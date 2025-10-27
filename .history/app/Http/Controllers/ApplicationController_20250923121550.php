<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Requirement;
use App\Models\Scholar;
use App\Notifications\ApplicationStatusChanged;
use App\Notifications\DocumentStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Auth::user()->applications()
            ->latest()
            ->paginate(10);

        return view('applications.index', compact('applications'));
    }

    public function create()
    {
        // Check if user has a pending application
        $hasPendingApplication = Auth::user()->applications()
            ->whereIn('status', ['pending', 'under_review'])
            ->exists();

        if ($hasPendingApplication) {
            return redirect()->route('applications.index')
                ->with('error', 'You already have a pending application.');
        }

        // Check if user is already a scholar
        if (Auth::user()->isScholar()) {
            return redirect()->route('applications.index')
                ->with('error', 'You are already a scholar and cannot apply again.');
        }

        // Check if user has any approved application (already a scholar)
        $hasApprovedApplication = Auth::user()->applications()
            ->where('status', 'approved')
            ->exists();

        if ($hasApprovedApplication) {
            return redirect()->route('applications.index')
                ->with('error', 'You already have an approved application and are a scholar.');
        }

        $scholarships = \App\Models\ScholarshipProgram::where('status', 'active')->get();
        return view('applications.create', compact('scholarships'));
    }

    public function store(Request $request)
    {
        // Application deadline check (REMOVED)
        // $deadlineDays = \App\Models\SystemSetting::getValue('application_deadline_days', 60);
        // $semester = $request->semester;
        // $schoolYear = $request->school_year;
        // // Assume semester start is June 1 for First, Nov 1 for Second (customize as needed)
        // $startDate = null;
        // if (stripos($semester, 'first') !== false) {
        //     $startDate = \Carbon\Carbon::parse(explode('-', $schoolYear)[0] . '-06-01');
        // } elseif (stripos($semester, 'second') !== false) {
        //     $startDate = \Carbon\Carbon::parse(explode('-', $schoolYear)[0] . '-11-01');
        // }
        // if ($startDate) {
        //     $deadline = $startDate->copy()->subDays($deadlineDays);
        //     if (now()->greaterThan($deadline)) {
        //         return back()->with('error', 'Application deadline has passed. No new applications are accepted for this semester.');
        //     }
        // }

        $request->validate([
            'scholarship_id' => 'required|exists:tbl_scholarship_programs,id',
            'school' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'year_level' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'school_year' => 'required|string|max:255',
            'gwa' => 'required|numeric|min:1.00|max:5.00',
            'family_income' => 'required|numeric|min:0',
            'reason_for_application' => 'required|string',
            'grade_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Additional validation: Check if user already has an approved application for this specific scholarship
        $existingApprovedApplication = Auth::user()->applications()
            ->where('scholarship_id', $request->scholarship_id)
            ->where('status', 'approved')
            ->exists();

        if ($existingApprovedApplication) {
            return back()->with('error', 'You already have an approved application for this scholarship program.')
                ->withInput();
        }

        // Check if user is already a scholar
        if (Auth::user()->isScholar()) {
            return back()->with('error', 'You are already a scholar and cannot apply again.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Handle grade photo upload
            $gradePhotoPath = null;
            if ($request->hasFile('grade_photo')) {
                $gradePhoto = $request->file('grade_photo');
                $gradePhotoPath = $gradePhoto->store('grades', 'public');
            }

            // Create application
            $application = Auth::user()->applications()->create([
                'school' => $request->school,
                'course' => $request->course,
                'year_level' => $request->year_level,
                'semester' => $request->semester,
                'school_year' => $request->school_year,
                'gwa' => $request->gwa,
                'family_income' => $request->family_income,
                'reason_for_application' => $request->reason_for_application,
                'grade_photo' => $gradePhotoPath,
                'scholarship_id' => $request->scholarship_id,
            ]);

            DB::commit();

            return redirect()->route('applications.status', $application->id)
                ->with('success', 'Application submitted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Application submission failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to submit application: ' . $e->getMessage());
        }
    }

    public function status($id)
    {
        if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
            $application = \App\Models\Application::findOrFail($id);
        } else {
            $application = Auth::user()->applications()->findOrFail($id);
        }
        if (request()->ajax() || request()->wantsJson() || request()->header('Accept') === 'text/html') {
            return view('applications.status-partial', compact('application'));
        }
        return view('applications.status', compact('application'));
    }

    public function downloadRequirement($id)
    {
        $requirement = Requirement::findOrFail($id);
        
        // Check if user is authorized to download
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin() && 
            Auth::id() !== $requirement->application->user_id) {
            abort(403);
        }

        return Storage::disk('public')->download($requirement->file_path);
    }

    public function approve($id)
    {
        \Log::info('Approve method called', ['application_id' => $id, 'user' => Auth::user()->username]);
        
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            \Log::warning('Unauthorized access attempt to approve application', ['user_id' => Auth::user()->id]);
            abort(403);
        }

        $application = Application::findOrFail($id);
        \Log::info('Application found', ['application' => $application->toArray()]);

        // Scholar limit check
        $maxScholarsInstitution = \App\Models\SystemSetting::getValue('max_scholars_per_institution', 50);
        $currentScholarsInstitution = \App\Models\Scholar::where('institution', $application->school)
            ->where('status', 'active')
            ->count();
        \Log::info('Scholar limits check', [
            'max_scholars_institution' => $maxScholarsInstitution,
            'current_scholars_institution' => $currentScholarsInstitution,
            'application_school' => $application->school
        ]);
        
        if ($currentScholarsInstitution >= $maxScholarsInstitution) {
            $message = 'Cannot approve: Maximum number of scholars for this institution ('.$application->school.') reached.';
            \Log::warning('Scholar limit reached for institution', ['institution' => $application->school]);
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'error' => $message], 422);
            }
            return back()->with('error', $message);
        }

        // Scholar limit per course
        $maxScholarsCourse = \App\Models\SystemSetting::getValue('max_scholars_per_course', 15);
        $currentScholarsCourse = \App\Models\Scholar::where('course', $application->course)
            ->where('status', 'active')
            ->count();
        if ($currentScholarsCourse >= $maxScholarsCourse) {
            $message = 'Cannot approve: Maximum number of scholars for this course ('.$application->course.') reached.';
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'error' => $message], 422);
            }
            return back()->with('error', $message);
        }

        // Scholar limit per barangay
        $maxScholarsBarangay = \App\Models\SystemSetting::getValue('max_scholars_per_barangay', 20);
        $userBarangay = $application->user->barangay ?? 'Not Specified';
        $currentScholarsBarangay = \App\Models\Scholar::where('barangay', $userBarangay)
            ->where('status', 'active')
            ->count();
        if ($currentScholarsBarangay >= $maxScholarsBarangay) {
            $message = 'Cannot approve: Maximum number of scholars for this barangay ('.$userBarangay.') reached.';
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'error' => $message], 422);
            }
            return back()->with('error', $message);
        }

        try {
            \Log::info('Starting approval process', ['application_id' => $id]);
            
            DB::beginTransaction();
            \Log::info('Database transaction started');

            // Update application status
            \Log::info('Updating application status to approved');
            $application->update([
                'status' => 'approved',
                'approved_at' => now(),
                'admin_remarks' => request('remarks'),
            ]);
            \Log::info('Application status updated successfully');

            // Create scholar record
            \Log::info('Creating scholar record', [
                'user_id' => $application->user_id,
                'institution' => $application->school,
                'barangay' => $application->user->barangay ?? 'Not Specified',
                'course' => $application->course,
                'year_level' => $application->year_level
            ]);
            
            $scholar = Scholar::create([
                'user_id' => $application->user_id,
                'institution' => $application->school,
                'barangay' => $application->user->barangay ?? 'Not Specified',
                'course' => $application->course,
                'year_level' => $application->year_level,
                'status' => 'active',
                'category' => request('category', 'Student'),
                'start_date' => now(),
            ]);
            \Log::info('Scholar record created successfully', ['scholar_id' => $scholar->id]);

            // Send notification
            \Log::info('Sending notification to user');
            $application->user->notify(new ApplicationStatusChanged($application));
            \Log::info('Notification sent successfully');

            DB::commit();
            \Log::info('Database transaction committed successfully');

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Application approved successfully.']);
            }
            return back()->with('success', 'Application approved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Approval process failed', [
                'application_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Failed to approve application. Please try again.'], 500);
            }
            return back()->with('error', 'Failed to approve application. Please try again.');
        }
    }

    public function reject($id)
    {
        \Log::info('Reject method called', ['application_id' => $id, 'user' => Auth::user()->username]);
        
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            \Log::warning('Unauthorized access attempt to reject application', ['user_id' => Auth::user()->id]);
            abort(403);
        }

        $application = Application::findOrFail($id);
        \Log::info('Application found for rejection', ['application' => $application->toArray()]);

        try {
            DB::beginTransaction();

            // Update application status
            $application->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'admin_remarks' => request('remarks'),
            ]);

            // Send notification
            $application->user->notify(new ApplicationStatusChanged($application));

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Application rejected successfully.']);
            }
            return back()->with('success', 'Application rejected successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Failed to reject application. Please try again.'], 500);
            }
            return back()->with('error', 'Failed to reject application. Please try again.');
        }
    }

    // Admin: Request additional requirements or provide feedback
    public function requestAdditionalRequirements($id, Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }
        $application = Application::findOrFail($id);
        $request->validate([
            'admin_remarks' => 'required|string|max:2000',
        ]);
        $application->update([
            'status' => 'needs_additional_requirements',
            'admin_remarks' => $request->admin_remarks,
        ]);
        // Notify applicant
        $application->user->notify(new \App\Notifications\ApplicationStatusChanged($application));
        return back()->with('success', 'Feedback/Additional requirements sent to applicant.');
    }

    public function applicants(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $query = Application::with(['user', 'scholarship'])
            ->whereIn('status', ['pending', 'under_review']);

        // Filter by scholarship program if provided
        if ($request->scholarship_id) {
            $query->where('scholarship_id', $request->scholarship_id);
        }

        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        $applications = $query->latest()->paginate(10);
        
        // Get scholarship program info if filtering by scholarship
        $scholarshipProgram = null;
        if ($request->scholarship_id) {
            $scholarshipProgram = \App\Models\ScholarshipProgram::find($request->scholarship_id);
        }

        return view('dashboardcontent.applicants', compact('applications', 'scholarshipProgram'));
    }
}

