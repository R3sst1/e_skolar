<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangayController extends Controller
{
    public function index()
    {
        $barangays = Barangay::orderBy('name')->get();
        return view('dashboardcontent.barangay-data', compact('barangays'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:barangays,name',
        ]);
        Barangay::create([
            'name' => $request->name,
            'funded' => $request->has('funded'),
        ]);
        return redirect()->back()->with('success', 'Barangay added successfully.');
    }

    public function update(Request $request, Barangay $barangay)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:barangays,name,' . $barangay->id,
        ]);
        $barangay->update([
            'name' => $request->name,
            'funded' => $request->has('funded'),
        ]);
        return redirect()->back()->with('success', 'Barangay updated successfully.');
    }

    public function destroy(Barangay $barangay)
    {
        $barangay->delete();
        return redirect()->back()->with('success', 'Barangay deleted successfully.');
    }

    public function toggleFunded(Barangay $barangay)
    {
        $barangay->funded = !$barangay->funded;
        $barangay->save();
        return redirect()->back()->with('success', 'Barangay funding status updated.');
    }
} 