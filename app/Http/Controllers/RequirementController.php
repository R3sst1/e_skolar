<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use App\Models\Application;
use App\Notifications\DocumentStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RequirementController extends Controller
{
    public function index($applicationId)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $application = Application::with(['requirements', 'user'])->findOrFail($applicationId);
        
        // Debug: Check if we're in the right context
        \Log::info('Requirements page accessed', [
            'application_id' => $applicationId,
            'user_id' => Auth::id(),
            'user_role' => Auth::user()->role
        ]);
        
        return view('requirements.index', compact('application'));
    }

    public function show($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $requirement = Requirement::with(['application.user'])->findOrFail($id);
        
        return view('requirements.show', compact('requirement'));
    }

    public function approve($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $requirement = Requirement::findOrFail($id);

        try {
            DB::beginTransaction();

            $requirement->update([
                'status' => 'approved',
                'remarks' => request('remarks'),
            ]);

            // Check if all requirements are approved
            $application = $requirement->application;
            $allRequirementsApproved = $application->requirements()
                ->where('status', '!=', 'approved')
                ->doesntExist();

            if ($allRequirementsApproved && $application->status === 'pending') {
                $application->update([
                    'status' => 'under_review',
                    'reviewed_at' => now(),
                ]);
            }

            // Send notification
            $requirement->application->user->notify(new DocumentStatusChanged($requirement));

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Document approved successfully.',
                    'all_approved' => $allRequirementsApproved
                ]);
            }
            return back()->with('success', 'Document approved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Failed to approve document.'], 500);
            }
            return back()->with('error', 'Failed to approve document.');
        }
    }

    public function reject($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $requirement = Requirement::findOrFail($id);

        try {
            DB::beginTransaction();

            $requirement->update([
                'status' => 'rejected',
                'remarks' => request('remarks'),
            ]);

            // If any requirement is rejected, application should be rejected
            $application = $requirement->application;
            $application->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'admin_remarks' => 'Application rejected due to document issues: ' . request('remarks'),
            ]);

            // Send notification
            $requirement->application->user->notify(new DocumentStatusChanged($requirement));

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Document rejected successfully.']);
            }
            return back()->with('success', 'Document rejected successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Failed to reject document.'], 500);
            }
            return back()->with('error', 'Failed to reject document.');
        }
    }

    public function download($id)
    {
        $requirement = Requirement::findOrFail($id);
        
        // Check if user is authorized to download
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin() && 
            Auth::id() !== $requirement->application->user_id) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($requirement->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($requirement->file_path, $requirement->name . '.' . $requirement->file_type);
    }

    public function preview($id)
    {
        $requirement = Requirement::findOrFail($id);
        
        // Check if user is authorized to view
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin() && 
            Auth::id() !== $requirement->application->user_id) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($requirement->file_path)) {
            abort(404, 'File not found');
        }

        $filePath = Storage::disk('public')->path($requirement->file_path);
        $fileType = $requirement->file_type;

        // For images, return the image directly
        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            return response()->file($filePath);
        }

        // For PDFs, return PDF viewer
        if ($fileType === 'pdf') {
            return response()->file($filePath);
        }

        // For other file types, offer download
        return Storage::disk('public')->download($requirement->file_path);
    }

    public function bulkAction(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'requirement_ids' => 'required|array',
            'requirement_ids.*' => 'exists:requirements,id'
        ]);

        $requirements = Requirement::whereIn('id', $request->requirement_ids);

        try {
            DB::beginTransaction();

            switch ($request->action) {
                case 'approve':
                    $requirements->update([
                        'status' => 'approved',
                        'remarks' => $request->remarks ?? null,
                    ]);
                    $message = 'Documents approved successfully.';
                    break;

                case 'reject':
                    $requirements->update([
                        'status' => 'rejected',
                        'remarks' => $request->remarks ?? null,
                    ]);
                    $message = 'Documents rejected successfully.';
                    break;
            }

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Failed to process bulk action.'], 500);
            }
            return back()->with('error', 'Failed to process bulk action.');
        }
    }

    // Admin: View all uploaded documents
    public function adminDocuments(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $query = \App\Models\Requirement::with(['application.user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('name', $request->type);
        }
        if ($request->filled('applicant')) {
            $query->whereHas('application.user', function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->applicant . '%')
                  ->orWhere('last_name', 'like', '%' . $request->applicant . '%')
                  ->orWhere('username', 'like', '%' . $request->applicant . '%');
            });
        }

        $documents = $query->latest()->paginate(20);
        $types = \App\Models\Requirement::select('name')->distinct()->pluck('name');
        $statuses = ['pending', 'approved', 'rejected'];

        return view('requirements.admin-documents', compact('documents', 'types', 'statuses'));
    }
} 