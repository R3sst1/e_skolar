<?php

namespace App\Http\Controllers;

use App\Models\Etala\DemographicIdentifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $demographic = DemographicIdentifications::with([
            'geographicIdentification.barangay',
            'educationAndLiteracy.gradeYear',
            'educationAndLiteracy.currentGradeYear',
            'familyIncome',
            'placeOfBirth',
            'maritalStatus',
            'familyHeadRelationship',
            'nuclearFamilyRelationship'
        ])->findOrFail($id);

        return view('profiles.show', compact('demographic'));
    }

    public function showOther($user)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $user = \App\Models\User::findOrFail($user);
        return view('profiles.show-other', compact('user'));
    }

    public function search(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $query = $request->get('q');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $demographics = DemographicIdentifications::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('full_name', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'middle_name', 'full_name']);

        return response()->json($demographics);
    }
}