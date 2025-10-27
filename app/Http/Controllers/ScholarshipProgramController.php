<?php
namespace App\Http\Controllers;

use App\Models\ScholarshipProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScholarshipProgramController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        // Check and update scholarship statuses automatically
        $this->checkAndUpdateScholarshipStatuses();
        
        $scholarships = ScholarshipProgram::latest()->get();
        return view('dashboardcontent.scholarship-management', compact('scholarships'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'deadline' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
            'type' => 'required|in:budgeted,unbudgeted',
            'allocated_budget' => 'nullable|numeric|min:0',
            'per_scholar_amount' => 'nullable|numeric|min:0',
            'auto_close' => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'description', 'status', 'deadline', 'type', 'allocated_budget', 'per_scholar_amount']);
        $data['auto_close'] = $request->has('auto_close');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('scholarships', 'public');
        }
        ScholarshipProgram::create($data);
        return redirect()->route('scholarship.management')->with('success', 'Scholarship added successfully.');
    }

    public function edit($id)
    {
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        $scholarship = ScholarshipProgram::findOrFail($id);
        return view('dashboardcontent.scholarship-edit', compact('scholarship'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        $scholarship = ScholarshipProgram::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'deadline' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
            'type' => 'required|in:budgeted,unbudgeted',
            'allocated_budget' => 'nullable|numeric|min:0',
            'per_scholar_amount' => 'nullable|numeric|min:0',
            'auto_close' => 'nullable|boolean',
        ]);
        $data = $request->only(['name', 'description', 'status', 'deadline', 'type', 'allocated_budget', 'per_scholar_amount']);
        $data['auto_close'] = $request->has('auto_close');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('scholarships', 'public');
        }
        $scholarship->update($data);
        return redirect()->route('scholarship.management')->with('success', 'Scholarship updated successfully.');
    }

    public function destroy($id)
    {
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        $scholarship = ScholarshipProgram::findOrFail($id);
        $scholarship->delete();
        return redirect()->route('scholarship.management')->with('success', 'Scholarship deleted successfully.');
    }

    /**
     * Check and update scholarship program statuses automatically
     */
    private function checkAndUpdateScholarshipStatuses()
    {
        $activeScholarships = ScholarshipProgram::where('status', 'active')->get();
        
        foreach ($activeScholarships as $scholarship) {
            $shouldClose = false;
            $reason = '';
            
            // Check deadline
            if ($scholarship->deadline && now()->greaterThan($scholarship->deadline)) {
                $shouldClose = true;
                $reason = 'Deadline reached';
            }
            
            // Check budget limit for budgeted programs
            if ($scholarship->type === 'budgeted' && $scholarship->auto_close && $scholarship->allocated_budget) {
                $totalDisbursed = \App\Models\Disbursement::whereHas('scholar', function($query) use ($scholarship) {
                    $query->where('institution', $scholarship->name); // Assuming scholarship name matches institution
                })->sum('amount');
                
                if ($totalDisbursed >= $scholarship->allocated_budget) {
                    $shouldClose = true;
                    $reason = 'Budget limit reached';
                }
            }
            
            // Update status if needed
            if ($shouldClose) {
                $scholarship->update(['status' => 'inactive']);
                \Log::info("Scholarship program '{$scholarship->name}' automatically closed: {$reason}");
            }
        }
    }
} 