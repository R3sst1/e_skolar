<div>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <!-- Header -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <h2 class="intro-y text-lg font-medium mr-auto">Disbursement Batches</h2>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <button wire:click="openCreateModal" class="btn btn-primary shadow-md mr-2">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Create Batch
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
            <div class="box p-5">
                <div class="flex items-center">
                    <div class="text-slate-500">Total Batches</div>
                    <div class="ml-auto">
                        <div class="text-base font-medium text-slate-600">{{ $batches->total() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
            <div class="box p-5">
                <div class="flex items-center">
                    <div class="text-slate-500">Pending Batches</div>
                    <div class="ml-auto">
                        <div class="text-base font-medium text-warning">{{ $batches->where('status', 'pending')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
            <div class="box p-5">
                <div class="flex items-center">
                    <div class="text-slate-500">Approved Batches</div>
                    <div class="ml-auto">
                        <div class="text-base font-medium text-success">{{ $batches->where('status', 'approved')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
            <div class="box p-5">
                <div class="flex items-center">
                    <div class="text-slate-500">Disbursed Batches</div>
                    <div class="ml-auto">
                        <div class="text-base font-medium text-primary">{{ $batches->where('status', 'disbursed')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <div class="max-w-7xl mx-auto px-4">
                <table class="table table-report -mt-2 w-full text-center">
                    <thead>
                        <tr>
                            <th>BATCH ID</th>
                            <th class="whitespace-nowrap">SCHOLARSHIP PROGRAM</th>
                            <th class="whitespace-nowrap">STATUS</th>
                            <th>BUDGET ALLOCATED</th>
                            <th>STUDENTS COUNT</th>
                            <th>CREATED AT</th>
                            <th class="text-center whitespace-nowrap">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($batches as $batch)
                        <tr class="intro-x">
                            <td class="align-top">
                                <span class="font-medium text-base">{{ $batch->reference_number }}</span>
                            </td>
                            <td class="align-top text-center">
                                <span class="font-medium">{{ $batch->scholarshipProgram->name ?? 'N/A' }}</span>
                            </td>
                            <td class="align-top text-center">
                                <div class="flex items-center justify-center">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        {{ $batch->status === 'pending' ? 'bg-warning text-white' : 
                                           ($batch->status === 'approved' ? 'bg-success text-white' : 
                                           ($batch->status === 'rejected' ? 'bg-danger text-white' : 'bg-primary text-white')) }}">
                                        {{ ucfirst($batch->status) }}
                                    </span>
                                </div>
                            </td>
                            <td class="align-top text-center">
                                <span class="font-medium">₱{{ number_format($batch->budget_allocated ?? 0, 2) }}</span>
                            </td>
                            <td class="align-top text-center">
                                <span class="font-medium">{{ $batch->disbursementBatchStudents->count() }}</span>
                            </td>
                            <td class="align-top text-center">
                                <span class="text-slate-600">{{ $batch->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="table-report__action w-56 align-top text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <button wire:click="openViewModal({{ $batch->id }})" class="btn btn-primary btn-sm">
                                        <i data-lucide="eye" class="w-4 h-4 mr-1"></i> View
                                    </button>
                                    @if($batch->status === 'pending')
                                    <button wire:click="approveBatch({{ $batch->id }})" 
                                            wire:confirm="Are you sure you want to approve this batch?"
                                            class="btn btn-success btn-sm">
                                        <i data-lucide="check" class="w-4 h-4 mr-1"></i> Approve
                                    </button>
                                    <button wire:click="rejectBatch({{ $batch->id }})" 
                                            wire:confirm="Are you sure you want to reject this batch?"
                                            class="btn btn-danger btn-sm">
                                        <i data-lucide="x" class="w-4 h-4 mr-1"></i> Reject
                                    </button>
                                    @endif
                                    @if($batch->status === 'approved')
                                    <button wire:click="disburseBatch({{ $batch->id }})" 
                                            wire:confirm="Are you sure you want to disburse this batch?"
                                            class="btn btn-warning btn-sm">
                                        <i data-lucide="send" class="w-4 h-4 mr-1"></i> Disburse
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-slate-500">No disbursement batches found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            {{ $batches->links() }}
        </div>
    </div>

    <!-- Create Batch Modal -->
    @if($showCreateModal)
    <div class="modal show" style="display: block;" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Create Disbursement Batch</h2>
                    <button wire:click="closeCreateModal" class="btn btn-outline-secondary hidden sm:flex">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Step 1: Select Scholarship Program -->
                    <div class="mb-6">
                        <label class="form-label">Select Scholarship Program *</label>
                        <select wire:model.live="scholarship_program_id" class="form-select">
                            <option value="">Choose a scholarship program...</option>
                            @foreach($scholarshipPrograms as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                            @endforeach
                        </select>
                        @error('scholarship_program_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    @if($scholarship_program_id)
                    <!-- Step 2: Search and Select Scholars -->
                    <div class="mb-4">
                        <label class="form-label">Search Scholars</label>
                        <input type="text" wire:model.live.debounce.300ms="searchTerm" class="form-control" placeholder="Search by name...">
                    </div>

                    <!-- Select All Controls -->
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-4">
                            <button wire:click="selectAllScholars" class="btn btn-outline-primary btn-sm">
                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Select All
                            </button>
                            <button wire:click="deselectAllScholars" class="btn btn-outline-secondary btn-sm">
                                <i data-lucide="square" class="w-4 h-4 mr-1"></i> Deselect All
                            </button>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-slate-600">
                                <span class="font-medium">{{ $this->selectedCount }}</span> selected
                            </div>
                            <div class="text-lg font-medium text-primary">
                                Total Amount: ₱<span class="font-bold">{{ number_format($this->totalAmount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Scholars List -->
                    <div class="border rounded-lg p-4 max-h-96 overflow-y-auto">
                        @forelse($scholars as $scholar)
                        <div class="flex items-center justify-between p-3 border-b last:border-b-0 hover:bg-slate-50">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       wire:click="toggleScholar({{ $scholar->id }})"
                                       @if(in_array($scholar->id, $selectedScholars)) checked @endif
                                       class="form-check-input mr-3">
                                <div>
                                    <div class="font-medium">{{ $scholar->user->first_name }} {{ $scholar->user->last_name }}</div>
                                    <div class="text-sm text-slate-600">
                                        {{ $scholar->course ?? 'N/A' }} • 
                                        Year {{ $scholar->year_level ?? 'N/A' }} • 
                                        {{ $scholar->institution ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-medium text-primary">
                                    ₱{{ number_format($scholarshipPrograms->where('id', $scholarship_program_id)->first()->per_scholar_amount ?? 0, 2) }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-slate-500">
                            @if($searchTerm)
                                No scholars found matching "{{ $searchTerm }}"
                            @else
                                No scholars available for this scholarship program
                            @endif
                        </div>
                        @endforelse
                    </div>

                    <!-- Budget Allocation -->
                    <div class="mt-6">
                        <label class="form-label">Budget Allocated (Optional)</label>
                        <input type="number" wire:model="budget_allocated" class="form-control" placeholder="Enter budget allocated amount...">
                        @error('budget_allocated') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Remarks -->
                    <div class="mt-4">
                        <label class="form-label">Remarks (Optional)</label>
                        <textarea wire:model="remarks" class="form-control" rows="3" placeholder="Add any remarks for this disbursement batch..."></textarea>
                        @error('remarks') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button wire:click="closeCreateModal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                    @if($scholarship_program_id)
                    <button wire:click="createBatch" 
                            @if(empty($selectedScholars)) disabled @endif
                            class="btn btn-primary w-20">
                        Create
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop show"></div>
    @endif

    <!-- View Batch Modal -->
    @if($showViewModal && $selectedBatch)
    <div class="modal show" style="display: block;" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Batch Details - {{ $selectedBatch->reference_number }}</h2>
                    <button wire:click="closeViewModal" class="btn btn-outline-secondary hidden sm:flex">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Batch Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="box p-4">
                            <h3 class="font-medium mb-2">Batch Information</h3>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Reference:</span> {{ $selectedBatch->reference_number }}</div>
                                <div><span class="font-medium">Program:</span> {{ $selectedBatch->scholarshipProgram->name ?? 'N/A' }}</div>
                                <div><span class="font-medium">Status:</span> 
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        {{ $selectedBatch->status === 'pending' ? 'bg-warning text-white' : 
                                           ($selectedBatch->status === 'approved' ? 'bg-success text-white' : 
                                           ($selectedBatch->status === 'rejected' ? 'bg-danger text-white' : 'bg-primary text-white')) }}">
                                        {{ ucfirst($selectedBatch->status) }}
                                    </span>
                                </div>
                                <div><span class="font-medium">Created:</span> {{ $selectedBatch->created_at->format('M d, Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="box p-4">
                            <h3 class="font-medium mb-2">Financial Summary</h3>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Total Amount:</span> ₱{{ number_format($selectedBatch->total_amount ?? 0, 2) }}</div>
                                <div><span class="font-medium">Budget Allocated:</span> ₱{{ number_format($selectedBatch->budget_allocated ?? 0, 2) }}</div>
                                <div><span class="font-medium">Students Count:</span> {{ $selectedBatch->disbursementBatchStudents->count() }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Students List -->
                    <div class="box p-4">
                        <h3 class="font-medium mb-4">Students in Batch</h3>
                        <div class="overflow-x-auto">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Course</th>
                                        <th>Year Level</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($batchStudents as $batchStudent)
                                    <tr>
                                        <td>{{ $batchStudent->scholar->user->first_name }} {{ $batchStudent->scholar->user->last_name }}</td>
                                        <td>{{ $batchStudent->scholar->course ?? 'N/A' }}</td>
                                        <td>{{ $batchStudent->scholar->year_level ?? 'N/A' }}</td>
                                        <td>₱{{ number_format($batchStudent->requested_amount, 2) }}</td>
                                        <td>
                                            <span class="px-2 py-1 rounded-full text-xs 
                                                {{ $batchStudent->status === 'pending' ? 'bg-warning text-white' : 
                                                   ($batchStudent->status === 'approved' ? 'bg-success text-white' : 'bg-danger text-white') }}">
                                                {{ ucfirst($batchStudent->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($selectedBatch->remarks)
                    <div class="box p-4">
                        <h3 class="font-medium mb-2">Remarks</h3>
                        <p class="text-sm text-slate-600">{{ $selectedBatch->remarks }}</p>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button wire:click="closeViewModal" class="btn btn-outline-secondary w-20">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop show"></div>
    @endif
</div>
