@extends('layouts.app')
@section('title', 'Scholar Release Management')
@section('content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <h2 class="intro-y text-lg font-medium mr-auto">Scholar Release Management</h2>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal" data-tw-target="#bulk-release-modal">
                <i data-lucide="send" class="w-4 h-4 mr-2"></i> Bulk Release
            </button>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Total Disbursements</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-slate-600">{{ $stats['total_disbursements'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Released</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-success">{{ $stats['released_disbursements'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Unreleased</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-warning">{{ $stats['unreleased_disbursements'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Total Released</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-primary">₱{{ number_format($stats['total_amount_released'] ?? 0, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-span-12">
        <div class="box p-5">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-48">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by scholar name...">
                </div>
                <div class="min-w-32">
                    <select name="release_status" class="form-select">
                        <option value="">All Release Status</option>
                        <option value="released" {{ request('release_status') === 'released' ? 'selected' : '' }}>Released</option>
                        <option value="unreleased" {{ request('release_status') === 'unreleased' ? 'selected' : '' }}>Unreleased</option>
                    </select>
                </div>
                <div class="min-w-32">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="disbursed" {{ request('status') === 'disbursed' ? 'selected' : '' }}>Disbursed</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('scholar-release.index') }}" class="btn btn-outline-secondary">Clear</a>
            </form>
        </div>
    </div>

    <!-- Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <div class="max-w-7xl mx-auto px-4">
            <table class="table table-report -mt-2 w-full text-center">
                <thead>
                    <tr>
                        <th>SCHOLAR NAME</th>
                        <th>SCHOLARSHIP PROGRAM</th>
                        <th>REQUESTED AMOUNT</th>
                        <th>ACTUAL AMOUNT</th>
                        <th>STATUS</th>
                        <th>RELEASE STATUS</th>
                        <th>RELEASED DATE</th>
                        <th class="text-center whitespace-nowrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disbursements ?? [] as $disbursement)
                    <tr class="intro-x">
                        <td class="align-top">
                            <div class="font-medium text-base">
                                {{ $disbursement->scholar->user->first_name ?? 'N/A' }} 
                                {{ $disbursement->scholar->user->last_name ?? 'N/A' }}
                            </div>
                            <div class="text-sm text-slate-500">
                                {{ $disbursement->scholar->course ?? 'N/A' }} • 
                                Year {{ $disbursement->scholar->year_level ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="align-top text-center">
                            <span class="font-medium">{{ $disbursement->disbursementBatch->scholarshipProgram->name ?? 'N/A' }}</span>
                        </td>
                        <td class="align-top text-center">
                            <span class="font-medium">₱{{ number_format($disbursement->requested_amount ?? 0, 2) }}</span>
                        </td>
                        <td class="align-top text-center">
                            <span class="font-medium">
                                @if($disbursement->actual_amount)
                                    ₱{{ number_format($disbursement->actual_amount, 2) }}
                                @else
                                    <span class="text-slate-400">Not set</span>
                                @endif
                            </span>
                        </td>
                        <td class="align-top text-center">
                            <div class="flex items-center justify-center">
                                <span class="px-2 py-1 rounded-full text-xs 
                                    {{ $disbursement->status === 'pending' ? 'bg-warning text-white' : 
                                       ($disbursement->status === 'approved' ? 'bg-primary text-white' : 
                                       ($disbursement->status === 'disbursed' ? 'bg-success text-white' : 'bg-danger text-white')) }}">
                                    {{ ucfirst($disbursement->status) }}
                                </span>
                            </div>
                        </td>
                        <td class="align-top text-center">
                            <div class="flex items-center justify-center">
                                <span class="px-2 py-1 rounded-full text-xs {{ $disbursement->release_status_badge }}">
                                    {{ ucfirst($disbursement->release_status) }}
                                </span>
                            </div>
                        </td>
                        <td class="align-top text-center">
                            <span class="text-slate-600">
                                @if($disbursement->released_at)
                                    {{ $disbursement->formatted_released_at }}
                                @else
                                    <span class="text-slate-400">Not released</span>
                                @endif
                            </span>
                        </td>
                        <td class="table-report__action w-56 align-top text-center">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('scholar-release.show', $disbursement->id) }}" class="btn btn-primary btn-sm">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i> View
                                </a>
                                @if($disbursement->release_status === 'unreleased')
                                <button class="btn btn-success btn-sm" 
                                        data-tw-toggle="modal" 
                                        data-tw-target="#release-modal-{{ $disbursement->id }}">
                                    <i data-lucide="send" class="w-4 h-4 mr-1"></i> Release
                                </button>
                                @else
                                <form method="POST" action="{{ route('scholar-release.unrelease', $disbursement->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to unrelease this disbursement?')">
                                        <i data-lucide="undo" class="w-4 h-4 mr-1"></i> Unrelease
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Release Modal for each disbursement -->
                    @if($disbursement->release_status === 'unreleased')
                    <div id="release-modal-{{ $disbursement->id }}" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="font-medium text-base mr-auto">Release Disbursement</h2>
                                    <button data-tw-dismiss="modal" class="btn btn-outline-secondary hidden sm:flex">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <form method="POST" action="{{ route('scholar-release.release', $disbursement->id) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-4">
                                            <label class="form-label">Scholar Name</label>
                                            <input type="text" class="form-control" 
                                                   value="{{ $disbursement->scholar->user->first_name ?? 'N/A' }} {{ $disbursement->scholar->user->last_name ?? 'N/A' }}" 
                                                   readonly>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Requested Amount</label>
                                            <input type="text" class="form-control" 
                                                   value="₱{{ number_format($disbursement->requested_amount ?? 0, 2) }}" 
                                                   readonly>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Actual Amount to Release *</label>
                                            <input type="number" name="actual_amount" class="form-control" 
                                                   value="{{ $disbursement->requested_amount ?? 0 }}" 
                                                   min="0" step="0.01" required>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Release Remarks (Optional)</label>
                                            <textarea name="release_remarks" class="form-control" rows="3" 
                                                      placeholder="Add any remarks for this release..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                                        <button type="submit" class="btn btn-primary w-20">Release</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-10 text-slate-500">No disbursements found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if(isset($disbursements) && $disbursements->hasPages())
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        {{ $disbursements->links() }}
    </div>
    @endif
</div>

<!-- Bulk Release Modal -->
<div id="bulk-release-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Bulk Release Disbursements</h2>
                <button data-tw-dismiss="modal" class="btn btn-outline-secondary hidden sm:flex">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('scholar-release.bulk-release') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">Select Unreleased Disbursements</label>
                        <div class="border rounded-lg p-4 max-h-96 overflow-y-auto">
                            <div id="bulk-disbursements-list">
                                <!-- Unreleased disbursements will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                    <button type="submit" class="btn btn-primary w-20" id="bulk-release-btn" disabled>Release Selected</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load unreleased disbursements for bulk release
    loadUnreleasedDisbursements();
});

function loadUnreleasedDisbursements() {
    fetch('/scholar-release/unreleased')
        .then(response => response.json())
        .then(disbursements => {
            const container = document.getElementById('bulk-disbursements-list');
            container.innerHTML = '';
            
            if (disbursements.length === 0) {
                container.innerHTML = '<div class="text-center py-8 text-slate-500">No unreleased disbursements found.</div>';
                return;
            }
            
            disbursements.forEach(disbursement => {
                const item = document.createElement('div');
                item.className = 'flex items-center justify-between p-3 border-b last:border-b-0 hover:bg-slate-50';
                
                item.innerHTML = `
                    <div class="flex items-center">
                        <input type="checkbox" name="disbursement_ids[]" value="${disbursement.id}" 
                               class="form-check-input bulk-checkbox mr-3">
                        <div>
                            <div class="font-medium">${disbursement.scholar_name}</div>
                            <div class="text-sm text-slate-600">${disbursement.program_name}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <label class="text-xs text-slate-500">Amount (₱)</label>
                            <input type="number" name="actual_amounts[${disbursement.id}]" 
                                   class="form-control w-24 text-sm" 
                                   value="${disbursement.requested_amount}" 
                                   min="0" step="0.01">
                        </div>
                    </div>
                `;
                
                container.appendChild(item);
            });
            
            // Add event listeners
            addBulkCheckboxListeners();
        })
        .catch(error => {
            console.error('Error loading disbursements:', error);
        });
}

function addBulkCheckboxListeners() {
    const checkboxes = document.querySelectorAll('.bulk-checkbox');
    const submitBtn = document.getElementById('bulk-release-btn');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.bulk-checkbox:checked').length;
            submitBtn.disabled = checkedCount === 0;
        });
    });
}
</script>
@endpush
