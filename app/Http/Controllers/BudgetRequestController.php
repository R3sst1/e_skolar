<?php

namespace App\Http\Controllers;

use App\Models\BudgetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetRequestController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $budgetRequests = BudgetRequest::orderBy('created_at', 'desc')->paginate(20);

        return view('budget-requests.index', compact('budgetRequests'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'requested_amount' => 'required|numeric|min:0',
            'purpose' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $budgetRequest = BudgetRequest::create([
                'office_id' => 6, // Scholarship Office ID
                'requested_amount' => $request->requested_amount,
                'purpose' => $request->purpose,
                'description' => $request->description,
                'status' => 'pending',
            ]);

            // TODO: Send request to E-Kalinga system if needed
            // This would integrate with the external E-Kalinga API

            DB::commit();

            return redirect()->back()->with('success', 'Budget request submitted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to submit budget request: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $budgetRequest = BudgetRequest::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        try {
            $budgetRequest->update([
                'status' => $request->status,
            ]);

            return redirect()->back()->with('success', 'Budget request status updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update budget request: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $budgetRequest = BudgetRequest::findOrFail($id);

        try {
            $budgetRequest->delete();
            return redirect()->back()->with('success', 'Budget request deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete budget request: ' . $e->getMessage());
        }
    }
}
