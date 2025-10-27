<?php

namespace App\Http\Controllers;

use App\Models\DisbursementBatchStudent;
use App\Models\Scholar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScholarReleaseController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $query = DisbursementBatchStudent::with([
            'disbursementBatch.scholarshipProgram',
            'scholar.user',
            'application'
        ]);

        // Apply filters
        if ($request->filled('release_status')) {
            $query->where('release_status', $request->release_status);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('scholar.user', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        $disbursements = $query->latest()->paginate(15);

        // Get statistics
        $stats = [
            'total_disbursements' => DisbursementBatchStudent::count(),
            'released_disbursements' => DisbursementBatchStudent::where('release_status', 'released')->count(),
            'unreleased_disbursements' => DisbursementBatchStudent::where('release_status', 'unreleased')->count(),
            'total_amount_released' => DisbursementBatchStudent::where('release_status', 'released')->sum('actual_amount'),
            'total_amount_pending' => DisbursementBatchStudent::where('release_status', 'unreleased')->sum('requested_amount'),
        ];

        return view('scholar-release.index', compact('disbursements', 'stats'));
    }

    public function show($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $disbursement = DisbursementBatchStudent::with([
            'disbursementBatch.scholarshipProgram',
            'scholar.user',
            'application'
        ])->findOrFail($id);

        return view('scholar-release.show', compact('disbursement'));
    }

    public function release(Request $request, $id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'actual_amount' => 'required|numeric|min:0',
            'release_remarks' => 'nullable|string|max:1000'
        ]);

        $disbursement = DisbursementBatchStudent::findOrFail($id);

        if ($disbursement->release_status === 'released') {
            return redirect()->back()->with('error', 'This disbursement has already been released.');
        }

        try {
            DB::beginTransaction();

            $disbursement->update([
                'release_status' => 'released',
                'actual_amount' => $request->actual_amount,
                'release_remarks' => $request->release_remarks,
                'released_at' => now(config('app.timezone')),
                'status' => 'disbursed'
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Disbursement released successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to release disbursement: ' . $e->getMessage());
        }
    }

    public function unrelease($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $disbursement = DisbursementBatchStudent::findOrFail($id);

        if ($disbursement->release_status === 'unreleased') {
            return redirect()->back()->with('error', 'This disbursement is already unreleased.');
        }

        try {
            DB::beginTransaction();

            $disbursement->update([
                'release_status' => 'unreleased',
                'actual_amount' => null,
                'release_remarks' => null,
                'released_at' => null,
                'status' => 'approved'
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Disbursement unreleased successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to unrelease disbursement: ' . $e->getMessage());
        }
    }

    public function bulkRelease(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'disbursement_ids' => 'required|array|min:1',
            'disbursement_ids.*' => 'exists:tbl_disbursement_batch_students,id',
            'actual_amounts' => 'required|array',
            'actual_amounts.*' => 'numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->disbursement_ids as $disbursementId) {
                $disbursement = DisbursementBatchStudent::findOrFail($disbursementId);
                
                if ($disbursement->release_status === 'unreleased') {
                    $actualAmount = $request->actual_amounts[$disbursementId] ?? $disbursement->requested_amount;
                    
                    $disbursement->update([
                        'release_status' => 'released',
                        'actual_amount' => $actualAmount,
                        'released_at' => now(config('app.timezone')),
                        'status' => 'disbursed'
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Selected disbursements released successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to release disbursements: ' . $e->getMessage());
        }
    }

    public function getUnreleased()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $disbursements = DisbursementBatchStudent::with([
            'disbursementBatch.scholarshipProgram',
            'scholar.user'
        ])
        ->where('release_status', 'unreleased')
        ->where('status', 'approved')
        ->get()
        ->map(function($disbursement) {
            return [
                'id' => $disbursement->id,
                'scholar_name' => ($disbursement->scholar->user->first_name ?? 'N/A') . ' ' . ($disbursement->scholar->user->last_name ?? 'N/A'),
                'program_name' => $disbursement->disbursementBatch->scholarshipProgram->name ?? 'N/A',
                'requested_amount' => $disbursement->requested_amount ?? 0
            ];
        });

        return response()->json($disbursements);
    }
}