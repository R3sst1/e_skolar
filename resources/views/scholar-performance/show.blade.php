@extends('layouts.app')
@section('title', 'Performance Details')
@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Performance Details</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('scholar-performance.edit', $performance->id) }}" class="btn btn-warning shadow-md mr-2">
            <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Edit
        </a>
        <a href="{{ route('scholar-performance.index') }}" class="btn btn-outline-secondary shadow-md">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
        </a>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- Scholar Information -->
    <div class="intro-y col-span-12 lg:col-span-4">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Scholar Information</h2>
            </div>
            <div class="p-5">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 image-fit zoom-in">
                        <img alt="Scholar" class="tooltip rounded-full" 
                             src="{{ asset('public/Images/normalpicture.png') }}">
                    </div>
                    <div class="ml-4">
                        <div class="font-medium text-lg">
                            {{ $performance->scholar->user->first_name }} {{ $performance->scholar->user->last_name }}
                        </div>
                        <div class="text-slate-500">{{ $performance->scholar->user->username }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <div class="text-slate-500 text-xs">Institution</div>
                        <div class="font-medium">{{ $performance->scholar->institution }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Course</div>
                        <div class="font-medium">{{ $performance->scholar->course }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Year Level</div>
                        <div class="font-medium">{{ $performance->scholar->year_level }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Barangay</div>
                        <div class="font-medium">{{ $performance->scholar->barangay }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Summary -->
    <div class="intro-y col-span-12 lg:col-span-8">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Performance Summary</h2>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $performance->getStatusBadgeClass() }}">
                        <i data-lucide="{{ $performance->academic_status === 'good' ? 'check' : ($performance->academic_status === 'warning' ? 'alert-triangle' : 'x') }}" 
                           class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="ml-3">
                        <div class="font-medium">{{ $performance->getStatusText() }}</div>
                    </div>
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-slate-500 text-xs">Semester & School Year</div>
                        <div class="font-medium text-lg">{{ $performance->semester }} {{ $performance->school_year }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Grade Weighted Average (GWA)</div>
                        <div class="font-medium text-lg">{{ number_format($performance->gwa, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Units Completed</div>
                        <div class="font-medium text-lg">{{ $performance->units_completed }}/{{ $performance->units_enrolled }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Subjects Passed</div>
                        <div class="font-medium text-lg">{{ $performance->subjects_passed }}/{{ $performance->subjects_enrolled }}</div>
                    </div>
                </div>

                <!-- Progress Bars -->
                <div class="mt-6">
                    <div class="mb-4">
                        <div class="flex justify-between text-xs mb-1">
                            <span>Units Completion Rate</span>
                            <span>{{ $performance->getCompletionRate() }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full" style="width: {{ $performance->getCompletionRate() }}%"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="flex justify-between text-xs mb-1">
                            <span>Subject Pass Rate</span>
                            <span>{{ $performance->getPassRate() }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-success h-2 rounded-full" style="width: {{ $performance->getPassRate() }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Breakdown -->
    <div class="intro-y col-span-12">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Detailed Breakdown</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Units Information -->
                    <div>
                        <h3 class="font-medium text-base mb-3">Units Information</h3>
                        <div class="grid grid-cols-1 gap-3">
                            <div class="flex justify-between p-3 bg-slate-50 rounded-md">
                                <span class="text-slate-600">Units Enrolled</span>
                                <span class="font-medium">{{ $performance->units_enrolled }}</span>
                            </div>
                            <div class="flex justify-between p-3 bg-success/10 rounded-md">
                                <span class="text-success">Units Completed</span>
                                <span class="font-medium text-success">{{ $performance->units_completed }}</span>
                            </div>
                            <div class="flex justify-between p-3 bg-danger/10 rounded-md">
                                <span class="text-danger">Units Failed</span>
                                <span class="font-medium text-danger">{{ $performance->units_failed }}</span>
                            </div>
                            <div class="flex justify-between p-3 bg-slate-50 rounded-md">
                                <span class="text-slate-600">Units Remaining</span>
                                <span class="font-medium">{{ $performance->getUnitsRemaining() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Subjects Information -->
                    <div>
                        <h3 class="font-medium text-base mb-3">Subjects Information</h3>
                        <div class="grid grid-cols-1 gap-3">
                            <div class="flex justify-between p-3 bg-slate-50 rounded-md">
                                <span class="text-slate-600">Subjects Enrolled</span>
                                <span class="font-medium">{{ $performance->subjects_enrolled }}</span>
                            </div>
                            <div class="flex justify-between p-3 bg-success/10 rounded-md">
                                <span class="text-success">Subjects Passed</span>
                                <span class="font-medium text-success">{{ $performance->subjects_passed }}</span>
                            </div>
                            <div class="flex justify-between p-3 bg-danger/10 rounded-md">
                                <span class="text-danger">Subjects Failed</span>
                                <span class="font-medium text-danger">{{ $performance->subjects_failed }}</span>
                            </div>
                            <div class="flex justify-between p-3 bg-warning/10 rounded-md">
                                <span class="text-warning">Subjects Dropped</span>
                                <span class="font-medium text-warning">{{ $performance->subjects_dropped }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Requirements Check -->
    <div class="intro-y col-span-12">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Retention Requirements Check</h2>
                <div class="flex items-center">
                    @if($performance->meets_retention_requirements)
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-success mr-2">
                            <i data-lucide="check" class="w-4 h-4 text-white"></i>
                        </div>
                        <span class="text-success font-medium">Meets Requirements</span>
                    @else
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-danger mr-2">
                            <i data-lucide="x" class="w-4 h-4 text-white"></i>
                        </div>
                        <span class="text-danger font-medium">Doesn't Meet Requirements</span>
                    @endif
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-center p-4 {{ $performance->meetsGWARequirement() ? 'bg-success/10' : 'bg-danger/10' }} rounded-md">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $performance->meetsGWARequirement() ? 'bg-success' : 'bg-danger' }} mr-3">
                            <i data-lucide="{{ $performance->meetsGWARequirement() ? 'check' : 'x' }}" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <div class="font-medium">GWA Requirement</div>
                            <div class="text-sm {{ $performance->meetsGWARequirement() ? 'text-success' : 'text-danger' }}">
                                {{ number_format($performance->gwa, 2) }} 
                                {{ $performance->meetsGWARequirement() ? '≤' : '>' }} 
                                {{ \App\Models\SystemSetting::getValue('minimum_gwa_for_retention', 2.5) }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center p-4 {{ $performance->meetsUnitsRequirement() ? 'bg-success/10' : 'bg-danger/10' }} rounded-md">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $performance->meetsUnitsRequirement() ? 'bg-success' : 'bg-danger' }} mr-3">
                            <i data-lucide="{{ $performance->meetsUnitsRequirement() ? 'check' : 'x' }}" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <div class="font-medium">Units Requirement</div>
                            <div class="text-sm {{ $performance->meetsUnitsRequirement() ? 'text-success' : 'text-danger' }}">
                                {{ $performance->units_completed }} 
                                {{ $performance->meetsUnitsRequirement() ? '≥' : '<' }} 
                                {{ \App\Models\SystemSetting::getValue('minimum_units_per_semester', 12) }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center p-4 {{ $performance->meetsNoFailedSubjectsRequirement() ? 'bg-success/10' : 'bg-danger/10' }} rounded-md">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $performance->meetsNoFailedSubjectsRequirement() ? 'bg-success' : 'bg-danger' }} mr-3">
                            <i data-lucide="{{ $performance->meetsNoFailedSubjectsRequirement() ? 'check' : 'x' }}" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <div class="font-medium">No Failed Subjects</div>
                            <div class="text-sm {{ $performance->meetsNoFailedSubjectsRequirement() ? 'text-success' : 'text-danger' }}">
                                {{ $performance->subjects_failed }} failed subjects
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Remarks -->
    @if($performance->academic_remarks)
    <div class="intro-y col-span-12">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Academic Remarks</h2>
            </div>
            <div class="p-5">
                <div class="text-slate-600">{{ $performance->academic_remarks }}</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Record Information -->
    <div class="intro-y col-span-12">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Record Information</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-slate-500 text-xs">Submitted At</div>
                        <div class="font-medium">{{ $performance->submitted_at->format('M d, Y g:i A') }}</div>
                    </div>
                    @if($performance->reviewed_at)
                    <div>
                        <div class="text-slate-500 text-xs">Reviewed At</div>
                        <div class="font-medium">{{ $performance->reviewed_at->format('M d, Y g:i A') }}</div>
                    </div>
                    @endif
                    @if($performance->reviewer)
                    <div>
                        <div class="text-slate-500 text-xs">Reviewed By</div>
                        <div class="font-medium">{{ $performance->reviewer->first_name }} {{ $performance->reviewer->last_name }}</div>
                    </div>
                    @endif
                    <div>
                        <div class="text-slate-500 text-xs">Last Updated</div>
                        <div class="font-medium">{{ $performance->updated_at->format('M d, Y g:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 