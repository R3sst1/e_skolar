<?php

namespace App\Http\Controllers;

use App\Models\Scholar;
use App\Models\ScholarRenewal;
use App\Models\RenewalDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RenewalController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $renewals = ScholarRenewal::with(['scholar.user'])
            ->when(request('status'), function($query, $status) {
                return $query->where('status', $status);
            })
            ->when(request('semester'), function($query, $semester) {
                return $query->where('semester', $semester);
            })
            ->when(request('school_year'), function($query, $schoolYear) {
                return $query->where('school_year', $schoolYear);
            })
            ->latest()
            ->paginate(10);

        $stats = [
            'total_pending' => ScholarRenewal::where('status', 'pending')->count(),
            'total_under_review' => ScholarRenewal::where('status', 'under_review')->count(),
            'total_approved' => ScholarRenewal::where('status', 'approved')->count(),
            'total_rejected' => ScholarRenewal::where('status', 'rejected')->count(),
        ];

        return view('dashboardcontent.renewals.index', compact('renewals', 'stats'));
    }

    public function create()
    {
        // Check if user is a scholar
        $scholar = Scholar::where('user_id', Auth::id())->first();
        
        if (!$scholar) {
            return redirect()->route('dashboard')->with('error', 'You are not a registered scholar.');
        }

        // Check if there's already a pending renewal for current semester
        $currentSemester = $this->getCurrentSemester();
        $currentSchoolYear = $this->getCurrentSchoolYear();
        
        $existingRenewal = ScholarRenewal::where('scholar_id', $scholar->id)
            ->where('semester', $currentSemester)
            ->where('school_year', $currentSchoolYear)
            ->whereIn('status', ['pending', 'under_review'])
            ->first();

        if ($existingRenewal) {
            return redirect()->route('renewals.status')->with('info', 'You already have a renewal application for this semester.');
        }

        return view('dashboardcontent.renewals.create', compact('scholar'));
    }

    public function store(Request $request)
    {
        // Renewal deadline check
        $deadlineDays = \App\Models\SystemSetting::getValue('renewal_deadline_days', 30);
        $semester = $request->semester;
        $schoolYear = $request->school_year;
        // Assume semester start is June 1 for First, Nov 1 for Second (customize as needed)
        $startDate = null;
        if (stripos($semester, 'first') !== false) {
            $startDate = \Carbon\Carbon::parse(explode('-', $schoolYear)[0] . '-06-01');
        } elseif (stripos($semester, 'second') !== false) {
            $startDate = \Carbon\Carbon::parse(explode('-', $schoolYear)[0] . '-11-01');
        }
        if ($startDate) {
            $deadline = $startDate->copy()->subDays($deadlineDays);
            if (now()->greaterThan($deadline)) {
                return back()->with('error', 'Renewal deadline has passed. No new renewals are accepted for this semester.');
            }
        }

        $request->validate([
            'semester' => 'required|in:First,Second,Summer',
            'school_year' => 'required|string',
            'gwa' => 'required|numeric|min:1.0|max:4.0',
            'academic_status' => 'required|string',
            'academic_remarks' => 'nullable|string',
            'documents.0' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'documents.1' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'documents.2' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'documents.3' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'document_types.0' => 'required|string',
            'document_types.1' => 'nullable|string',
            'document_types.2' => 'nullable|string',
            'document_types.3' => 'nullable|string',
        ]);

        $scholar = Scholar::where('user_id', Auth::id())->first();
        
        if (!$scholar) {
            return redirect()->back()->with('error', 'You are not a registered scholar.');
        }

        DB::beginTransaction();
        try {
            // Create renewal
            $renewal = ScholarRenewal::create([
                'scholar_id' => $scholar->id,
                'renewal_number' => ScholarRenewal::generateRenewalNumber(),
                'semester' => $request->semester,
                'school_year' => $request->school_year,
                'gwa' => $request->gwa,
                'academic_status' => $request->academic_status,
                'academic_remarks' => $request->academic_remarks,
                'status' => 'pending',
                'submitted_at' => now(),
            ]);

            // Upload documents
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $index => $file) {
                    $documentType = $request->document_types[$index];
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('renewal_documents', $fileName, 'public');

                    RenewalDocument::create([
                        'renewal_id' => $renewal->id,
                        'document_type' => $documentType,
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'file_size' => $file->getSize(),
                        'status' => 'pending',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('renewals.status')->with('success', 'Renewal application submitted successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to submit renewal application. Please try again.');
        }
    }

    public function show(ScholarRenewal $renewal)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $renewal->load(['scholar.user', 'documents']);
        
        return view('dashboardcontent.renewals.show', compact('renewal'));
    }

    public function review(Request $request, ScholarRenewal $renewal)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_remarks' => 'nullable|string',
        ]);

        // Retention requirements check (only if approving)
        if ($request->status === 'approved') {
            $minGwa = \App\Models\SystemSetting::getValue('minimum_gwa_for_retention', 2.5);
            $minUnits = \App\Models\SystemSetting::getValue('minimum_units_per_semester', 12);
            $maxFailed = \App\Models\SystemSetting::getValue('maximum_failed_subjects', 2);

            $gwa = $renewal->gwa;
            $units = $renewal->units ?? null; // Make sure this field exists in your model/migration
            $failed = $renewal->failed_subjects ?? null; // Make sure this field exists in your model/migration

            $errors = [];
            if ($gwa > $minGwa) {
                $errors[] = 'GWA ('.$gwa.") is above the minimum required (".$minGwa.").";
            }
            if ($units !== null && $units < $minUnits) {
                $errors[] = 'Units taken ('.$units.") is below the minimum required (".$minUnits.").";
            }
            if ($failed !== null && $failed > $maxFailed) {
                $errors[] = 'Failed subjects ('.$failed.") exceeds the maximum allowed (".$maxFailed.").";
            }
            if (count($errors) > 0) {
                return back()->with('error', 'Cannot approve renewal: '.implode(' ', $errors));
            }
        }

        $renewal->update([
            'status' => $request->status,
            'admin_remarks' => $request->admin_remarks,
            'reviewed_at' => now(),
        ]);

        if ($request->status === 'approved') {
            $renewal->update(['approved_at' => now()]);
        } else {
            $renewal->update(['rejected_at' => now()]);
        }

        return redirect()->route('renewals.index')->with('success', 'Renewal application ' . $request->status . ' successfully.');
    }

    public function status()
    {
        $scholar = Scholar::where('user_id', Auth::id())->first();
        
        if (!$scholar) {
            return redirect()->route('dashboard')->with('error', 'You are not a registered scholar.');
        }

        $renewals = $scholar->renewals()->with('documents')->latest()->get();
        
        return view('dashboardcontent.renewals.status', compact('scholar', 'renewals'));
    }

    public function downloadDocument(RenewalDocument $document)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    // Admin: View scholar eligibility and manage renewals per semester
    public function adminEligibility(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $query = \App\Models\ScholarRenewal::with(['scholar.user']);
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }
        if ($request->filled('school_year')) {
            $query->where('school_year', $request->school_year);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $renewals = $query->latest('submitted_at')->paginate(20);

        // Summary counts
        $summary = [
            'eligible' => $query->clone()->where('meets_retention_requirements', true)->count(),
            'at_risk' => $query->clone()->where('meets_retention_requirements', false)->where('status', '!=', 'rejected')->count(),
            'not_eligible' => $query->clone()->where('status', 'rejected')->count(),
            'total' => $query->clone()->count(),
        ];

        $semesters = ['First', 'Second', 'Summer'];
        $currentYear = date('Y');
        $schoolYears = [($currentYear-1) . '-' . $currentYear, $currentYear . '-' . ($currentYear+1)];
        $statuses = ['pending', 'approved', 'rejected', 'needs_additional_requirements'];

        return view('renewals.admin-eligibility', compact('renewals', 'summary', 'semesters', 'schoolYears', 'statuses'));
    }

    private function getCurrentSemester()
    {
        $month = date('n');
        if ($month >= 6 && $month <= 10) {
            return 'First';
        } elseif ($month >= 11 || $month <= 3) {
            return 'Second';
        } else {
            return 'Summer';
        }
    }

    private function getCurrentSchoolYear()
    {
        $year = date('Y');
        $month = date('n');
        
        if ($month >= 6) {
            return $year . '-' . ($year + 1);
        } else {
            return ($year - 1) . '-' . $year;
        }
    }
} 