@extends('layouts.app')

@section('title', 'My Applications')

@section('content')
<div class="intro-y flex items-center mt-8">
    
    @if(!Auth::user()->applications()->whereIn('status', ['pending', 'under_review'])->exists())
    <div class="ml-auto flex">
        <a href="{{ route('applications.create') }}" class="btn btn-primary shadow-md">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            New Application
        </a>
    </div>
    @endif
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12">
        <div class="intro-y box">
            @if($applications->isEmpty())
            <div class="p-5 text-center">
                <div class="mb-4">
                    <i data-lucide="file-text" class="w-12 h-12 mx-auto text-slate-400"></i>
                </div>
                <div class="text-slate-500 mb-4">No applications found</div>
                <a href="{{ route('applications.create') }}" class="btn btn-primary">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Apply Now
                </a>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">Application #</th>
                            <th class="whitespace-nowrap">School</th>
                            <th class="whitespace-nowrap">Course</th>
                            <th class="whitespace-nowrap">Status</th>
                            <th class="whitespace-nowrap">Submitted</th>
                            <th class="whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                        <tr>
                            <td>
                                <div class="font-medium">{{ $application->application_number }}</div>
                                @if($application->scholarship)
                                <div class="text-slate-500 text-xs">{{ $application->scholarship->name }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="font-medium">{{ $application->school }}</div>
                                <div class="text-slate-500 text-xs">{{ $application->year_level }} - {{ $application->semester }}</div>
                            </td>
                            <td>{{ $application->course }}</td>
                            <td>
                                <div class="flex items-center">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        @if($application->status === 'pending') bg-warning text-white
                                        @elseif($application->status === 'under_review') bg-primary text-white
                                        @elseif($application->status === 'approved') bg-success text-white
                                        @elseif($application->status === 'needs_additional_requirements') bg-warning text-white
                                        @else bg-danger text-white @endif">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                    @if($application->status === 'pending')
                                        <i data-lucide="clock" class="w-4 h-4 ml-1 text-warning"></i>
                                    @elseif($application->status === 'under_review')
                                        <i data-lucide="eye" class="w-4 h-4 ml-1 text-primary"></i>
                                    @elseif($application->status === 'approved')
                                        <i data-lucide="check-circle" class="w-4 h-4 ml-1 text-success"></i>
                                    @elseif($application->status === 'needs_additional_requirements')
                                        <i data-lucide="alert-circle" class="w-4 h-4 ml-1 text-warning"></i>
                                    @else
                                        <i data-lucide="x-circle" class="w-4 h-4 ml-1 text-danger"></i>
                                    @endif
                                </div>
                                @if($application->status === 'needs_additional_requirements')
                                <div class="text-warning text-xs mt-1">Additional requirements needed</div>
                                @endif
                            </td>
                            <td>
                                <div class="font-medium">{{ $application->created_at->format('M d, Y') }}</div>
                                <div class="text-slate-500 text-xs">{{ $application->created_at->format('g:i A') }}</div>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <a href="{{ route('applications.status', $application->id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                        View
                                    </a>
                                    @if($application->status === 'needs_additional_requirements')
                                    <a href="{{ route('applications.create') }}" 
                                       class="btn btn-sm btn-warning ml-1">
                                        <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                                        Update
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-5">
                {{ $applications->links() }}
            </div>
            @endif
        </div>
    </div>
    <!-- END: Data List -->
</div>

<!-- Mobile Cards View (hidden on desktop) -->
<div class="lg:hidden mt-5">
     <div class="intro-y box p-5">
        @foreach($applications as $application)
        
            <div class="flex items-center justify-between mb-3">
                <div>
                    <div class="font-medium">{{ $application->application_number }}</div>
                    @if($application->scholarship)
                    <div class="text-slate-500 text-xs">{{ $application->scholarship->name }}</div>
                    @endif
                </div>
                <span class="px-2 py-1 rounded-full text-xs 
                    @if($application->status === 'pending') bg-warning text-white
                    @elseif($application->status === 'under_review') bg-primary text-white
                    @elseif($application->status === 'approved') bg-success text-white
                    @elseif($application->status === 'needs_additional_requirements') bg-warning text-white
                    @else bg-danger text-white @endif">
                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                </span>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-3">
                <div>
                    <div class="text-slate-500 text-xs">School</div>
                    <div class="font-medium text-sm">{{ $application->school }}</div>
                </div>
                <div>
                    <div class="text-slate-500 text-xs">Course</div>
                    <div class="font-medium text-sm">{{ $application->course }}</div>
                </div>
                <div>
                    <div class="text-slate-500 text-xs">Year Level</div>
                    <div class="font-medium text-sm">{{ $application->year_level }}</div>
                </div>
                <div>
                    <div class="text-slate-500 text-xs">Submitted</div>
                    <div class="font-medium text-sm">{{ $application->created_at->format('M d, Y') }}</div>
                </div>
            </div>

            @if($application->status === 'needs_additional_requirements')
            <div class="bg-warning/10 border border-warning/20 rounded p-2 mb-3">
                <div class="flex items-center">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-warning mr-2"></i>
                    <span class="text-warning text-xs">Additional requirements needed</span>
                </div>
            </div>
            @endif

            <div class="flex items-center justify-between">
                <a href="{{ route('applications.status', $application->id) }}" 
                class="btn btn-sm btn-primary">
                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                    View Details
                </a>
                @if($application->status === 'needs_additional_requirements')
                <a href="{{ route('applications.create') }}" 
                class="btn btn-sm btn-warning">
                    <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                    Update
                </a>
                @endif
            </div>
        
        @endforeach
    </div>
</div>
@endsection 