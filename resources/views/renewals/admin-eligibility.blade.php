@extends('layouts.app')
@section('title', 'Scholar Eligibility & Renewals')
@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Scholar Eligibility & Renewals</h2>
</div>

<!-- Summary -->
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 md:col-span-4">
        <div class="box p-5 text-center">
            <div class="text-3xl font-bold text-success">{{ $summary['eligible'] }}</div>
            <div class="text-slate-500 mt-1">Eligible</div>
        </div>
    </div>
    <div class="col-span-12 md:col-span-4">
        <div class="box p-5 text-center">
            <div class="text-3xl font-bold text-warning">{{ $summary['at_risk'] }}</div>
            <div class="text-slate-500 mt-1">At Risk</div>
        </div>
    </div>
    <div class="col-span-12 md:col-span-4">
        <div class="box p-5 text-center">
            <div class="text-3xl font-bold text-danger">{{ $summary['not_eligible'] }}</div>
            <div class="text-slate-500 mt-1">Not Eligible</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="intro-y box mt-5">
    <div class="flex items-center p-5 border-b border-slate-200/60">
        <h2 class="font-medium text-base mr-auto">Filters</h2>
    </div>
    <div class="p-5">
        <form method="GET" action="{{ route('admin.renewals.eligibility') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Semester</label>
                <select name="semester" class="form-select w-full">
                    <option value="">All Semesters</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>{{ $semester }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">School Year</label>
                <select name="school_year" class="form-select w-full">
                    <option value="">All Years</option>
                    @foreach($schoolYears as $year)
                        <option value="{{ $year }}" {{ request('school_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-select w-full">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-1 flex items-end">
                <button type="submit" class="btn btn-primary mr-2">
                    <i data-lucide="search" class="w-4 h-4 mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.renewals.eligibility') }}" class="btn btn-outline-secondary">
                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Renewals Table -->
<div class="intro-y box mt-5">
    <div class="flex items-center p-5 border-b border-slate-200/60">
        <h2 class="font-medium text-base mr-auto">Renewal Records</h2>
    </div>
    <div class="p-5">
        <div class="overflow-x-auto">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th>Scholar</th>
                        <th>Semester</th>
                        <th>School Year</th>
                        <th>Eligibility</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($renewals as $renewal)
                        <tr class="intro-x">
                            <td>
                                <div class="font-medium">{{ $renewal->scholar->user->first_name }} {{ $renewal->scholar->user->last_name }}</div>
                                <div class="text-slate-500 text-xs">{{ $renewal->scholar->institution }}</div>
                            </td>
                            <td>{{ $renewal->semester }}</td>
                            <td>{{ $renewal->school_year }}</td>
                            <td>
                                @if($renewal->meets_retention_requirements)
                                    <span class="badge bg-success">Eligible</span>
                                @else
                                    <span class="badge bg-danger">Not Eligible</span>
                                @endif
                            </td>
                            <td>
                                @if($renewal->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($renewal->status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @elseif($renewal->status == 'needs_additional_requirements')
                                    <span class="badge bg-warning">Needs Additional</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('renewals.show', $renewal->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i> View
                                </a>
                                @if($renewal->status == 'pending')
                                    <form method="POST" action="{{ route('renewals.review', $renewal->id) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn btn-outline-success btn-sm">
                                            <i data-lucide="check" class="w-4 h-4 mr-1"></i> Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('renewals.review', $renewal->id) }}" class="inline ml-2">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i data-lucide="x" class="w-4 h-4 mr-1"></i> Reject
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-slate-500">
                                No renewal records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-5">
            {{ $renewals->links() }}
        </div>
    </div>
</div>
@endsection 