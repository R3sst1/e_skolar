@extends('layouts.app')
@section('title', '')
@section('content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <h2 class="intro-y text-lg font-medium mr-auto">Consolidated Transactions (E-Kalinga)</h2>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <a href="{{ route('disbursements.index') }}" class="btn btn-outline-secondary shadow-md">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Disbursements
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <!-- <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Total Transactions</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-slate-600">{{ $stats['total_transactions'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Endorsed Status</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-success">{{ $stats['endorsed'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Total Budget</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-primary">₱{{ number_format($stats['total_budget'] ?? 0, 2) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Budget Received</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-success">₱{{ number_format($stats['budget_received'] ?? 0, 2) }}</div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Filters -->
    <!-- <div class="col-span-12">
        <div class="box p-5">
            <form method="GET" action="{{ route('disbursements.consolidated-transactions') }}" class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Beneficiary ID or Remarks..." value="{{ request('search') }}">
                </div>
                <div class="col-span-12 md:col-span-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="endorsed" {{ request('status') == 'endorsed' ? 'selected' : '' }}>Endorsed</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-span-12 md:col-span-2">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-span-12 md:col-span-2">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-span-12 md:col-span-3 flex items-end gap-2">
                    <button type="submit" class="btn btn-primary w-full">
                        <i data-lucide="search" class="w-4 h-4 mr-2"></i> Filter
                    </button>
                    <a href="{{ route('disbursements.consolidated-transactions') }}" class="btn btn-outline-secondary w-full">
                        <i data-lucide="x" class="w-4 h-4 mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div> -->

    <!-- Consolidated Transactions Table -->
    <div class="intro-y col-span-12">
        <div class="box p-5">
            <h3 class="text-lg font-medium mb-4">Transaction Records</h3>
            <div class="overflow-x-auto">
                <table class="table table-report w-full">
                    <thead>
                        <tr>
                            <th class="!py-4">ID</th>
                            <th>BENEFICIARY NAME</th>
                            <th>BENEFICIARY ID</th>
                            <th class="text-right">TOTAL BUDGET</th>
                            <th class="text-right">BUDGET TO GIVE</th>
                            <th class="text-right">BUDGET RECEIVED</th>
                            <th class="text-center">STATUS</th>
                            <th>DATE</th>
                            <th>REMARKS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions ?? [] as $transaction)
                        @php
                            $scholar = \App\Models\Scholar::find($transaction->beneficiary_id);
                            $beneficiaryName = $scholar && $scholar->user 
                                ? $scholar->user->first_name . ' ' . $scholar->user->last_name 
                                : 'Unknown';
                        @endphp
                        <tr class="intro-x">
                            <td class="!py-4">
                                <div class="font-medium text-primary">#{{ $transaction->id }}</div>
                            </td>
                            <td>
                                <div class="font-medium">{{ $beneficiaryName }}</div>
                            </td>
                            <td>
                                <div class="text-slate-600">{{ $transaction->beneficiary_id }}</div>
                            </td>
                            <td class="text-right">
                                <div class="font-medium text-primary">₱{{ number_format($transaction->total_budget ?? 0, 2) }}</div>
                            </td>
                            <td class="text-right">
                                <div class="font-medium text-success">₱{{ number_format($transaction->budget ?? 0, 2) }}</div>
                            </td>
                            <td class="text-right">
                                @if($transaction->budget_received)
                                    <div class="font-medium text-success">₱{{ number_format($transaction->budget_received, 2) }}</div>
                                @else
                                    <span class="text-slate-400 text-sm">Pending</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="px-2 py-1 rounded text-xs font-medium bg-success/10 text-success">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="text-slate-600">{{ $transaction->created_at->format('M d, Y') }}</div>
                                <div class="text-slate-500 text-xs">{{ $transaction->created_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                <div class="text-slate-600 text-sm truncate max-w-xs" title="{{ $transaction->remarks }}">
                                    {{ $transaction->remarks ?? '-' }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-10 text-slate-500">
                                <i data-lucide="inbox" class="w-16 h-16 mx-auto mb-3 text-slate-300"></i>
                                <p>No consolidated transactions found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if(isset($transactions) && $transactions->hasPages())
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        {{ $transactions->links() }}
    </div>
    @endif
</div>

@endsection

