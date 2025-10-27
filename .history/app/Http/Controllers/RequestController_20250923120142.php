<?php

namespace App\Http\Controllers;

use App\Models\AssistanceRequest;
use App\Models\BudgetAllocation;
use App\Models\DisbursementBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $q = $request->get('q');
        $status = $request->get('status');

        $requests = AssistanceRequest::with(['office', 'constituent'])
            ->when($q, function ($query) use ($q) {
                $query->where('item_name', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%")
                    ->orWhereHas('constituent', function ($qq) use ($q) {
                        $qq->where('first_name', 'like', "%$q%")
                           ->orWhere('last_name', 'like', "%$q%");
                    });
            })
            ->when($status, fn ($q2) => $q2->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Aggregate budgets per office
        $officeBudgets = BudgetAllocation::select('office_id')
            ->selectRaw('SUM(amount) as total_allocated')
            ->selectRaw('SUM(used_amount) as total_used')
            ->selectRaw('SUM(remaining_amount) as total_remaining')
            ->groupBy('office_id')
            ->get()
            ->keyBy('office_id');

        return view('requests.index', compact('requests', 'officeBudgets'));
    }

    public function approve($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $requestModel = AssistanceRequest::with('office')->findOrFail($id);
        if ($requestModel->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be approved.');
        }

        DB::beginTransaction();
        try {
            // Get active allocation for the office
            $allocation = BudgetAllocation::where('office_id', $requestModel->office_id)
                ->where('status', 'active')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            if (!$allocation) {
                throw new \Exception('No active budget allocation for this office.');
            }

            $amount = $requestModel->amountToCharge();

            if ((float) $allocation->remaining_amount < $amount) {
                throw new \Exception('Insufficient remaining budget for this office.');
            }

            // Deduct from allocation
            $allocation->update([
                'used_amount' => (float) $allocation->used_amount + $amount,
                'remaining_amount' => (float) $allocation->remaining_amount - $amount,
            ]);

            // Create or attach to a disbursement batch (office-level)
            $batch = DisbursementBatch::firstOrCreate(
                [
                    'remarks' => 'Office '.$requestModel->office_id.' Requests',
                    'status' => 'pending',
                ],
                [
                    'total_amount' => 0,
                ]
            );

            $requestModel->update([
                'status' => 'approved',
                'disbursement_batch_id' => $batch->id,
            ]);

            // Update batch total
            $batch->update([
                'total_amount' => (float) $batch->total_amount + $amount,
            ]);

            DB::commit();
            return back()->with('success', 'Request approved and budget reserved.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $requestModel = AssistanceRequest::findOrFail($id);
        if ($requestModel->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        $requestModel->update(['status' => 'rejected']);
        return back()->with('success', 'Request rejected.');
    }

    public function disburse($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $requestModel = AssistanceRequest::with('disbursementBatch')->findOrFail($id);
        if ($requestModel->status !== 'approved') {
            return back()->with('error', 'Only approved requests can be disbursed.');
        }

        DB::beginTransaction();
        try {
            // Ensure batch is at least reviewed, then mark disbursed
            $batch = $requestModel->disbursementBatch;
            if ($batch && $batch->status === 'pending') {
                $batch->update(['status' => 'reviewed']);
            }
            if ($batch) {
                $batch->update(['status' => 'disbursed']);
            }

            $requestModel->update(['status' => 'disbursed']);

            DB::commit();
            return back()->with('success', 'Request disbursed successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}


