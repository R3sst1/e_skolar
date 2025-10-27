<?php

namespace App\Livewire;

use App\Models\DisbursementBatch;
use App\Models\ScholarshipProgram;
use App\Models\Scholar;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class DisbursementBatches extends Component
{
    use WithPagination;

    // Modal states
    public $showCreateModal = false;
    public $showViewModal = false;
    public $selectedBatch = null;

    // Create batch form
    public $scholarship_program_id = '';
    public $selectedScholars = [];
    public $budget_allocated = '';
    public $remarks = '';
    public $searchTerm = '';

    // View batch data
    public $batchStudents = [];

    protected $rules = [
        'scholarship_program_id' => 'required|exists:tbl_scholarship_programs,id',
        'selectedScholars' => 'required|array|min:1',
        'budget_allocated' => 'nullable|numeric|min:0',
        'remarks' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'scholarship_program_id.required' => 'Please select a scholarship program.',
        'selectedScholars.required' => 'Please select at least one scholar.',
        'selectedScholars.min' => 'Please select at least one scholar.',
        'budget_allocated.numeric' => 'Budget allocated must be a valid number.',
        'budget_allocated.min' => 'Budget allocated cannot be negative.',
    ];

    public function mount()
    {
        // Initialize component
    }

    public function render()
    {
        $batches = DisbursementBatch::with(['scholarshipProgram', 'disbursementBatchStudents.scholar.user'])
            ->latest()
            ->paginate(10);

        $scholarshipPrograms = ScholarshipProgram::where('status', 'active')
            ->orderBy('name')
            ->get();

        $scholars = collect();
        if ($this->scholarship_program_id) {
            $scholars = Scholar::with(['user', 'applications.scholarship'])
                ->where('status', 'active')
                ->whereHas('applications', function($query) {
                    $query->where('scholarship_id', $this->scholarship_program_id)
                          ->where('status', 'approved');
                })
                ->when($this->searchTerm, function($query) {
                    $query->whereHas('user', function($q) {
                        $q->where('first_name', 'like', '%' . $this->searchTerm . '%')
                          ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%');
                    });
                })
                ->get();
        }

        return view('disbursements.disbursement-batches', [
            'batches' => $batches,
            'scholarshipPrograms' => $scholarshipPrograms,
            'scholars' => $scholars,
        ]);
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openViewModal($batchId)
    {
        $this->selectedBatch = DisbursementBatch::with([
            'scholarshipProgram',
            'disbursementBatchStudents.scholar.user',
            'disbursementBatchStudents.application'
        ])->findOrFail($batchId);

        $this->batchStudents = $this->selectedBatch->disbursementBatchStudents;
        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->selectedBatch = null;
        $this->batchStudents = [];
    }

    public function updatedScholarshipProgramId()
    {
        $this->selectedScholars = [];
        $this->searchTerm = '';
    }

    public function toggleScholar($scholarId)
    {
        if (in_array($scholarId, $this->selectedScholars)) {
            $this->selectedScholars = array_diff($this->selectedScholars, [$scholarId]);
        } else {
            $this->selectedScholars[] = $scholarId;
        }
    }

    public function selectAllScholars()
    {
        if ($this->scholarship_program_id) {
            $scholarIds = Scholar::where('status', 'active')
                ->whereHas('applications', function($query) {
                    $query->where('scholarship_id', $this->scholarship_program_id)
                          ->where('status', 'approved');
                })
                ->when($this->searchTerm, function($query) {
                    $query->whereHas('user', function($q) {
                        $q->where('first_name', 'like', '%' . $this->searchTerm . '%')
                          ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%');
                    });
                })
                ->pluck('id')
                ->toArray();

            $this->selectedScholars = $scholarIds;
        }
    }

    public function deselectAllScholars()
    {
        $this->selectedScholars = [];
    }

    public function createBatch()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Get scholarship program details
            $scholarshipProgram = ScholarshipProgram::findOrFail($this->scholarship_program_id);

            // Calculate total amount
            $totalAmount = 0;
            $scholars = Scholar::whereIn('id', $this->selectedScholars)->get();
            
            foreach ($scholars as $scholar) {
                $totalAmount += $scholarshipProgram->per_scholar_amount ?? 0;
            }

            // Create disbursement batch
            $batch = DisbursementBatch::create([
                'scholarship_program_id' => $this->scholarship_program_id,
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'budget_allocated' => $this->budget_allocated,
                'remarks' => $this->remarks,
            ]);

            // Create batch students
            foreach ($this->selectedScholars as $scholarId) {
                $scholar = Scholar::findOrFail($scholarId);
                $application = $scholar->user->applications()
                    ->where('scholarship_id', $this->scholarship_program_id)
                    ->where('status', 'approved')
                    ->first();

                $batch->disbursementBatchStudents()->create([
                    'student_id' => $scholarId,
                    'application_id' => $application->id ?? null,
                    'status' => 'pending',
                    'requested_amount' => $scholarshipProgram->per_scholar_amount ?? 0,
                ]);
            }

            DB::commit();

            session()->flash('success', 'Disbursement batch created successfully.');
            $this->closeCreateModal();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to create disbursement batch: ' . $e->getMessage());
        }
    }

    public function approveBatch($batchId)
    {
        try {
            $batch = DisbursementBatch::findOrFail($batchId);
            $batch->update(['status' => 'approved']);
            
            session()->flash('success', 'Batch approved successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to approve batch: ' . $e->getMessage());
        }
    }

    public function rejectBatch($batchId)
    {
        try {
            $batch = DisbursementBatch::findOrFail($batchId);
            $batch->update(['status' => 'rejected']);
            
            session()->flash('success', 'Batch rejected successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to reject batch: ' . $e->getMessage());
        }
    }

    public function disburseBatch($batchId)
    {
        try {
            DB::beginTransaction();

            $batch = DisbursementBatch::with('disbursementBatchStudents')->findOrFail($batchId);
            
            // Update batch status
            $batch->update(['status' => 'disbursed']);

            // Update all approved students to disbursed
            $batch->disbursementBatchStudents()
                ->where('status', 'approved')
                ->update(['status' => 'disbursed']);

            DB::commit();

            session()->flash('success', 'Batch disbursed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to disburse batch: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->scholarship_program_id = '';
        $this->selectedScholars = [];
        $this->budget_allocated = '';
        $this->remarks = '';
        $this->searchTerm = '';
        $this->resetErrorBag();
    }

    public function getTotalAmountProperty()
    {
        if (!$this->scholarship_program_id || empty($this->selectedScholars)) {
            return 0;
        }

        $scholarshipProgram = ScholarshipProgram::find($this->scholarship_program_id);
        $perScholarAmount = $scholarshipProgram->per_scholar_amount ?? 0;
        
        return count($this->selectedScholars) * $perScholarAmount;
    }

    public function getSelectedCountProperty()
    {
        return count($this->selectedScholars);
    }
}
