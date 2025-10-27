<?php

namespace App\Http\Controllers;

use App\Models\Scholar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScholarController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $query = Scholar::with('user')
            ->when($request->category, function ($q) use ($request) {
                return $q->where('category', $request->category);
            })
            ->when($request->institution, function ($q) use ($request) {
                return $q->where('institution', $request->institution);
            })
            ->when($request->barangay, function ($q) use ($request) {
                return $q->where('barangay', $request->barangay);
            })
            ->when($request->status, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->when($request->search, function ($q) use ($request) {
                return $q->whereHas('user', function ($query) use ($request) {
                    $query->where('first_name', 'like', '%' . $request->search . '%')
                        ->orWhere('last_name', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%');
                });
            });

        // Get unique values for filters
        $institutions = \App\Models\Institution::active()->orderBy('name')->pluck('name');
        $barangays = Scholar::distinct()->pluck('barangay');

        // Calculate statistics
        $stats = [
            'total_active' => Scholar::where('status', 'active')->count(),
            'total_graduated' => Scholar::where('status', 'graduated')->count(),
            'total_institutions' => \App\Models\Institution::active()->count(),
            'total_barangays' => Scholar::distinct('barangay')->count('barangay'),
        ];

        $scholars = $query->latest()->paginate(10);

        return view('dashboardcontent.scholars', compact(
            'scholars',
            'stats',
            'institutions',
            'barangays'
        ));
    }

    public function drop(Scholar $scholar)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }
        $scholar->status = 'discontinued';
        $scholar->end_date = now();
        $scholar->save();
        return redirect()->back()->with('success', 'Scholar has been dropped successfully.');
    }
} 