@extends('layouts.app')
@section('title', '')
@section('content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <h2 class="intro-y text-lg font-medium mr-auto">Allocation Logs</h2>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <a href="{{ route('disbursements.index') }}" class="btn btn-outline-secondary shadow-md mr-2">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Disbursements
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-span-12">
        <div class="box p-5">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-48">
                    <label class="form-label">Transaction Type</label>
                    <select name="transaction_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="allocation" {{ request('transaction_type') == 'allocation' ? 'selected' : '' }}>Allocation</option>
                        <option value="disbursement" {{ request('transaction_type') == 'disbursement' ? 'selected' : '' }}>Disbursement</option>
                        <option value="adjustment" {{ request('transaction_type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                    </select>
                </div>
                <div class="flex-1 min-w-48">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="flex-1 min-w-48">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="search" class="w-4 h-4 mr-1"></i> Filter
                    </button>
                    <a href="{{ route('disbursements.allocation-logs') }}" class="btn btn-outline-secondary ml-2">
                        <i data-lucide="x" class="w-4 h-4 mr-1"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <div class="max-w-7xl mx-auto px-4">
            <table class="table table-report -mt-2 w-full text-center">
                <thead>
                    <tr>
                        <th class="w-40 !py-4">REFERENCE</th>
                        <th class="w-40">TYPE</th>
                        <th class="text-center">AMOUNT</th>
                        <th>DESCRIPTION</th>
                        <th>ALLOCATED BY</th>
                        <th>DATE</th>
                        <th class="text-center whitespace-nowrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allocationLogs ?? [] as $log)
                    @php
                        $type = $log->transaction_type;
                        $typeIcon = $type === 'allocation' ? 'plus-circle' : ($type === 'disbursement' ? 'minus-circle' : 'edit');
                        $typeClass = $type === 'allocation' ? 'text-success' : ($type === 'disbursement' ? 'text-warning' : 'text-primary');
                    @endphp
                    <tr class="intro-x">
                        <td class="w-40 !py-4">
                            <div class="underline decoration-dotted whitespace-nowrap">{{ $log->reference_number ?? 'N/A' }}</div>
                        </td>
                        <td class="w-40">
                            <div class="flex items-center justify-center whitespace-nowrap {{ $typeClass }}">
                                <i data-lucide="{{ $typeIcon }}" class="w-4 h-4 mr-2"></i> {{ ucfirst($type) }}
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="font-medium {{ $type === 'allocation' ? 'text-success' : 'text-warning' }}">
                                {{ $type === 'allocation' ? '+' : '-' }}₱{{ number_format($log->amount, 2) }}
                            </div>
                        </td>
                        <td>
                            <div class="text-sm">{{ $log->description ?? 'N/A' }}</div>
                            @if($log->disbursementBatch)
                                <div class="text-xs text-slate-500 mt-1">
                                    Batch: {{ $log->disbursementBatch->reference_number ?? 'N/A' }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="text-sm font-medium">{{ $log->allocatedBy->first_name ?? 'N/A' }} {{ $log->allocatedBy->last_name ?? '' }}</div>
                            <div class="text-xs text-slate-500">{{ $log->allocatedBy->email ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ $log->created_at->format('d M, H:i') }}</div>
                        </td>
                        <td class="table-report__action">
                            <div class="flex justify-center items-center">
                                <button type="button" class="flex items-center text-primary whitespace-nowrap" data-tw-toggle="modal" data-tw-target="#view-log-modal-{{ $log->id }}">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i> View Details
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-slate-500">No allocation logs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if(isset($allocationLogs) && $allocationLogs->hasPages())
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        {{ $allocationLogs->links() }}
    </div>
    @endif
</div>

<!-- View Log Modals -->
@foreach($allocationLogs ?? [] as $log)
<div id="view-log-modal-{{ $log->id }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Allocation Log Details</h2>
                <button data-tw-dismiss="modal" class="btn btn-outline-secondary hidden sm:flex">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Reference Number</label>
                        <div class="form-control-plaintext">{{ $log->reference_number ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <label class="form-label">Transaction Type</label>
                        <div class="form-control-plaintext">
                            <span class="badge badge-{{ $log->transaction_type_badge }}">{{ ucfirst($log->transaction_type) }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Amount</label>
                        <div class="form-control-plaintext font-medium">{{ $log->formatted_amount }}</div>
                    </div>
                    <div>
                        <label class="form-label">Date</label>
                        <div class="form-control-plaintext">{{ $log->created_at->format('M d, Y H:i:s') }}</div>
                    </div>
                    <div class="col-span-2">
                        <label class="form-label">Description</label>
                        <div class="form-control-plaintext">{{ $log->description ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <label class="form-label">Allocated By</label>
                        <div class="form-control-plaintext">{{ $log->allocatedBy->first_name ?? 'N/A' }} {{ $log->allocatedBy->last_name ?? '' }}</div>
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <div class="form-control-plaintext">{{ $log->allocatedBy->email ?? 'N/A' }}</div>
                    </div>
                    @if($log->disbursementBatch)
                    <div class="col-span-2">
                        <label class="form-label">Related Disbursement Batch</label>
                        <div class="form-control-plaintext">
                            <a href="{{ route('disbursements.show', $log->disbursementBatch->id) }}" class="text-primary underline">
                                {{ $log->disbursementBatch->reference_number ?? 'Batch #' . $log->disbursementBatch->id }}
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-tw-dismiss="modal" class="btn btn-secondary w-20">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
