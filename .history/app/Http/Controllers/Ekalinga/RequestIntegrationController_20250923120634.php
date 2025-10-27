<?php

namespace App\Http\Controllers\Ekalinga;

use App\Http\Controllers\Controller;
use App\Models\Ekalinga\Request as EkalingaRequest;
use App\Models\Ekalinga\BudgetAllocations as EkalingaBudgetAllocation;
use App\Models\Ekalinga\Offices as EkalingaOffice;
use App\Models\DisbursementBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestIntegrationController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $q = $request->get('q');
        $status = $request->get('status');

        $requests = EkalingaRequest::on('e_kalinga')
            ->when($q, function ($query) use ($q) {
                $query->where('item_name', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%")
                    ->orWhere('purpose', 'like', "%$q%")
                    ->orWhere('barangay', 'like', "%$q%");
            })
            ->when($status, fn ($q2) => $q2->where('status', $status))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        // Aggregate budgets per office from Ekalinga
        $officeBudgets = EkalingaBudgetAllocation::on('e_kalinga')
            ->select('office_id')
            ->selectRaw('SUM(amount) as total_allocated')
            ->selectRaw('SUM(used_amount) as total_used')
            ->selectRaw('SUM(remaining_amount) as total_remaining')
            ->groupBy('office_id')
            ->get()
            ->keyBy('office_id');

        // Map office names quickly
        $officeNames = EkalingaOffice::on('e_kalinga')->pluck('name', 'id');

        return view('ekalinga.requests.index', compact('requests', 'officeBudgets', 'officeNames'));
    }

    public function approve($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $req = EkalingaRequest::on('e_kalinga')->findOrFail($id);
        if ($req->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be approved.');
        }

        $amount = $this->amountToCharge($req);

        try {
            DB::connection('e_kalinga')->beginTransaction();

            $allocation = EkalingaBudgetAllocation::on('e_kalinga')
                ->where('office_id', $req->office_id)
                ->where('status', 'active')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            if (!$allocation) {
                throw new \Exception('No active budget allocation for this office.');
            }

            if ((float) $allocation->remaining_amount < $amount) {
                throw new \Exception('Insufficient remaining budget.');
            }

            // Deduct
            $allocation->update([
                'used_amount' => (float) $allocation->used_amount + $amount,
                'remaining_amount' => (float) $allocation->remaining_amount - $amount,
            ]);

            // Create or attach to local disbursement batch (pending)
            $batch = DisbursementBatch::firstOrCreate(
                [
                    'remarks' => 'Ekalinga Office '.$req->office_id.' Requests',
                    'status' => 'pending',
                ],
                [
                    'total_amount' => 0,
                ]
            );

            // Link Ekalinga request to local batch id
            $req->disbursement_batch_id = $batch->id;
            $req->status = 'approved';
            $req->save();

            // Update batch total locally
            $batch->update([
                'total_amount' => (float) $batch->total_amount + $amount,
            ]);

            DB::connection('e_kalinga')->commit();
            return back()->with('success', 'Request approved and budget reserved.');
        } catch (\Throwable $e) {
            DB::connection('e_kalinga')->rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $req = EkalingaRequest::on('e_kalinga')->findOrFail($id);
        if ($req->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        $req->status = 'rejected';
        $req->save();
        return back()->with('success', 'Request rejected.');
    }

    public function disburse($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $req = EkalingaRequest::on('e_kalinga')->findOrFail($id);
        if ($req->status !== 'approved') {
            return back()->with('error', 'Only approved requests can be disbursed.');
        }

        // Mark local batch reviewed/disbursed without affecting scholarship batches
        if ($req->disbursement_batch_id) {
            $batch = DisbursementBatch::find($req->disbursement_batch_id);
            if ($batch) {
                if ($batch->status === 'pending') {
                    $batch->update(['status' => 'reviewed']);
                }
                $batch->update(['status' => 'disbursed']);
            }
        }

        $req->status = 'disbursed';
        $req->save();

        return back()->with('success', 'Request disbursed successfully.');
    }

    private function amountToCharge($req): float
    {
        $qty = max(1, (int) ($req->quantity ?? 1));
        $qtyTotal = (float) ($req->item_cost ?? 0) * $qty;
        return (float) ($req->requested_amount ?? $qtyTotal);
    }
}


