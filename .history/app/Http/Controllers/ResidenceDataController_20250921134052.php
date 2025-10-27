<?php

namespace App\Http\Controllers;

use App\Models\Etala\DemographicIdentifications;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResidenceDataController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $q = $request->get('q');
        $demographics = collect();

        if ($q) {
            $demographics = DemographicIdentifications::with([
                'geographicIdentification.barangay',
                'educationAndLiteracy.gradeYear',
                'educationAndLiteracy.currentGradeYear',
                'familyIncome',
                'placeOfBirth',
                'maritalStatus',
                'familyHeadRelationship',
                'nuclearFamilyRelationship'
            ])
            ->where(function ($query) use ($q) {
                $query->where('full_name', 'like', '%' . $q . '%')
                      ->orWhere('first_name', 'like', '%' . $q . '%')
                      ->orWhere('last_name', 'like', '%' . $q . '%')
                      ->orWhere('registry_number', 'like', '%' . $q . '%')
                      ->orWhereHas('geographicIdentification', function ($geoQuery) use ($q) {
                          $geoQuery->where('email_address', 'like', '%' . $q . '%');
                      });
            })
            ->paginate(20);
        }

        return view('dashboardcontent.ResidenceData.residence-data', compact('demographics', 'q'));
    }

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

        return view('dashboardcontent.ResidenceData.residence-profile', compact('demographic'));
    }

    public function createAccount($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $demographic = DemographicIdentifications::findOrFail($id);

        // Check if account already exists
        $existingUser = $this->findExistingUser($demographic);
        
        if ($existingUser) {
            return redirect()->route('residence-data.index')
                ->with('error', 'Account already exists for ' . $demographic->full_name . ' (Username: ' . $existingUser->username . ')');
        }

        return redirect()->route('create-account-demographic', ['demographicId' => $id])
            ->with('info', 'Creating account for ' . $demographic->full_name);
    }

    /**
     * Find existing user by demographic data
     */
    private function findExistingUser($demographic)
    {
        // Check by email if available
        if ($demographic->geographicIdentification && $demographic->geographicIdentification->email_address) {
            $user = User::where('email', $demographic->geographicIdentification->email_address)->first();
            if ($user) {
                return $user;
            }
        }

        // Check by full name match (case insensitive)
        $fullName = strtolower(trim($demographic->full_name));
        $user = User::whereRaw('LOWER(CONCAT(first_name, " ", last_name)) = ?', [$fullName])->first();
        
        if ($user) {
            return $user;
        }

        // Check by first and last name separately
        $user = User::where('first_name', $demographic->first_name)
                   ->where('last_name', $demographic->last_name)
                   ->first();
        
        return $user;
    }

}
