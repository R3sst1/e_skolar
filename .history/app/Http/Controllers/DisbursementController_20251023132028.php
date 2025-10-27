<?php

namespace App\Http\Controllers;

use App\Models\DisbursementBatch;
use App\Models\DisbursementBatchStudent;
use App\Models\Application;
use App\Models\AllocationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DisbursementController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $query = DisbursementBatch::with('disbursementBatchStudents.application.user');

        // Apply filters
        if ($request->search) {
            $query->where('reference_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('disbursementBatchStudents.application.user', function ($q) use ($request) {
                      $q->where('first_name', 'like', '%' . $request->search . '%')
                        ->orWhere('last_name', 'like', '%' . $request->search . '%');
                  });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $disbursementBatches = $query->latest()->paginate(15);

        // Get statistics
        $stats = [
            'total_batches' => DisbursementBatch::count(),
            'pending_batches' => DisbursementBatch::where('status', 'pending')->count(),
            'reviewed_batches' => DisbursementBatch::where('status', 'reviewed')->count(),
            'disbursed_batches' => DisbursementBatch::where('status', 'disbursed')->count(),
            'total_amount' => DisbursementBatch::sum('total_amount'),
        ];

        // Get total allocated budget for Scholarship Office (office_id = 6)
        $totalAllocated = AllocationLog::forOffice(6)->allocations()->sum('amount');
        $totalDisbursed = AllocationLog::forOffice(6)->disbursements()->sum('amount');
        $remainingBalance = $totalAllocated - $totalDisbursed;

        // Get scholarship program budget information
        $scholarshipBudgets = \App\Models\ScholarshipProgram::where('type', 'budgeted')
            ->whereNotNull('allocated_budget')
            ->get()
            ->map(function ($program) {
                $disbursed = DisbursementBatch::where('scholarship_program_id', $program->id)
                    ->whereIn('status', ['reviewed', 'disbursed'])
                    ->sum('total_amount');
                
                return [
                    'id' => $program->id,
                    'name' => $program->name,
                    'allocated_budget' => $program->allocated_budget,
                    'disbursed_amount' => $disbursed,
                    'remaining_budget' => $program->allocated_budget - $disbursed,
                ];
            });

        // Get approved applications for the modal
        $approvedApplications = Application::with(['user', 'scholarship'])
            ->where('status', 'approved')
            ->whereDoesntHave('disbursementBatchStudents', function($query) {
                $query->where('status', '!=', 'rejected');
            })
            ->get();

        // Get scholarship programs for the dropdown
        $scholarshipPrograms = \App\Models\ScholarshipProgram::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('disbursements.index', compact('disbursementBatches', 'stats', 'approvedApplications', 'scholarshipPrograms', 'scholarshipBudgets', 'totalAllocated', 'totalDisbursed', 'remainingBalance'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        // Get approved applications that haven't been included in any disbursement batch
        $availableApplications = Application::where('status', 'approved')
            ->whereDoesntHave('disbursementBatchStudents')
            ->with('user', 'scholarship')
            ->get();

        return view('disbursements.create', compact('availableApplications'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'remarks' => 'nullable|string|max:1000',
            'application_ids' => 'required|array|min:1',
            'application_ids.*' => 'exists:applications,id',
        ]);

        DB::beginTransaction();
        try {
            // Create disbursement batch
            $disbursementBatch = DisbursementBatch::create([
                'remarks' => $request->remarks,
                'status' => 'pending',
            ]);

            // Add applications to the batch
            foreach ($request->application_ids as $applicationId) {
                DisbursementBatchStudent::create([
                    'disbursement_batch_id' => $disbursementBatch->id,
                    'application_id' => $applicationId,
                    'status' => 'pending',
                ]);
            }

            // Calculate total amount based on scholarship programs
            $totalAmount = 0;
            foreach ($disbursementBatch->disbursementBatchStudents as $batchStudent) {
                if ($batchStudent->application->scholarship && $batchStudent->application->scholarship->per_scholar_amount) {
                    $totalAmount += $batchStudent->application->scholarship->per_scholar_amount;
                }
            }

            $disbursementBatch->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('disbursements.index')
                ->with('success', 'Disbursement batch created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to create disbursement batch: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function storeBatch(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'scholarship_program_id' => 'required|exists:tbl_scholarship_programs,id',
            'scholar_ids' => 'required|array|min:1',
            'scholar_ids.*' => 'exists:scholars,id',
            'scholar_amounts' => 'required|array',
            'scholar_amounts.*' => 'numeric|min:0',
            'remarks' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            // Get scholarship program
            $scholarshipProgram = \App\Models\ScholarshipProgram::findOrFail($request->scholarship_program_id);
            
            // Get selected scholars
            $scholars = \App\Models\Scholar::whereIn('id', $request->scholar_ids)
                ->where('status', 'active')
                ->get();

            if ($scholars->isEmpty()) {
                throw new \Exception('No valid scholars found.');
            }

            // Calculate total amount from individual scholar amounts
            $totalAmount = 0;
            foreach ($request->scholar_amounts as $scholarId => $amount) {
                if (in_array($scholarId, $request->scholar_ids)) {
                    $totalAmount += $amount;
                }
            }

            // Check allocated budget for Scholarship Office (office_id = 6)
            $totalAllocated = AllocationLog::forOffice(6)->allocations()->sum('amount');
            $totalDisbursed = AllocationLog::forOffice(6)->disbursements()->sum('amount');
            $remainingBalance = $totalAllocated - $totalDisbursed;

            if ($totalAmount > $remainingBalance) {
                $deficit = $totalAmount - $remainingBalance;
                DB::rollBack();
                return redirect()->back()->with('error', 'Insufficient allocated budget. You need ₱' . number_format($deficit, 2) . ' more. Current remaining balance: ₱' . number_format($remainingBalance, 2) . '. Please request additional budget allocation from E-Kalinga system.');
            }

            // Create disbursement batch and immediately disburse
            $batch = DisbursementBatch::create([
                'scholarship_program_id' => $request->scholarship_program_id,
                'status' => 'disbursed', // Directly disburse instead of pending
                'total_amount' => $totalAmount,
                'remarks' => $request->remarks
            ]);

            // Create disbursement batch students with disbursed status
            foreach ($scholars as $scholar) {
                $application = $scholar->user->applications()
                    ->where('scholarship_id', $request->scholarship_program_id)
                    ->where('status', 'approved')
                    ->first();

                $individualAmount = $request->scholar_amounts[$scholar->id] ?? $scholarshipProgram->per_scholar_amount ?? 0;

                DisbursementBatchStudent::create([
                    'disbursement_batch_id' => $batch->id,
                    'student_id' => $scholar->id,
                    'application_id' => $application->id ?? null,
                    'status' => 'disbursed', // Directly disbursed
                    'requested_amount' => $individualAmount,
                    'actual_amount' => $individualAmount,
                    'release_status' => 'released', // Directly released
                    'released_at' => now(),
                    'remarks' => null
                ]);
            }

            // Log the disbursement in allocation logs
            AllocationLog::create([
                'office_id' => 6, // Scholarship Office ID
                'allocated_by' => Auth::id(),
                'disbursement_batch_id' => $batch->id,
                'transaction_type' => 'disbursement',
                'amount' => $totalAmount,
                'description' => 'Disbursement for ' . $scholarshipProgram->name . ' - ' . $scholars->count() . ' scholars',
                'reference_number' => $batch->reference_number ?? 'BATCH-' . $batch->id,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Disbursement completed successfully for ' . $scholars->count() . ' scholars. Total amount: ₱' . number_format($totalAmount, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process disbursement: ' . $e->getMessage());
        }
    }

    public function getScholarsByProgram(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'scholarship_program_id' => 'required|exists:tbl_scholarship_programs,id'
        ]);

        // Get scholarship program details
        $scholarshipProgram = \App\Models\ScholarshipProgram::findOrFail($request->scholarship_program_id);

        // Get scholars who have approved applications for this scholarship program
        $scholars = \App\Models\Scholar::with(['user'])
            ->where('status', 'active')
            ->whereHas('user.applications', function($query) use ($request) {
                $query->where('scholarship_id', $request->scholarship_program_id)
                      ->where('status', 'approved');
            })
            ->get()
            ->map(function($scholar) use ($scholarshipProgram) {
                return [
                    'id' => $scholar->id,
                    'name' => $scholar->user->first_name . ' ' . $scholar->user->last_name,
                    'course' => $scholar->course,
                    'year_level' => $scholar->year_level,
                    'institution' => $scholar->institution,
                    'amount' => $scholarshipProgram->per_scholar_amount ?? 0
                ];
            });

        return response()->json($scholars);
    }

    public function show($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $disbursementBatch = DisbursementBatch::with([
                'disbursementBatchStudents.application.user',
                'disbursementBatchStudents.application.scholarship',
                'disbursementBatchStudents.scholar.user',
            ])->findOrFail($id);

        return view('disbursements.show', compact('disbursementBatch'));
    }


    public function markAsReceived($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $disbursementBatchStudent = DisbursementBatchStudent::findOrFail($id);
        
        if ($disbursementBatchStudent->status !== 'disbursed') {
            return redirect()->back()->with('error', 'Only disbursed items can be marked as received.');
        }

        $disbursementBatchStudent->update(['status' => 'received']);

        return redirect()->back()->with('success', 'Disbursement marked as received successfully!');
    }

    public function bulkAction(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'action' => 'required|in:approve,release',
            'batch_ids' => 'required|array|min:1',
            'batch_ids.*' => 'exists:tbl_disbursement_batches,id',
        ]);

        $batches = DisbursementBatch::whereIn('id', $request->batch_ids);

        switch ($request->action) {
            case 'approve':
                $batches->where('status', 'pending')->update(['status' => 'reviewed']);
                $message = 'Selected batches approved successfully!';
                break;
            case 'release':
                $batches->where('status', 'reviewed')->update(['status' => 'disbursed']);
                $message = 'Selected batches released successfully!';
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    public function scholarDisbursements(Request $request)
    {
        if (!Auth::user()->isApplicant()) {
            abort(403);
        }

        $userApplications = Application::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->pluck('id');

        $disbursementBatchStudents = DisbursementBatchStudent::whereIn('application_id', $userApplications)
            ->with('disbursementBatch', 'application.scholarship')
            ->latest()
            ->paginate(15);

        return view('disbursements.scholar-index', compact('disbursementBatchStudents'));
    }

    public function scholarDisbursementShow($id)
    {
        if (!Auth::user()->isApplicant()) {
            abort(403);
        }

        $disbursementBatchStudent = DisbursementBatchStudent::whereHas('application', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->with('disbursementBatch', 'application.scholarship')
            ->findOrFail($id);

        return view('disbursements.scholar-show', compact('disbursementBatchStudent'));
    }


    /**
     * View allocation logs for transparency
     */
    public function allocationLogs(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $query = AllocationLog::forOffice(6)->with('allocatedBy', 'disbursementBatch');

        // Apply filters
        if ($request->transaction_type) {
            $query->where('transaction_type', $request->transaction_type);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $allocationLogs = $query->latest()->paginate(20);

        return view('disbursements.allocation-logs', compact('allocationLogs'));
    }

}
