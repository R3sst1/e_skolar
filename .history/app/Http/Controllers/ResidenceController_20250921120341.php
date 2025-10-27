<?php

namespace App\Http\Controllers;

use App\Models\Etala\DemographicIdentifications;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResidenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
                abort(403, 'Access denied. Admin privileges required.');
            }
            return $next($request);
        });
    }

    /**
     * Display the residence search page
     */
    public function index(Request $request)
    {
        $q = $request->get('q');
        $results = collect();

        if ($q) {
            $results = DemographicIdentifications::with([
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

        return view('dashboardcontent.ResidenceData.residence-search', compact('results', 'q'));
    }

    /**
     * Display a specific demographic profile
     */
    public function show($id)
    {
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

    /**
     * Redirect to account creation with demographic data
     */
    public function createAccountRedirect($id)
    {
        $demographic = DemographicIdentifications::findOrFail($id);

        // Check if account already exists
        $existingUser = $this->findExistingUser($demographic);
        
        if ($existingUser) {
            return redirect()->route('residence.index')
                ->with('error', 'Account already exists for ' . $demographic->full_name . ' (Username: ' . $existingUser->username . ')');
        }

        // Redirect to account creation with demographic data
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
