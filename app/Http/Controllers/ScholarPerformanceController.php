<?php

namespace App\Http\Controllers;

use App\Models\Scholar;
use App\Models\ScholarPerformance;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScholarPerformanceController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $query = ScholarPerformance::with(['scholar.user']);

        // Apply filters
        if ($request->search) {
            $query->whereHas('scholar.user', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->semester) {
            $query->where('semester', $request->semester);
        }

        if ($request->school_year) {
            $query->where('school_year', $request->school_year);
        }

        if ($request->academic_status) {
            $query->where('academic_status', $request->academic_status);
        }

        if ($request->meets_requirements !== null) {
            $query->where('meets_retention_requirements', $request->meets_requirements);
        }

        $performance = $query->latest('submitted_at')->paginate(15);

        // Get statistics
        $stats = [
            'total_records' => ScholarPerformance::count(),
            'good_standing' => ScholarPerformance::where('academic_status', 'good')->count(),
            'on_warning' => ScholarPerformance::where('academic_status', 'warning')->count(),
            'on_probation' => ScholarPerformance::where('academic_status', 'probation')->count(),
            'meets_requirements' => ScholarPerformance::where('meets_retention_requirements', true)->count(),
            'needs_attention' => ScholarPerformance::whereIn('academic_status', ['warning', 'probation'])->count(),
        ];

        $semesters = ['First', 'Second', 'Summer'];
        $currentYear = date('Y');
        $schoolYears = [
            ($currentYear-1) . '-' . $currentYear,
            $currentYear . '-' . ($currentYear+1),
            ($currentYear+1) . '-' . ($currentYear+2)
        ];

        return view('scholar-performance.index', compact('performance', 'stats', 'semesters', 'schoolYears'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $scholars = Scholar::with('user')
            ->where('status', 'active')
            ->get();

        $semesters = ['First', 'Second', 'Summer'];
        $currentYear = date('Y');
        $schoolYears = [
            ($currentYear-1) . '-' . $currentYear,
            $currentYear . '-' . ($currentYear+1),
            ($currentYear+1) . '-' . ($currentYear+2)
        ];

        return view('scholar-performance.create', compact('scholars', 'semesters', 'schoolYears'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'scholar_id' => 'required|exists:scholars,id',
            'semester' => 'required|string',
            'school_year' => 'required|string',
            'gwa' => 'required|numeric|min:1.00|max:5.00',
            'units_enrolled' => 'required|integer|min:0',
            'units_completed' => 'required|integer|min:0',
            'units_failed' => 'required|integer|min:0',
            'subjects_enrolled' => 'required|integer|min:0',
            'subjects_passed' => 'required|integer|min:0',
            'subjects_failed' => 'required|integer|min:0',
            'subjects_dropped' => 'required|integer|min:0',
            'academic_remarks' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Check for existing performance record
            $existing = ScholarPerformance::where([
                'scholar_id' => $request->scholar_id,
                'semester' => $request->semester,
                'school_year' => $request->school_year,
            ])->first();

            if ($existing) {
                return back()->with('error', 'Performance record already exists for this scholar, semester, and school year.');
            }

            // Create performance record
            $performance = ScholarPerformance::create([
                'scholar_id' => $request->scholar_id,
                'semester' => $request->semester,
                'school_year' => $request->school_year,
                'gwa' => $request->gwa,
                'units_enrolled' => $request->units_enrolled,
                'units_completed' => $request->units_completed,
                'units_failed' => $request->units_failed,
                'subjects_enrolled' => $request->subjects_enrolled,
                'subjects_passed' => $request->subjects_passed,
                'subjects_failed' => $request->subjects_failed,
                'subjects_dropped' => $request->subjects_dropped,
                'academic_remarks' => $request->academic_remarks,
                'submitted_at' => now(),
            ]);

            // Calculate academic status and requirements
            $performance->update([
                'academic_status' => $performance->calculateAcademicStatus(),
                'meets_retention_requirements' => $performance->meetsGWARequirement() && 
                                                $performance->meetsUnitsRequirement() && 
                                                $performance->meetsNoFailedSubjectsRequirement(),
            ]);

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Performance record created successfully.']);
            }
            return redirect()->route('scholar-performance.index')->with('success', 'Performance record created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Failed to create performance record.'], 500);
            }
            return back()->with('error', 'Failed to create performance record.');
        }
    }

    public function show($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $performance = ScholarPerformance::with(['scholar.user', 'reviewer'])->findOrFail($id);

        return view('scholar-performance.show', compact('performance'));
    }

    public function edit($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $performance = ScholarPerformance::with(['scholar.user'])->findOrFail($id);
        $scholars = Scholar::with('user')->where('status', 'active')->get();

        $semesters = ['First', 'Second', 'Summer'];
        $currentYear = date('Y');
        $schoolYears = [
            ($currentYear-1) . '-' . $currentYear,
            $currentYear . '-' . ($currentYear+1),
            ($currentYear+1) . '-' . ($currentYear+2)
        ];

        return view('scholar-performance.edit', compact('performance', 'scholars', 'semesters', 'schoolYears'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $performance = ScholarPerformance::findOrFail($id);

        $request->validate([
            'gwa' => 'required|numeric|min:1.00|max:5.00',
            'units_enrolled' => 'required|integer|min:0',
            'units_completed' => 'required|integer|min:0',
            'units_failed' => 'required|integer|min:0',
            'subjects_enrolled' => 'required|integer|min:0',
            'subjects_passed' => 'required|integer|min:0',
            'subjects_failed' => 'required|integer|min:0',
            'subjects_dropped' => 'required|integer|min:0',
            'academic_remarks' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $performance->update([
                'gwa' => $request->gwa,
                'units_enrolled' => $request->units_enrolled,
                'units_completed' => $request->units_completed,
                'units_failed' => $request->units_failed,
                'subjects_enrolled' => $request->subjects_enrolled,
                'subjects_passed' => $request->subjects_passed,
                'subjects_failed' => $request->subjects_failed,
                'subjects_dropped' => $request->subjects_dropped,
                'academic_remarks' => $request->academic_remarks,
            ]);

            // Recalculate academic status and requirements
            $performance->update([
                'academic_status' => $performance->calculateAcademicStatus(),
                'meets_retention_requirements' => $performance->meetsGWARequirement() && 
                                                $performance->meetsUnitsRequirement() && 
                                                $performance->meetsNoFailedSubjectsRequirement(),
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
            ]);

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Performance record updated successfully.']);
            }
            return redirect()->route('scholar-performance.index')->with('success', 'Performance record updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Failed to update performance record.'], 500);
            }
            return back()->with('error', 'Failed to update performance record.');
        }
    }

    public function destroy($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $performance = ScholarPerformance::findOrFail($id);

        try {
            $performance->delete();

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Performance record deleted successfully.']);
            }
            return redirect()->route('scholar-performance.index')->with('success', 'Performance record deleted successfully.');

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Failed to delete performance record.'], 500);
            }
            return back()->with('error', 'Failed to delete performance record.');
        }
    }

    public function analytics()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        // Performance analytics data
        $analytics = [
            'total_scholars' => Scholar::where('status', 'active')->count(),
            'total_performance_records' => ScholarPerformance::count(),
            'average_gwa' => ScholarPerformance::avg('gwa'),
            'average_completion_rate' => ScholarPerformance::avg(DB::raw('(units_completed / units_enrolled) * 100')),
            'average_pass_rate' => ScholarPerformance::avg(DB::raw('(subjects_passed / subjects_enrolled) * 100')),
        ];

        // Academic status distribution
        $statusDistribution = ScholarPerformance::select('academic_status', DB::raw('count(*) as count'))
            ->groupBy('academic_status')
            ->get();

        // Performance trends by semester
        $semesterTrends = ScholarPerformance::select('semester', 'school_year', 
                DB::raw('avg(gwa) as avg_gwa'),
                DB::raw('avg((units_completed / units_enrolled) * 100) as avg_completion_rate'))
            ->groupBy('semester', 'school_year')
            ->orderBy('school_year', 'desc')
            ->orderBy('semester')
            ->get();

        // Top performing scholars
        $topPerformers = ScholarPerformance::with(['scholar.user'])
            ->orderBy('gwa', 'asc')
            ->limit(10)
            ->get();

        // Scholars needing attention
        $needsAttention = ScholarPerformance::with(['scholar.user'])
            ->whereIn('academic_status', ['warning', 'probation'])
            ->orderBy('gwa', 'desc')
            ->limit(10)
            ->get();

        return view('scholar-performance.analytics', compact('analytics', 'statusDistribution', 'semesterTrends', 'topPerformers', 'needsAttention'));
    }
}
