@extends('layouts.app')
@section('title', ' ')
@section('content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <h2 class="intro-y text-lg font-medium mr-auto">Disbursement Management</h2>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <a href="{{ route('disbursements.allocation-logs') }}" class="btn btn-outline-secondary shadow-md mr-2" target="_self">
                <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> View Logs
            </a>
            <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal" data-tw-target="#create-disbursement-modal">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Create Disbursement
            </button>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Total Batches</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-slate-600">{{ $stats['total_batches'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Pending Batches</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-warning">{{ $stats['pending_batches'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Reviewed Batches</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-primary">{{ $stats['reviewed_batches'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Disbursed Batches</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-success">{{ $stats['disbursed_batches'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Budget Summary Section -->
    <div class="col-span-12">
        <div class="box p-5">
            <h3 class="text-lg font-medium mb-4">Allocated Budget Summary</h3>
            <div class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1 text-center">
                    <div class="text-2xl lg:text-3xl font-bold text-primary mb-1">₱{{ number_format($totalAllocated ?? 0, 2) }}</div>
                    <div class="text-slate-600 font-medium">Total Allocated Budget</div>
                    <div class="text-sm text-slate-500">From E-Kalinga System</div>
                </div>
                <div class="flex-1 text-center">
                    <div class="text-2xl lg:text-3xl font-bold text-warning mb-1">₱{{ number_format($totalDisbursed ?? 0, 2) }}</div>
                    <div class="text-slate-600 font-medium">Total Disbursed</div>
                    <div class="text-sm text-slate-500">Amount Released to Scholars</div>
                </div>
                <div class="flex-1 text-center">
                    <div class="text-2xl lg:text-3xl font-bold {{ ($remainingBalance ?? 0) > 0 ? 'text-success' : 'text-danger' }} mb-1">
                        ₱{{ number_format($remainingBalance ?? 0, 2) }}
                    </div>
                    <div class="text-slate-600 font-medium">Remaining Balance</div>
                    <div class="text-sm text-slate-500">Available for Disbursement</div>
                </div>
            </div>
            @if(($remainingBalance ?? 0) <= 0)
                <div class="mt-4 p-3 bg-danger/10 border border-danger/20 rounded-lg">
                    <div class="flex items-center">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-danger mr-2"></i>
                        <span class="text-danger font-medium">No remaining budget available. Please submit a budget request to continue disbursements.</span>
                    </div>
                </div>
            @endif
            
            @if(($totalAllocated ?? 0) == 0)
                <div class="mt-4 p-4 bg-warning/10 border border-warning/20 rounded-lg">
                    <div class="flex items-center">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-warning mr-3"></i>
                        <div>
                            <h4 class="font-medium text-warning mb-1">No Budget Allocated</h4>
                            <p class="text-sm text-slate-600">Budget allocation must be done through the E-Kalinga system. Please contact the E-Kalinga administrator to allocate budget for the Scholarship Office.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Scholarship Budget Information -->
    @if(isset($scholarshipBudgets) && $scholarshipBudgets->count() > 0)
    <div class="col-span-12">
        <div class="box p-5">
            <h3 class="text-lg font-medium mb-4">Scholarship Program Budgets</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($scholarshipBudgets as $budget)
                <div class="border rounded-lg p-4">
                    <h4 class="font-medium text-slate-800 mb-2">{{ $budget['name'] }}</h4>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Allocated:</span>
                            <span class="font-medium">₱{{ number_format($budget['allocated_budget'], 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Disbursed:</span>
                            <span class="font-medium text-warning">₱{{ number_format($budget['disbursed_amount'], 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Remaining:</span>
                            <span class="font-medium {{ $budget['remaining_budget'] > 0 ? 'text-success' : 'text-danger' }}">
                                ₱{{ number_format($budget['remaining_budget'], 2) }}
                            </span>
                        </div>
                        <div class="mt-2">
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-primary h-2 rounded-full" style="width: {{ $budget['allocated_budget'] > 0 ? ($budget['disbursed_amount'] / $budget['allocated_budget']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <div class="max-w-7xl mx-auto px-4">
            <table class="table table-report -mt-2 w-full text-center">
                <thead>
                    <tr>
                        <th class="w-40 !py-4">REFERENCE</th>
                        <th class="w-40">PROGRAM / META</th>
                        <th class="text-center">STATUS</th>
                        <th>DATE</th>
                        <th class="w-40 text-right">TOTAL</th>
                        <th class="text-center whitespace-nowrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disbursementBatches ?? [] as $batch)
                    @php
                        $status = $batch->status;
                        $statusIcon = $status === 'disbursed' ? 'check-square' : ($status === 'reviewed' ? 'check-square' : 'clock');
                        $statusClass = $status === 'disbursed' ? 'text-success' : ($status === 'reviewed' ? 'text-primary' : 'text-warning');
                    @endphp
                    <tr class="intro-x">
                        <td class="w-40 !py-4">
                            <a href="{{ route('disbursements.show', $batch->id) }}" class="underline decoration-dotted whitespace-nowrap">#{{ $batch->reference_number }}</a>
                        </td>
                        <td class="w-40">
                            <a href="{{ route('disbursements.show', $batch->id) }}" class="font-medium whitespace-nowrap">{{ optional($batch->scholarshipProgram)->name ?? 'Scholarship Program' }}</a>
                            <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ $batch->disbursementBatchStudents->count() }} students</div>
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center whitespace-nowrap {{ $statusClass }}">
                                <i data-lucide="{{ $statusIcon }}" class="w-4 h-4 mr-2"></i> {{ ucfirst($status) }}
                            </div>
                        </td>
                        <td>
                            <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ $batch->created_at->format('d M, H:i') }}</div>
                        </td>
                        <td class="w-40 text-right">
                            <div>₱{{ number_format($batch->total_amount ?? 0, 2) }}</div>
                        </td>
                        <td class="table-report__action">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center text-primary whitespace-nowrap" href="{{ route('disbursements.show', $batch->id) }}">
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> View Details
                                </a>
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
    @if(isset($disbursementBatches) && $disbursementBatches->hasPages())
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        {{ $disbursementBatches->links() }}
    </div>
    @endif
</div>

<!-- Create Disbursement Batch Modal -->
<div id="create-disbursement-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Create Disbursement Batch</h2>
                <button data-tw-dismiss="modal" class="btn btn-outline-secondary hidden sm:flex">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <form id="create-batch-form" method="POST" action="{{ route('disbursements.batches.store') }}">
                @csrf
                <div class="modal-body">
                    <!-- Step 1: Select Scholarship Program -->
                    <div class="mb-6">
                        <label class="form-label">Select Scholarship Program *</label>
                        <select id="scholarship-program-select" name="scholarship_program_id" class="form-select" required>
                            <option value="">Choose a scholarship program...</option>
                            @foreach($scholarshipPrograms ?? [] as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Step 2: Search and Select Scholars -->
                    <div id="scholars-section" style="display: none;">
                        <!-- Search Input -->
                        <div class="mb-4">
                            <label class="form-label">Search Scholars</label>
                            <input type="text" id="search-scholars" class="form-control" placeholder="Search by name...">
                        </div>

                        <!-- Select All and Summary -->
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="select-all" class="form-check-input mr-2">
                                <label for="select-all" class="form-check-label">Select All</label>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-slate-600">
                                    <span id="selected-count">0</span> selected
                                </div>
                                <div class="text-lg font-medium text-primary">
                                    Total Amount: ₱<span id="total-amount">0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Apply same amount to selected -->
                        <div class="flex items-center justify-end gap-2 mb-4">
                            <label for="apply-amount-all" class="text-sm text-slate-600">Apply amount to selected</label>
                            <input id="apply-amount-all" type="number" class="form-control w-36 text-sm" step="0.01" min="0" placeholder="e.g. 2000">
                            <button type="button" id="apply-amount-btn" class="btn btn-outline-primary btn-sm">Apply</button>
                        </div>

                        <!-- Scholars List -->
                        <div class="border rounded-lg p-4 max-h-96 overflow-y-auto">
                            <div id="scholars-list">
                                <!-- Scholars will be loaded dynamically here -->
                            </div>
                        </div>

                        <!-- Selected Scholars Summary -->
                        <div id="selected-scholars-summary" class="mt-4" style="display: none;">
                            <div class="box p-4">
                                <h4 class="font-medium mb-3">Selected Scholars Summary</h4>
                                <div id="selected-scholars-list" class="space-y-2">
                                    <!-- Selected scholars will be listed here -->
                                </div>
                                <div class="mt-4 pt-3 border-t">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">Total Amount:</span>
                                        <span class="text-lg font-bold text-primary">₱<span id="final-total-amount">0.00</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Budget Allocation -->
                    <div class="mt-6" id="budget-section" style="display: none;">
                        <label class="form-label">Budget Allocated (Optional)</label>
                        <input type="number" name="budget_allocated" class="form-control" placeholder="Enter budget allocated amount..." step="0.01">
                    </div>

                    <!-- Remarks -->
                    <div class="mt-4" id="remarks-section" style="display: none;">
                        <label class="form-label">Remarks (Optional)</label>
                        <textarea name="remarks" class="form-control" rows="3" placeholder="Add any remarks for this disbursement batch..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                    <button type="submit" class="btn btn-primary w-20" id="submit-btn" disabled>Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const scholarshipProgramSelect = document.getElementById('scholarship-program-select');
    const scholarsSection = document.getElementById('scholars-section');
    const budgetSection = document.getElementById('budget-section');
    const remarksSection = document.getElementById('remarks-section');
    const searchInput = document.getElementById('search-scholars');
    const selectAllCheckbox = document.getElementById('select-all');
    const scholarsList = document.getElementById('scholars-list');
    const selectedCountSpan = document.getElementById('selected-count');
    const totalAmountSpan = document.getElementById('total-amount');
    const submitBtn = document.getElementById('submit-btn');
    const applyAllInput = document.getElementById('apply-amount-all');
    const applyAllBtn = document.getElementById('apply-amount-btn');
    
    let currentScholars = [];
    let selectedScholars = [];

    // Scholarship program selection handler
    scholarshipProgramSelect.addEventListener('change', function() {
        const programId = this.value;
        
        if (programId) {
            // Show scholars section
            scholarsSection.style.display = 'block';
            budgetSection.style.display = 'block';
            remarksSection.style.display = 'block';
            
            // Load scholars for selected program
            loadScholars(programId);
        } else {
            // Hide sections
            scholarsSection.style.display = 'none';
            budgetSection.style.display = 'none';
            remarksSection.style.display = 'none';
            
            // Reset form
            resetForm();
        }
    });

    // Load scholars function
    function loadScholars(programId) {
        fetch(`/disbursements/scholars-by-program?scholarship_program_id=${programId}`)
            .then(response => response.json())
            .then(scholars => {
                currentScholars = scholars;
                selectedScholars = [];
                renderScholars(scholars);
                updateSummary();
            })
            .catch(error => {
                console.error('Error loading scholars:', error);
                alert('Error loading scholars. Please try again.');
            });
    }

    // Render scholars function
    function renderScholars(scholars) {
        scholarsList.innerHTML = '';
        
        if (scholars.length === 0) {
            scholarsList.innerHTML = '<div class="text-center py-8 text-slate-500">No scholars found for this program.</div>';
            return;
        }

        scholars.forEach(scholar => {
            const scholarItem = document.createElement('div');
            scholarItem.className = 'scholar-item flex items-center justify-between p-3 border-b last:border-b-0 hover:bg-slate-50';
            scholarItem.dataset.name = scholar.name.toLowerCase();
            scholarItem.dataset.amount = scholar.amount;
            scholarItem.dataset.scholarId = scholar.id;
            
            scholarItem.innerHTML = `
                <div class="flex items-center flex-1">
                    <input type="checkbox" name="scholar_ids[]" value="${scholar.id}" 
                           class="form-check-input scholar-checkbox mr-3" 
                           data-amount="${scholar.amount}"
                           data-scholar-id="${scholar.id}">
                    <div class="flex-1">
                        <div class="font-medium">${scholar.name}</div>
                        <div class="text-sm text-slate-600">
                            ${scholar.course || 'N/A'} • 
                            Year ${scholar.year_level || 'N/A'} • 
                            ${scholar.institution || 'N/A'}
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <label class="text-xs text-slate-500 font-medium">Individual Amount (₱)</label>
                        <input type="number" 
                               class="form-control scholar-amount-input w-32 text-sm font-medium border-primary" 
                               value="${parseFloat(scholar.amount).toFixed(2)}"
                               min="0"
                               step="0.01"
                               data-scholar-id="${scholar.id}"
                               data-default-amount="${scholar.amount}"
                               placeholder="Enter amount"
                               disabled>
                        <div class="text-xs text-slate-400 mt-1">Default: ₱${parseFloat(scholar.amount).toFixed(2)}</div>
                    </div>
                </div>
            `;
            
            scholarsList.appendChild(scholarItem);
        });

        // Add event listeners to new checkboxes and amount inputs
        addCheckboxListeners();
        addAmountInputListeners();
    }

    // Add checkbox event listeners
    function addCheckboxListeners() {
        const scholarCheckboxes = document.querySelectorAll('.scholar-checkbox');
        scholarCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const scholarId = parseInt(this.value);
                const amountInput = document.querySelector(`input[data-scholar-id="${scholarId}"].scholar-amount-input`);
                
                if (this.checked) {
                    if (!selectedScholars.includes(scholarId)) {
                        selectedScholars.push(scholarId);
                    }
                    amountInput.disabled = false;
                } else {
                    selectedScholars = selectedScholars.filter(id => id !== scholarId);
                    amountInput.disabled = true;
                    amountInput.value = amountInput.dataset.defaultAmount;
                }
                updateSummary();
                updateSelectedScholarsSummary();
            });
        });
    }

    // Add amount input event listeners
    function addAmountInputListeners() {
        const amountInputs = document.querySelectorAll('.scholar-amount-input');
        amountInputs.forEach(input => {
            input.addEventListener('input', function() {
                updateSummary();
                updateSelectedScholarsSummary();
            });
        });
    }

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const scholarItems = document.querySelectorAll('.scholar-item');
        
        scholarItems.forEach(item => {
            const name = item.dataset.name;
            
            if (name.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
        
        updateSelectAllState();
    });

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        const visibleItems = Array.from(document.querySelectorAll('.scholar-item'))
            .filter(item => item.style.display !== 'none');
        
        visibleItems.forEach(item => {
            const checkbox = item.querySelector('.scholar-checkbox');
            const amountInput = item.querySelector('.scholar-amount-input');
            const scholarId = parseInt(checkbox.value);
            
            if (this.checked) {
                checkbox.checked = true;
                amountInput.disabled = false;
                if (!selectedScholars.includes(scholarId)) {
                    selectedScholars.push(scholarId);
                }
            } else {
                checkbox.checked = false;
                amountInput.disabled = true;
                amountInput.value = amountInput.dataset.defaultAmount;
                selectedScholars = selectedScholars.filter(id => id !== scholarId);
            }
        });
        
        updateSummary();
        updateSelectedScholarsSummary();
    });

    // Update summary function
    function updateSummary() {
        const totalAmount = selectedScholars.reduce((sum, scholarId) => {
            const amountInput = document.querySelector(`input[data-scholar-id="${scholarId}"].scholar-amount-input`);
            return sum + (amountInput ? parseFloat(amountInput.value) || 0 : 0);
        }, 0);
        
        selectedCountSpan.textContent = selectedScholars.length;
        totalAmountSpan.textContent = totalAmount.toFixed(2);
        
        // Enable/disable submit button
        submitBtn.disabled = selectedScholars.length === 0;
        
        updateSelectAllState();
    }

    // Update selected scholars summary
    function updateSelectedScholarsSummary() {
        const summaryDiv = document.getElementById('selected-scholars-summary');
        const summaryList = document.getElementById('selected-scholars-list');
        const finalTotalSpan = document.getElementById('final-total-amount');
        
        if (selectedScholars.length === 0) {
            summaryDiv.style.display = 'none';
            return;
        }
        
        summaryDiv.style.display = 'block';
        
        let totalAmount = 0;
        let summaryHTML = '';
        
        selectedScholars.forEach(scholarId => {
            const scholar = currentScholars.find(s => s.id === scholarId);
            const amountInput = document.querySelector(`input[data-scholar-id="${scholarId}"].scholar-amount-input`);
            const amount = parseFloat(amountInput.value) || 0;
            totalAmount += amount;
            
            if (scholar) {
                summaryHTML += `
                    <div class="flex justify-between items-center p-2 bg-slate-50 rounded">
                        <div>
                            <span class="font-medium">${scholar.name}</span>
                            <span class="text-sm text-slate-600 ml-2">${scholar.course || 'N/A'}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="scholar_amounts[${scholarId}]" value="${amount}">
                            <span class="font-medium text-primary">₱${amount.toFixed(2)}</span>
                        </div>
                    </div>
                `;
            }
        });
        
        summaryList.innerHTML = summaryHTML;
        finalTotalSpan.textContent = totalAmount.toFixed(2);
    }

    // Update select all checkbox state
    function updateSelectAllState() {
        const visibleCheckboxes = Array.from(document.querySelectorAll('.scholar-item'))
            .filter(item => item.style.display !== 'none')
            .map(item => item.querySelector('.scholar-checkbox'));
        
        const visibleChecked = visibleCheckboxes.filter(cb => cb.checked);
        
        if (visibleChecked.length === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (visibleChecked.length === visibleCheckboxes.length) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }

    // Reset form function
    function resetForm() {
        currentScholars = [];
        selectedScholars = [];
        scholarsList.innerHTML = '';
        searchInput.value = '';
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
        document.getElementById('selected-scholars-summary').style.display = 'none';
        updateSummary();
    }

    // Form submission validation
    document.getElementById('create-batch-form').addEventListener('submit', function(e) {
        if (selectedScholars.length === 0) {
            e.preventDefault();
            alert('Please select at least one scholar for the disbursement batch.');
            return false;
        }
        
        if (!scholarshipProgramSelect.value) {
            e.preventDefault();
            alert('Please select a scholarship program.');
            return false;
        }
    });

    // Reset form when modal is closed
    document.getElementById('create-disbursement-modal').addEventListener('hidden.bs.modal', function() {
        resetForm();
        scholarshipProgramSelect.value = '';
        scholarsSection.style.display = 'none';
        budgetSection.style.display = 'none';
        remarksSection.style.display = 'none';
    });

    // Apply same amount to all selected scholars
    function applyAmountToSelected(amountValue) {
        const amount = parseFloat(amountValue);
        if (isNaN(amount) || amount < 0) {
            return;
        }
        selectedScholars.forEach(scholarId => {
            const amountInput = document.querySelector(`input[data-scholar-id="${scholarId}"].scholar-amount-input`);
            if (amountInput && !amountInput.disabled) {
                amountInput.value = amount.toFixed(2);
            }
        });
        updateSummary();
        updateSelectedScholarsSummary();
    }

    if (applyAllBtn) {
        applyAllBtn.addEventListener('click', function() {
            applyAmountToSelected(applyAllInput.value);
        });
    }

    if (applyAllInput) {
        applyAllInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyAmountToSelected(applyAllInput.value);
            }
        });
    }
});
</script>
@endpush


