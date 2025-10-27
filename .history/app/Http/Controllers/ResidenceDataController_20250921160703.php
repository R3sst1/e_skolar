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
        
        $demographics = DemographicIdentifications::with([
            'geographicIdentification.barangay',
            'educationAndLiteracy.gradeYear',
            'educationAndLiteracy.currentGradeYear',
            'familyIncome',
            'placeOfBirth',
            'maritalStatus',
            'familyHeadRelationship',
            'nuclearFamilyRelationship',
            'sexData'
        ])->whereHas('geographicIdentification');

        if ($q) {
            $demographics = $demographics->where(function ($query) use ($q) {
                $query->where('full_name', 'like', '%' . $q . '%')
                      ->orWhere('first_name', 'like', '%' . $q . '%')
                      ->orWhere('last_name', 'like', '%' . $q . '%')
                      ->orWhere('registry_number', 'like', '%' . $q . '%')
                      ->orWhereHas('geographicIdentification', function ($geoQuery) use ($q) {
                          $geoQuery->where('email_address', 'like', '%' . $q . '%');
                      });
            });
        }

        $demographics = $demographics->orderBy('full_name')->paginate(20);

        // Debug: Log some sample data to help identify issues
        if ($demographics->count() > 0) {
            $sample = $demographics->first();
            \Log::info('Sample demographic data:', [
                'id' => $sample->id,
                'full_name' => $sample->full_name,
                'sex_field' => $sample->sex ?? 'null',
                'has_sex_relation' => $sample->sex ? 'yes' : 'no',
                'sex_name' => $sample->sex->name ?? 'null',
                'has_geo' => $sample->geographicIdentification ? 'yes' : 'no',
                'barangay_id' => $sample->geographicIdentification->barangay_id ?? 'null',
                'barangay_name' => $sample->geographicIdentification->barangay->name ?? 'null',
                'subdivision' => $sample->geographicIdentification->subdivision_village_name ?? 'null',
                'sitio_purok_id' => $sample->geographicIdentification->sitio_purok_id ?? 'null'
            ]);
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
            'nuclearFamilyRelationship',
            'sexData'
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
