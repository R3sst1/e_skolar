@extends('layouts.app')
@section('title', '')
@section('content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <h2 class="intro-y text-lg font-medium mr-auto">Budget Request Management</h2>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal" data-tw-target="#create-budget-request-modal">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> New Request
            </button>
        </div>
    </div>
    
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="intro-y col-span-12">
            <div class="alert alert-success">{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="intro-y col-span-12">
            <div class="alert alert-danger">{{ session('error') }}</div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Total Requests</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-slate-600">{{ $budgetRequests->total() ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Pending Requests</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-warning">{{ $budgetRequests->where('status', 'pending')->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Approved Requests</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-success">{{ $budgetRequests->where('status', 'approved')->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Rejected Requests</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-danger">{{ $budgetRequests->where('status', 'rejected')->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <div class="max-w-7xl mx-auto px-4">
            <table class="table table-report -mt-2 w-full text-center">
                <thead>
                    <tr>
                        <th class="w-40 !py-4">REFERENCE</th>
                        <th class="w-40">PURPOSE</th>
                        <th class="text-center">STATUS</th>
                        <th>AMOUNT</th>
                        <th>DATE</th>
                        <th class="text-center whitespace-nowrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($budgetRequests ?? [] as $budgetRequest)
                    @php
                        $status = $budgetRequest->status;
                        $statusIcon = $status === 'approved' ? 'check-square' : ($status === 'rejected' ? 'x-square' : 'clock');
                        $statusClass = $status === 'approved' ? 'text-success' : ($status === 'rejected' ? 'text-danger' : 'text-warning');
                    @endphp
                    <tr class="intro-x">
                        <td class="w-40 !py-4">
                            <div class="underline decoration-dotted whitespace-nowrap">#BR-{{ str_pad($budgetRequest->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="w-40">
                            <div class="font-medium whitespace-nowrap">{{ $budgetRequest->purpose }}</div>
                            @if($budgetRequest->description)
                                <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ Str::limit($budgetRequest->description, 30) }}</div>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center whitespace-nowrap {{ $statusClass }}">
                                <i data-lucide="{{ $statusIcon }}" class="w-4 h-4 mr-2"></i> {{ ucfirst($status) }}
                            </div>
                        </td>
                        <td>
                            <div class="font-medium">{{ $budgetRequest->formatted_amount }}</div>
                        </td>
                        <td>
                            <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ $budgetRequest->created_at->format('d M, H:i') }}</div>
                        </td>
                        <td class="table-report__action">
                            <div class="flex justify-center items-center">
                                <button type="button" 
                                        class="flex items-center text-primary whitespace-nowrap mr-3" 
                                        data-tw-toggle="modal" 
                                        data-tw-target="#view-request-modal-{{ $budgetRequest->id }}">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i> View
                                </button>
                                @if($budgetRequest->status === 'pending')
                                    <div class="dropdown" data-tw-dropdown>
                                        <button class="dropdown-toggle btn btn-outline-secondary btn-sm" aria-expanded="false" data-tw-toggle="dropdown">
                                            <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <div class="dropdown-content">
                                                <button type="button" 
                                                        class="dropdown-item text-success" 
                                                        onclick="updateStatus({{ $budgetRequest->id }}, 'approved')">
                                                    <i data-lucide="check" class="w-4 h-4 mr-2"></i> Approve
                                                </button>
                                                <button type="button" 
                                                        class="dropdown-item text-danger" 
                                                        onclick="updateStatus({{ $budgetRequest->id }}, 'rejected')">
                                                    <i data-lucide="x" class="w-4 h-4 mr-2"></i> Reject
                                                </button>
                                                <div class="dropdown-divider"></div>
                                                <button type="button" 
                                                        class="dropdown-item text-danger" 
                                                        onclick="deleteRequest({{ $budgetRequest->id }})">
                                                    <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-slate-500">No budget requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if(isset($budgetRequests) && $budgetRequests->hasPages())
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        {{ $budgetRequests->links() }}
    </div>
    @endif
</div>

<!-- Create Budget Request Modal -->
<div id="create-budget-request-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Create Budget Request</h2>
                <button data-tw-dismiss="modal" class="btn btn-outline-secondary hidden sm:flex">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('budget-requests.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">Requested Amount (₱) *</label>
                        <input type="number" name="requested_amount" class="form-control" step="0.01" min="0" placeholder="Enter amount" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Purpose *</label>
                        <input type="text" name="purpose" class="form-control" placeholder="e.g., Additional funds for scholarship program" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Add detailed description (optional)"></textarea>
                    </div>
                    <div class="mb-4 p-3 bg-slate-50 rounded">
                        <div class="text-sm text-slate-600 mb-2">Request Details</div>
                        <div class="text-sm">
                            <div class="flex justify-between mb-1">
                                <span>Office ID:</span>
                                <span class="font-medium">6 (Scholarship Office)</span>
                            </div>
                            <div class="flex justify-between mb-1">
                                <span>Status:</span>
                                <span class="font-medium text-warning">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                    <button type="submit" class="btn btn-primary w-20">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Request Modals -->
@foreach($budgetRequests as $budgetRequest)
<div id="view-request-modal-{{ $budgetRequest->id }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Budget Request #BR-{{ str_pad($budgetRequest->id, 6, '0', STR_PAD_LEFT) }}</h2>
                <button data-tw-dismiss="modal" class="btn btn-outline-secondary hidden sm:flex">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="form-label">Reference</label>
                        <div class="form-control-plaintext">#BR-{{ str_pad($budgetRequest->id, 6, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <div class="form-control-plaintext">
                            <span class="badge {{ $budgetRequest->status_badge }}">{{ ucfirst($budgetRequest->status) }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Requested Amount</label>
                        <div class="form-control-plaintext font-medium">{{ $budgetRequest->formatted_amount }}</div>
                    </div>
                    <div>
                        <label class="form-label">Office ID</label>
                        <div class="form-control-plaintext">6 (Scholarship Office)</div>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Purpose</label>
                    <div class="form-control-plaintext">{{ $budgetRequest->purpose }}</div>
                </div>
                @if($budgetRequest->description)
                <div class="mb-4">
                    <label class="form-label">Description</label>
                    <div class="form-control-plaintext">{{ $budgetRequest->description }}</div>
                </div>
                @endif
                <div class="mb-4">
                    <label class="form-label">Created At</label>
                    <div class="form-control-plaintext">{{ $budgetRequest->created_at->format('M d, Y H:i') }}</div>
                </div>
                @if($budgetRequest->updated_at != $budgetRequest->created_at)
                <div class="mb-4">
                    <label class="form-label">Last Updated</label>
                    <div class="form-control-plaintext">{{ $budgetRequest->updated_at->format('M d, Y H:i') }}</div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" data-tw-dismiss="modal" class="btn btn-secondary w-20">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
function updateStatus(id, status) {
    if (confirm(`Are you sure you want to ${status} this budget request?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/budget-requests/${id}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PUT';
        
        const statusField = document.createElement('input');
        statusField.type = 'hidden';
        statusField.name = 'status';
        statusField.value = status;
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        form.appendChild(statusField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteRequest(id) {
    if (confirm('Are you sure you want to delete this budget request?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/budget-requests/${id}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush

@push('styles')
<style>
    .table th {
        background-color: #f8fafc;
        font-weight: 600;
        color: #334155;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .btn:active {
        transform: translateY(0);
    }
    
    .table tbody tr:hover {
        background-color: #f1f5f9 !important;
    }
</style>
@endpush
