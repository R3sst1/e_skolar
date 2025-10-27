<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Application Status</h2>
    @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
    <a href="{{ route('view.profile.other', $application->user_id) }}" target="_blank" class="btn btn-outline-primary ml-4">
        <i data-lucide="user" class="w-4 h-4 mr-1"></i> View Profile
    </a>
    <a href="{{ route('requirements.index', $application->id) }}" class="btn btn-outline-secondary ml-2">
        <i data-lucide="file-text" class="w-4 h-4 mr-1"></i> Review Documents
    </a>
    @endif
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- Application Timeline -->
    <div class="col-span-12 lg:col-span-4">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Application Timeline</h2>
            </div>
            <div class="p-5">
                <div class="relative">
                    <!-- Timeline Line -->
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-slate-200"></div>
                    
                    <!-- Timeline Items -->
                    <div class="space-y-4">
                        <!-- Submitted -->
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-success text-white flex items-center justify-center text-xs font-medium z-10 relative">
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </div>
                            <div class="ml-4">
                                <div class="font-medium">Application Submitted</div>
                                <div class="text-slate-500 text-xs">{{ $application->created_at->format('M d, Y g:i A') }}</div>
                            </div>
                        </div>

                        <!-- Under Review -->
                        @if($application->status === 'under_review' || $application->status === 'approved' || $application->status === 'rejected')
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-xs font-medium z-10 relative">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </div>
                            <div class="ml-4">
                                <div class="font-medium">Under Review</div>
                                <div class="text-slate-500 text-xs">{{ $application->reviewed_at ? $application->reviewed_at->format('M d, Y g:i A') : 'In Progress' }}</div>
                            </div>
                        </div>
                        @endif

                        <!-- Approved/Rejected -->
                        @if($application->status === 'approved')
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-success text-white flex items-center justify-center text-xs font-medium z-10 relative">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                            </div>
                            <div class="ml-4">
                                <div class="font-medium">Application Approved</div>
                                <div class="text-slate-500 text-xs">{{ $application->approved_at ? $application->approved_at->format('M d, Y g:i A') : 'Approved' }}</div>
                            </div>
                        </div>
                        @elseif($application->status === 'rejected')
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-danger text-white flex items-center justify-center text-xs font-medium z-10 relative">
                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                            </div>
                            <div class="ml-4">
                                <div class="font-medium">Application Rejected</div>
                                <div class="text-slate-500 text-xs">{{ $application->rejected_at ? $application->rejected_at->format('M d, Y g:i A') : 'Rejected' }}</div>
                            </div>
                        </div>
                        @elseif($application->status === 'needs_additional_requirements')
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-warning text-white flex items-center justify-center text-xs font-medium z-10 relative">
                                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                            </div>
                            <div class="ml-4">
                                <div class="font-medium">Additional Requirements Needed</div>
                                <div class="text-slate-500 text-xs">Please check admin feedback below</div>
                            </div>
                        </div>
                        @endif

                        <!-- Pending -->
                        @if($application->status === 'pending')
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-xs font-medium z-10 relative">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                            </div>
                            <div class="ml-4">
                                <div class="font-medium">Pending Review</div>
                                <div class="text-slate-500 text-xs">Your application is in queue</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Details -->
    <div class="col-span-12 lg:col-span-8">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Application #{{ $application->application_number }}</h2>
                <span class="px-2 py-1 rounded-full text-xs ml-2 
                    @if($application->status === 'pending') bg-warning text-white
                    @elseif($application->status === 'under_review') bg-primary text-white
                    @elseif($application->status === 'approved') bg-success text-white
                    @elseif($application->status === 'needs_additional_requirements') bg-warning text-white
                    @else bg-danger text-white @endif">
                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                </span>
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
                                <div class="mt-1">₱{{ number_format($application->family_income, 2) }}</div>
                            </div>
                            <div class="col-span-12">
                                <div class="text-slate-500">Reason for Application</div>
                                <div class="mt-1">{{ $application->reason_for_application }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Grade Photo -->
                    <div class="col-span-12 border-t pt-4">
                        <div class="font-medium text-base mb-3">Grade Photo</div>
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12">
                                @if($application->grade_photo)
                                    <div class="box p-4 bg-slate-50 border border-slate-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center">
                                                <div class="mr-3">
                                                    <i data-lucide="image" class="w-5 h-5 text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="font-medium">Grade Photo</div>
                                                    <div class="text-slate-500 text-xs mt-0.5">Uploaded on {{ $application->created_at->format('M d, Y g:i A') }}</div>
                                                </div>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="px-2 py-1 rounded-full text-xs bg-success text-white">
                                                    Uploaded
                                                </span>
                                                <a href="{{ Storage::url($application->grade_photo) }}" target="_blank" class="btn btn-outline-secondary py-1 px-2 ml-2">
                                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                                </a>
                                                <a href="{{ Storage::url($application->grade_photo) }}" download class="btn btn-outline-secondary py-1 px-2 ml-1">
                                                    <i data-lucide="download" class="w-4 h-4"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <img src="{{ Storage::url($application->grade_photo) }}" alt="Grade Photo" class="max-w-full h-auto max-h-64 rounded-lg border border-slate-200">
                                        </div>
                                    </div>
                                @else
                                    <div class="box p-4 bg-warning/10 border border-warning/20">
                                        <div class="flex items-center">
                                            <div class="mr-3">
                                                <i data-lucide="alert-circle" class="w-5 h-5 text-warning"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-warning">No Grade Photo Uploaded</div>
                                                <div class="text-slate-500 text-xs mt-0.5">Grade photo is required for application processing</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                    <div class="intro-y box mt-5">
                        <div class="flex items-center p-5 border-b border-slate-200/60">
                            <h2 class="font-medium text-base mr-auto">Admin Feedback / Additional Requirements</h2>
                        </div>
                        <div class="p-5">
                            <form method="POST" action="{{ route('applications.request-additional', $application->id) }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Feedback / Additional Requirements</label>
                                    <textarea name="admin_remarks" rows="4" class="form-control w-full" required>{{ old('admin_remarks', $application->admin_remarks) }}</textarea>
                                    @error('admin_remarks')
                                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="text-right mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i data-lucide="send" class="w-4 h-4 mr-2"></i> Send Feedback / Request
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    @if($application->admin_remarks)
                    <div class="intro-y box mt-5">
                        <div class="flex items-center p-5 border-b border-slate-200/60">
                            <h2 class="font-medium text-base mr-auto">Admin Feedback / Additional Requirements</h2>
                        </div>
                        <div class="p-5">
                            <div class="text-slate-600">{{ $application->admin_remarks }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>