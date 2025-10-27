@extends('layouts.app')
@section('title', 'Ekalinga Requests')
@section('content')

<div class="max-w-7xl mx-auto p-4">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Ekalinga Requests</h1>
        <form method="GET" class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search requests...">
            <select name="status" class="form-select">
                <option value="">All</option>
                @foreach(['pending','approved','rejected','disbursed'] as $s)
                    <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary"><i data-lucide="search" class="w-4 h-4 mr-2"></i> Search</button>
        </form>
    </div>

    <div class="intro-y box mt-5">
        <div class="p-5 overflow-x-auto">
            <table class="table table-report -mt-2 w-full">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Office</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Office Budget</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $r)
                    @php
                        $amount = (new class { public function calc($r){ $q=max(1,(int)($r->quantity??1)); $qt= (float)($r->item_cost??0)*$q; return (float)($r->requested_amount??$qt);} })->calc($r);
                        $budget = $r->office_id ? ($officeBudgets[$r->office_id] ?? null) : null;
                    @endphp
                    <tr>
                        <td>
                            <div class="font-medium">{{ $r->item_name ?? '—' }}</div>
                            <div class="text-xs text-slate-500">Qty: {{ $r->quantity }} @if($r->item_cost) x ₱{{ number_format($r->item_cost,2) }} @endif</div>
                        </td>
                        <td>{{ $officeNames[$r->office_id] ?? '—' }}</td>
                        <td>₱{{ number_format($amount, 2) }}</td>
                        <td>
                            <span class="px-2 py-1 rounded-full text-xs {{ $r->status==='pending'?'bg-warning text-white':($r->status==='approved'?'bg-primary text-white':($r->status==='disbursed'?'bg-success text-white':'bg-danger text-white')) }}">
                                {{ ucfirst($r->status) }}
                            </span>
                        </td>
                        <td>
                            @if($budget)
                                <div class="text-xs">Allocated: ₱{{ number_format($budget->total_allocated ?? 0,2) }}</div>
                                <div class="text-xs">Used: ₱{{ number_format($budget->total_used ?? 0,2) }}</div>
                                <div class="text-xs">Remaining: <span class="font-medium">₱{{ number_format($budget->total_remaining ?? 0,2) }}</span></div>
                            @else
                                <span class="text-slate-400 text-xs">No budget</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="flex justify-center items-center gap-2">
                                @if($r->status==='pending')
                                <form method="POST" action="{{ route('ekalinga.requests.approve', $r->id) }}">
                                    @csrf
                                    <button class="btn btn-success btn-sm"><i data-lucide="check" class="w-4 h-4 mr-1"></i> Approve</button>
                                </form>
                                <form method="POST" action="{{ route('ekalinga.requests.reject', $r->id) }}">
                                    @csrf
                                    <button class="btn btn-danger btn-sm"><i data-lucide="x" class="w-4 h-4 mr-1"></i> Reject</button>
                                </form>
                                @endif
                                @if($r->status==='approved')
                                <form method="POST" action="{{ route('ekalinga.requests.disburse', $r->id) }}">
                                    @csrf
                                    <button class="btn btn-warning btn-sm"><i data-lucide="send" class="w-4 h-4 mr-1"></i> Disburse</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-10 text-slate-500">No requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-5">
            {{ $requests->links() }}
        </div>
    </div>
</div>

@endsection


