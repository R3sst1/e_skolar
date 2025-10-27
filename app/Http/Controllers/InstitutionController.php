<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        $query = Institution::query();

        // Handle search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        // Handle type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Handle status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $institutions = $query->orderBy('name')->paginate(10);

        if ($request->ajax()) {
            return view('dashboardcontent.institutions._institutions_list', compact('institutions'))->render();
        }

        return view('dashboardcontent.institutions.index', compact('institutions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        return view('dashboardcontent.institutions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:institutions,name',
            'type' => 'required|in:university,college,school',
            'address' => 'nullable|string|max:500',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            Institution::create([
                'name' => $request->name,
                'type' => $request->type,
                'address' => $request->address,
                'contact_person' => $request->contact_person,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'description' => $request->description,
                'is_active' => $request->has('is_active')
            ]);

            return redirect()->route('institutions.index')->with('success', 'Institution created successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create institution: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Institution $institution)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        $scholars = $institution->scholars()->with('user')->paginate(10);

        return view('dashboardcontent.institutions.show', compact('institution', 'scholars'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Institution $institution)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        // Return JSON for AJAX requests (modal)
        if (request()->ajax()) {
            \Log::info('Institution edit AJAX request', [
                'institution_id' => $institution->id,
                'institution_name' => $institution->name
            ]);
            
            // Return only the necessary fields to avoid serialization issues
            $data = [
                'id' => $institution->id,
                'name' => $institution->name,
                'type' => $institution->type,
                'address' => $institution->address,
                'contact_person' => $institution->contact_person,
                'contact_email' => $institution->contact_email,
                'contact_phone' => $institution->contact_phone,
                'description' => $institution->description,
                'is_active' => $institution->is_active
            ];
            
            \Log::info('Institution data being returned', $data);
            return response()->json($data);
        }

        return view('dashboardcontent.institutions.edit', compact('institution'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Institution $institution)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        \Log::info('Institution update request', [
            'institution_id' => $institution->id,
            'request_data' => $request->all()
        ]);

        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:institutions,name,' . $institution->id,
                'type' => 'required|in:university,college,school',
                'address' => 'nullable|string|max:500',
                'contact_person' => 'nullable|string|max:255',
                'contact_email' => 'nullable|email|max:255',
                'contact_phone' => 'nullable|string|max:20',
                'description' => 'nullable|string|max:1000',
            ]);

            $updateData = [
                'name' => $request->name,
                'type' => $request->type,
                'address' => $request->address,
                'contact_person' => $request->contact_person,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'description' => $request->description,
                'is_active' => $request->has('is_active')
            ];

            \Log::info('Updating institution with data', $updateData);

            $institution->update($updateData);

            \Log::info('Institution updated successfully');

            return redirect()->route('institutions.index')->with('success', 'Institution updated successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', [
                'errors' => $e->errors()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Failed to update institution', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to update institution: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Institution $institution)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        try {
            // Check if institution has scholars
            if ($institution->scholars()->count() > 0) {
                return redirect()->back()->with('error', 'Cannot delete institution with existing scholars');
            }

            $institution->delete();

            return redirect()->route('institutions.index')->with('success', 'Institution deleted successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete institution: ' . $e->getMessage());
        }
    }

    /**
     * Get institutions for dropdown
     */
    public function getInstitutions()
    {
        $institutions = Institution::active()->orderBy('name')->get(['id', 'name']);
        return response()->json($institutions);
    }
}
