<?php

namespace App\Http\Controllers;

use App\Models\ResidenceData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResidenceDataController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $residenceData = ResidenceData::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dashboardcontent.ResidenceData.residence-data', compact('residenceData'));
    }

    public function show($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $residenceData = ResidenceData::with('user')->findOrFail($id);
        return view('dashboardcontent.ResidenceData.show', compact('residenceData'));
    }

    public function createAccount($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $residenceData = ResidenceData::findOrFail($id);
        
        // Check if account already exists
        if ($residenceData->account_created) {
            return redirect()->route('residence-data.index')
                ->with('error', 'Account for this applicant has already been created.');
        }

        return redirect()->route('create-account', $id);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'barangay' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:1|max:120',
        ]);

        ResidenceData::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'barangay' => $request->barangay,
            'age' => $request->age,
            'account_created' => false,
        ]);

        return redirect()->route('residence-data.index')
            ->with('success', 'Applicant data added successfully.');
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'barangay' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:1|max:120',
        ]);

        $residenceData = ResidenceData::findOrFail($id);
        $residenceData->update($request->all());

        return redirect()->route('residence-data.index')
            ->with('success', 'Applicant data updated successfully.');
    }

    public function destroy($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $residenceData = ResidenceData::findOrFail($id);
        
        // Check if account has been created
        if ($residenceData->account_created && $residenceData->user) {
            return redirect()->route('residence-data.index')
                ->with('error', 'Cannot delete applicant with existing account. Delete the user account first.');
        }

        $residenceData->delete();

        return redirect()->route('residence-data.index')
            ->with('success', 'Applicant data deleted successfully.');
    }
}
