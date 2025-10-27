@extends('layouts.app')
@section('title', 'Disbursement Details')
@section('content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <h2 class="intro-y text-lg font-medium mr-auto">Batch {{ $disbursementBatch->reference_number }}</h2>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <a href="{{ route('disbursements.index') }}" class="btn btn-outline-secondary">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="col-span-12 lg:col-span-3">
        <div class="box p-5">
            <div class="text-slate-500">Status</div>
            <div class="mt-2 text-base font-medium">
                <span class="px-2 py-1 rounded-full text-xs {{ $disbursementBatch->status==='pending'?'bg-warning text-white':($disbursementBatch->status==='reviewed'?'bg-primary text-white':'bg-success text-white') }}">
                    {{ ucfirst($disbursementBatch->status) }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3">
        <div class="box p-5">
            <div class="text-slate-500">Total Amount</div>
            <div class="mt-2 text-base font-medium">₱{{ number_format($disbursementBatch->total_amount ?? 0, 2) }}</div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3">
        <div class="box p-5">
            <div class="text-slate-500">Students</div>
            <div class="mt-2 text-base font-medium">{{ $disbursementBatch->disbursementBatchStudents->count() }}</div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3">
        <div class="box p-5">
            <div class="text-slate-500">Created</div>
            <div class="mt-2 text-base font-medium">{{ $disbursementBatch->created_at?->format('d M, H:i') }}</div>
        </div>
    </div>

    <!-- Program / Budget -->
    <div class="col-span-12">
        <div class="box p-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="text-slate-500">Scholarship Program</div>
                    <div class="mt-1 text-base font-medium">{{ optional($disbursementBatch->scholarshipProgram)->name ?? '—' }}</div>
                </div>
                @php
                    $program = $disbursementBatch->scholarshipProgram;
                    $allocated = (float) optional($program)->allocated_budget;
                    $already = (float) \App\Models\DisbursementBatch::where('scholarship_program_id', optional($program)->id)
                        ->whereIn('status', ['reviewed', 'disbursed'])
                        ->sum('total_amount');
                    $remaining = max(0, $allocated - $already);
                    $needed = (float) ($disbursementBatch->total_amount ?? 0);
                    $lack = max(0, $needed - $remaining);
                @endphp
                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <div class="text-[11px] text-slate-500">Allocated</div>
                        <div class="text-sm font-medium">₱{{ number_format($allocated, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] text-slate-500">Remaining</div>
                        <div class="text-sm font-medium {{ $remaining>0?'text-success':'text-danger' }}">₱{{ number_format($remaining, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] text-slate-500">Lack</div>
                        <div class="text-sm font-medium {{ $lack>0?'text-danger':'text-success' }}">₱{{ number_format($lack, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="col-span-12">
        <div class="intro-y box mt-5">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Students in Batch</h2>
            </div>
            <div class="p-5 overflow-x-auto">
                <table class="table table-report -mt-2 w-full">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">Student</th>
                            <th class="whitespace-nowrap text-right">Requested Amount</th>
                            <th class="whitespace-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($disbursementBatch->disbursementBatchStudents as $bs)
                        <tr>
                            <td class="text-left whitespace-nowrap">{{ optional($bs->scholar->user)->first_name }} {{ optional($bs->scholar->user)->last_name }}</td>
                            <td class="text-right whitespace-nowrap">₱{{ number_format($bs->requested_amount ?? 0, 2) }}</td>
                            <td class="whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full text-xs {{ $bs->status==='pending'?'bg-warning text-white':($bs->status==='approved'?'bg-primary text-white':($bs->status==='disbursed'?'bg-success text-white':'bg-danger text-white')) }}">
                                    {{ ucfirst($bs->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
