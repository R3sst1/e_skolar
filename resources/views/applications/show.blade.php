@extends('layouts.app')
@section('title', 'Application Details')
@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Application Details</h2>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- Begin: Status Timeline -->
    <div class="col-span-12 lg:col-span-4">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Application Status</h2>
            </div>
            <div class="p-5">
                <div class="relative">
                    <!-- Vertical Line -->
                    <div class="absolute left-0 ml-[14px] w-px h-full bg-slate-200"></div>

                    <!-- Status Steps -->
                    <div class="relative flex items-center mb-8">
                        <div class="w-8 h-8 rounded-full bg-success flex items-center justify-center z-10">
                            <i data-lucide="check" class="w-4 h-4 text-white"></i>
                        </div>
                        <div class="ml-4">
                            <div class="font-medium">Submitted</div>
                            <div class="text-slate-500 text-xs mt-1">{{ $application->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                    </div>

                    <div class="relative flex items-center mb-8">
                        <div class="w-8 h-8 rounded-full {{ $application->isUnderReview() || $application->isApproved() || $application->isRejected() ? 'bg-success' : ($application->isPending() ? 'bg-warning' : 'bg-slate-200') }} flex items-center justify-center z-10">
                            <i data-lucide="{{ $application->isUnderReview() || $application->isApproved() || $application->isRejected() ? 'check' : ($application->isPending() ? 'clock' : 'loader') }}" class="w-4 h-4 {{ $application->isUnderReview() || $application->isApproved() || $application->isRejected() ? 'text-white' : ($application->isPending() ? 'text-white' : 'text-slate-500') }}"></i>
                        </div>
                        <div class="ml-4">
                            <div class="font-medium">Under Review</div>
                            @if($application->reviewed_at)
                                <div class="text-slate-500 text-xs mt-1">{{ $application->reviewed_at->format('M d, Y h:i A') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="relative flex items-center">
                        <div class="w-8 h-8 rounded-full {{ $application->isApproved() ? 'bg-success' : ($application->isRejected() ? 'bg-danger' : 'bg-slate-200') }} flex items-center justify-center z-10">
                            <i data-lucide="{{ $application->isApproved() ? 'check' : ($application->isRejected() ? 'x' : 'loader') }}" class="w-4 h-4 {{ $application->isApproved() || $application->isRejected() ? 'text-white' : 'text-slate-500' }}"></i>
                        </div>
                        <div class="ml-4">
                            <div class="font-medium">{{ $application->isApproved() ? 'Approved' : ($application->isRejected() ? 'Rejected' : 'Decision') }}</div>
                            @if($application->approved_at)
                                <div class="text-slate-500 text-xs mt-1">{{ $application->approved_at->format('M d, Y h:i A') }}</div>
                            @elseif($application->rejected_at)
                                <div class="text-slate-500 text-xs mt-1">{{ $application->rejected_at->format('M d, Y h:i A') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($application->admin_remarks)
                <div class="bg-slate-50 p-4 rounded-md mt-6">
                    <div class="font-medium">Admin Remarks</div>
                    <div class="text-slate-500 mt-2">{{ $application->admin_remarks }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- End: Status Timeline -->

    <!-- Begin: Application Details -->
    <div class="col-span-12 lg:col-span-8">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Application #{{ $application->application_number }}</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-12 gap-x-6 gap-y-4">
                    <!-- Educational Information -->
                    <div class="col-span-12">
                        <div class="font-medium text-base mb-3">Educational Information</div>
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 sm:col-span-6">
                                <div class="text-slate-500">School</div>
                                <div class="mt-1">{{ $application->school }}</div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="text-slate-500">Course</div>
                                <div class="mt-1">{{ $application->course }}</div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <div class="text-slate-500">Year Level</div>
                                <div class="mt-1">{{ $application->year_level }}</div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <div class="text-slate-500">Semester</div>
                                <div class="mt-1">{{ $application->semester }}</div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <div class="text-slate-500">School Year</div>
                                <div class="mt-1">{{ $application->school_year }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="col-span-12 border-t pt-4">
                        <div class="font-medium text-base mb-3">Academic Information</div>
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 sm:col-span-6">
                                <div class="text-slate-500">General Weighted Average (GWA)</div>
                                <div class="mt-1">{{ number_format($application->gwa, 2) }}</div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="text-slate-500">Family Monthly Income</div>
                                <div class="mt-1">{{ $application->family_income }}</div>
                            </div>
                            <div class="col-span-12">
                                <div class="text-slate-500">Reason for Application</div>
                                <div class="mt-1">{{ $application->reason_for_application }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Requirements -->
                    <div class="col-span-12 border-t pt-4">
                        <div class="font-medium text-base mb-3">Requirements</div>
                        <div class="grid grid-cols-12 gap-4">
                            @foreach($application->requirements as $requirement)
                            <div class="col-span-12 sm:col-span-6">
                                <div class="box p-4 {{ $requirement->isApproved() ? 'bg-success/10' : ($requirement->isRejected() ? 'bg-danger/10' : 'bg-slate-50') }}">
                                    <div class="flex items-center">
                                        <div class="mr-auto">
                                            <div class="font-medium">{{ ucwords(str_replace('_', ' ', $requirement->name)) }}</div>
                                            <div class="text-slate-500 text-xs mt-0.5">{{ $requirement->getFileSize() }}</div>
                                        </div>
                                        <div class="flex items-center">
                                            @if($requirement->isApproved())
                                                <i data-lucide="check-circle" class="w-4 h-4 text-success"></i>
                                            @elseif($requirement->isRejected())
                                                <i data-lucide="x-circle" class="w-4 h-4 text-danger"></i>
                                            @else
                                                <i data-lucide="clock" class="w-4 h-4 text-slate-500"></i>
                                            @endif
                                            <a href="{{ route('requirements.download', $requirement) }}" class="btn btn-outline-secondary py-1 px-2 ml-2">
                                                <i data-lucide="download" class="w-4 h-4"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @if($requirement->remarks)
                                    <div class="text-danger text-xs mt-2">{{ $requirement->remarks }}</div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End: Application Details -->
</div>

<!-- Begin: Action Buttons -->
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <button type="button" onclick="window.history.back()" class="btn btn-primary shadow-md mr-2">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Back to List
        </button>
        @if($application->isPending())
        <button type="button" 
                onclick="event.preventDefault(); document.getElementById('cancel-form').submit();"
                class="btn btn-danger shadow-md">
            <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
            Cancel Application
        </button>
        <form id="cancel-form" 
              action="{{ route('applications.cancel', $application) }}" 
              method="POST" 
              class="hidden">
            @csrf
            @method('DELETE')
        </form>
        @endif
    </div>
</div>
<!-- End: Action Buttons -->
@endsection 