@extends('layouts.app')
@section('title', 'Scholar Performance Tracking')
@section('content')
<div class="intro-y flex items-center mt-8">
   
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('scholar-performance.create') }}" class="btn btn-primary shadow-md mr-2">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Performance Record
        </a>
        <a href="{{ route('scholar-performance.analytics') }}" class="btn btn-outline-secondary shadow-md">
            <i data-lucide="bar-chart" class="w-4 h-4 mr-2"></i> Analytics
        </a>
    </div>
</div>

<!-- Statistics -->
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="users" class="report-box__icon text-primary"></i>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $stats['total_records'] }}</div>
                <div class="text-base text-slate-500 mt-1">Total Records</div>
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="check-circle" class="report-box__icon text-success"></i>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $stats['good_standing'] }}</div>
                <div class="text-base text-slate-500 mt-1">Good Standing</div>
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="alert-triangle" class="report-box__icon text-warning"></i>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $stats['needs_attention'] }}</div>
                <div class="text-base text-slate-500 mt-1">Needs Attention</div>
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="target" class="report-box__icon text-primary"></i>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $stats['meets_requirements'] }}</div>
                <div class="text-base text-slate-500 mt-1">Meets Requirements</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="intro-y box mt-5">
    <div class="flex items-center p-5 border-b border-slate-200/60">
        <h2 class="font-medium text-base mr-auto">Filters</h2>
    </div>
    <div class="p-5">
        <form method="GET" action="{{ route('scholar-performance.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Search Scholar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="form-control w-full" placeholder="Name or username">
            </div>
            <div>
                <label class="form-label">Semester</label>
                <select name="semester" class="form-select w-full">
                    <option value="">All Semesters</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>
                            {{ $semester }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">School Year</label>
                <select name="school_year" class="form-select w-full">
                    <option value="">All Years</option>
                    @foreach($schoolYears as $year)
                        <option value="{{ $year }}" {{ request('school_year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Academic Status</label>
                <select name="academic_status" class="form-select w-full">
                    <option value="">All Status</option>
                    <option value="good" {{ request('academic_status') == 'good' ? 'selected' : '' }}>Good Standing</option>
                    <option value="warning" {{ request('academic_status') == 'warning' ? 'selected' : '' }}>Warning</option>
                    <option value="probation" {{ request('academic_status') == 'probation' ? 'selected' : '' }}>Probation</option>
                </select>
            </div>
            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="btn btn-primary mr-2">
                    <i data-lucide="search" class="w-4 h-4 mr-2"></i> Filter
                </button>
                <a href="{{ route('scholar-performance.index') }}" class="btn btn-outline-secondary">
                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Performance Records Table -->
<div class="intro-y box mt-5">
    <div class="flex items-center p-5 border-b border-slate-200/60">
        <h2 class="font-medium text-base mr-auto">Performance Records</h2>
    </div>
    <div class="p-5">
        <div class="overflow-x-auto">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">Scholar</th>
                        <th class="whitespace-nowrap">Semester</th>
                        <th class="whitespace-nowrap">GWA</th>
                        <th class="whitespace-nowrap">Units</th>
                        <th class="whitespace-nowrap">Subjects</th>
                        <th class="whitespace-nowrap">Status</th>
                        <th class="whitespace-nowrap">Requirements</th>
                        <th class="whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($performance as $record)
                        <tr class="intro-x">
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 image-fit zoom-in">
                                        <img alt="Scholar" class="tooltip rounded-full" 
                                             src="{{ asset('public/Images/normalpicture.png') }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium whitespace-nowrap">
                                            {{ $record->scholar->user->first_name }} {{ $record->scholar->user->last_name }}
                                        </div>
                                        <div class="text-slate-500 text-xs whitespace-nowrap">
                                            {{ $record->scholar->institution }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-slate-900">{{ $record->semester }}</div>
                                <div class="text-slate-500 text-xs">{{ $record->school_year }}</div>
                            </td>
                            <td>
                                <div class="font-medium">{{ number_format($record->gwa, 2) }}</div>
                                <div class="text-slate-500 text-xs">
                                    {{ $record->getCompletionRate() }}% completion
                                </div>
                            </td>
                            <td>
                                <div class="font-medium">{{ $record->units_completed }}/{{ $record->units_enrolled }}</div>
                                <div class="text-slate-500 text-xs">
                                    {{ $record->units_failed }} failed
                                </div>
                            </td>
                            <td>
                                <div class="font-medium">{{ $record->subjects_passed }}/{{ $record->subjects_enrolled }}</div>
                                <div class="text-slate-500 text-xs">
                                    {{ $record->subjects_failed }} failed, {{ $record->subjects_dropped }} dropped
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $record->getStatusBadgeClass() }}">
                                        <i data-lucide="{{ $record->academic_status === 'good' ? 'check' : ($record->academic_status === 'warning' ? 'alert-triangle' : 'x') }}" 
                                           class="w-4 h-4 text-white"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="font-medium">{{ $record->getStatusText() }}</div>
                                        <div class="text-slate-500 text-xs">
                                            {{ $record->submitted_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    @if($record->meets_retention_requirements)
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-success">
                                            <i data-lucide="check" class="w-4 h-4 text-white"></i>
                                        </div>
                                        <span class="ml-2 text-success text-sm">Meets</span>
                                    @else
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-danger">
                                            <i data-lucide="x" class="w-4 h-4 text-white"></i>
                                        </div>
                                        <span class="ml-2 text-danger text-sm">Doesn't Meet</span>
                                    @endif
                                </div>
                            </td>
                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <a href="{{ route('scholar-performance.show', $record->id) }}" 
                                       class="flex items-center mr-3 text-primary">
                                        <i data-lucide="eye" class="w-4 h-4 mr-1"></i> View
                                    </a>
                                    <a href="{{ route('scholar-performance.edit', $record->id) }}" 
                                       class="flex items-center mr-3 text-warning">
                                        <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Edit
                                    </a>
                                    <button type="button" class="flex items-center text-danger" 
                                            onclick="deleteRecord({{ $record->id }})">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-slate-500">
                                No performance records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-5">
            {{ $performance->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteRecord(id) {
    if (confirm('Are you sure you want to delete this performance record?')) {
        fetch(`/scholar-performance/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'Failed to delete record');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to delete record');
        });
    }
}
</script>
@endpush
@endsection 