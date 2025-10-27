@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Welcome back, {{ Auth::user()->first_name }}!
        </h2>
    </div>
@endsection

@section('content')
    @if(session('show_profile_modal'))
        <x-modal name="registration-success-modal" :show="true">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Registration Successful!</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Would you like to set up your profile now?</p>
                <div class="mt-6 flex justify-end gap-4">
                    <form method="GET" action="{{ route('profile.edit') }}">
                        <x-primary-button>Yes</x-primary-button>
                    </form>
                    <form method="GET" action="{{ route('dashboard') }}">
                        <x-secondary-button>No</x-secondary-button>
                    </form>
                </div>
            </div>
        </x-modal>
    @endif

    @if(Auth::user()->isApplicant())
    <!-- Applicant Dashboard -->
    <div class="grid grid-cols-12 gap-6 mt-5">
        <!-- Quick Stats -->
        <div class="col-span-12 lg:col-span-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="intro-y box p-5">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                            <i data-lucide="file-text" class="w-6 h-6 text-primary"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-2xl font-bold">{{ Auth::user()->applications()->count() }}</div>
                            <div class="text-slate-500 text-sm">Total Applications</div>
                        </div>
                    </div>
                </div>
                
                <div class="intro-y box p-5">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-success/10 rounded-lg flex items-center justify-center">
                            <i data-lucide="check-circle" class="w-6 h-6 text-success"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-2xl font-bold">{{ Auth::user()->applications()->where('status', 'approved')->count() }}</div>
                            <div class="text-slate-500 text-sm">Approved</div>
                        </div>
                    </div>
                </div>
                
                <div class="intro-y box p-5">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-warning/10 rounded-lg flex items-center justify-center">
                            <i data-lucide="clock" class="w-6 h-6 text-warning"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-2xl font-bold">{{ Auth::user()->unreadNotifications()->count() }}</div>
                            <div class="text-slate-500 text-sm">Unread Notifications</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-span-12 lg:col-span-4">
            <div class="intro-y box p-5">
                <h3 class="font-medium text-base mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @if(!Auth::user()->applications()->whereIn('status', ['pending', 'under_review'])->exists())
                    <a href="{{ route('applications.create') }}" class="btn btn-primary w-full">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Apply for Scholarship
                    </a>
                    @endif
                    
                    <a href="{{ route('applications.index') }}" class="btn btn-outline-secondary w-full">
                        <i data-lucide="file-text" class="w-4 h-4 mr-2"></i>
                        View My Applications
                    </a>
                    
                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary w-full">
                        <i data-lucide="bell" class="w-4 h-4 mr-2"></i>
                        View Notifications
                        @if(Auth::user()->unreadNotifications()->count() > 0)
                        <span class="ml-auto bg-danger text-white text-xs px-2 py-1 rounded-full">
                            {{ Auth::user()->unreadNotifications()->count() }}
                        </span>
                        @endif
                    </a>
                    
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary w-full">
                        <i data-lucide="user" class="w-4 h-4 mr-2"></i>
                        Update Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Applications -->
        <div class="col-span-12">
            <div class="intro-y box">
                <div class="flex items-center p-5 border-b border-slate-200/60">
                    <h3 class="font-medium text-base mr-auto">Recent Applications</h3>
                    <a href="{{ route('applications.index') }}" class="btn btn-outline-secondary btn-sm">
                        View All
                    </a>
                </div>
                <div class="p-5">
                    @php
                        $recentApplications = Auth::user()->applications()->latest()->take(3)->get();
                    @endphp
                    
                    @if($recentApplications->isEmpty())
                    <div class="text-center py-8">
                        <i data-lucide="file-text" class="w-12 h-12 mx-auto text-slate-400 mb-4"></i>
                        <div class="text-slate-500">No applications yet</div>
                        <a href="{{ route('applications.create') }}" class="btn btn-primary mt-3">
                            Apply Now
                        </a>
                    </div>
                    @else
                    <div class="space-y-4">
                        @foreach($recentApplications as $application)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mr-3">
                                    <i data-lucide="file-text" class="w-5 h-5 text-primary"></i>
                                </div>
                                <div>
                                    <div class="font-medium">{{ $application->application_number }}</div>
                                    <div class="text-slate-500 text-sm">{{ $application->school }} - {{ $application->course }}</div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="px-2 py-1 rounded-full text-xs 
                                    @if($application->status === 'pending') bg-warning text-white
                                    @elseif($application->status === 'under_review') bg-primary text-white
                                    @elseif($application->status === 'approved') bg-success text-white
                                    @elseif($application->status === 'needs_additional_requirements') bg-warning text-white
                                    @else bg-danger text-white @endif">
                                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                </span>
                                <a href="{{ route('applications.status', $application->id) }}" class="btn btn-sm btn-outline-secondary ml-3">
                                    View
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Notifications -->
        <div class="col-span-12">
            <div class="intro-y box">
                <div class="flex items-center p-5 border-b border-slate-200/60">
                    <h3 class="font-medium text-base mr-auto">Recent Notifications</h3>
                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary btn-sm">
                        View All
                    </a>
                </div>
                <div class="p-5">
                    @php
                        $recentNotifications = Auth::user()->notifications()->latest()->take(3)->get();
                    @endphp
                    
                    @if($recentNotifications->isEmpty())
                    <div class="text-center py-8">
                        <i data-lucide="bell" class="w-12 h-12 mx-auto text-slate-400 mb-4"></i>
                        <div class="text-slate-500">No notifications yet</div>
                    </div>
                    @else
                    <div class="space-y-4">
                        @foreach($recentNotifications as $notification)
                        <div class="flex items-start p-4 {{ is_null($notification->read_at) ? 'bg-primary/5 border-primary/20' : 'bg-slate-50 border-slate-200' }} border rounded-lg">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full {{ is_null($notification->read_at) ? 'bg-primary text-white' : 'bg-slate-200 text-slate-500' }} mr-3">
                                <i data-lucide="bell" class="w-4 h-4"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="font-medium text-sm">{{ $notification->data['title'] ?? 'Application Update' }}</h4>
                                    <small class="text-slate-500">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="text-slate-600 text-sm">{{ $notification->data['message'] ?? 'There has been an update to your application.' }}</p>
                                @if(isset($notification->data['application_id']))
                                <a href="{{ route('applications.status', $notification->data['application_id']) }}" class="text-primary text-xs hover:underline">
                                    View Application →
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @elseif(Auth::user()->isSuperAdmin())
    <!-- Super Admin Analytics Dashboard -->
    <div class="grid grid-cols-12 gap-6 mt-5">
        <!-- Overview Statistics -->
        <div class="col-span-12 lg:col-span-3">
            <div class="intro-y box p-5">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                        <i data-lucide="users" class="w-6 h-6 text-primary"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold">{{ \App\Models\Scholar::count() }}</div>
                        <div class="text-slate-500 text-sm">Total Scholars</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-span-12 lg:col-span-3">
            <div class="intro-y box p-5">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-success/10 rounded-lg flex items-center justify-center">
                        <i data-lucide="user-check" class="w-6 h-6 text-success"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold">{{ \App\Models\Scholar::where('status', 'active')->count() }}</div>
                        <div class="text-slate-500 text-sm">Active Scholars</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-span-12 lg:col-span-3">
            <div class="intro-y box p-5">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-warning/10 rounded-lg flex items-center justify-center">
                        <i data-lucide="graduation-cap" class="w-6 h-6 text-warning"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold">{{ \App\Models\Scholar::where('status', 'graduated')->count() }}</div>
                        <div class="text-slate-500 text-sm">Graduated</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-span-12 lg:col-span-3">
            <div class="intro-y box p-5">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-danger/10 rounded-lg flex items-center justify-center">
                        <i data-lucide="credit-card" class="w-6 h-6 text-danger"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold">₱{{ number_format(\App\Models\DisbursementBatch::sum('total_amount') ?? 0, 2) }}</div>
                        <div class="text-slate-500 text-sm">Total Disbursed</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Cards -->
        <div class="col-span-12 lg:col-span-6">
            <div class="intro-y box p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium text-base">Scholar Statistics</h3>
                    <a href="{{ route('super-admin.scholar-stats') }}" class="btn btn-outline-secondary btn-sm">
                        <i data-lucide="external-link" class="w-4 h-4 mr-1"></i> View Details
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary">{{ \App\Models\Scholar::where('status', 'active')->count() }}</div>
                        <div class="text-slate-500 text-sm">Active</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-success">{{ \App\Models\Scholar::where('status', 'graduated')->count() }}</div>
                        <div class="text-slate-500 text-sm">Graduated</div>
                    </div>
                </div>
                <div class="mt-4">
                    @php
                        $totalScholars = \App\Models\Scholar::count();
                        $activeScholars = \App\Models\Scholar::where('status', 'active')->count();
                        $retentionRate = $totalScholars > 0 ? ($activeScholars / $totalScholars) * 100 : 0;
                    @endphp
                    <div class="flex items-center justify-between text-sm">
                        <span>Retention Rate</span>
                        <span class="font-medium">{{ number_format($retentionRate, 1) }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2 mt-1">
                        <div class="bg-primary h-2 rounded-full" style="width: {{ $retentionRate }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-6">
            <div class="intro-y box p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium text-base">Disbursement Analytics</h3>
                    <a href="{{ route('super-admin.disbursement-stats') }}" class="btn btn-outline-secondary btn-sm">
                        <i data-lucide="external-link" class="w-4 h-4 mr-1"></i> View Details
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary">{{ \App\Models\DisbursementBatch::count() }}</div>
                        <div class="text-slate-500 text-sm">Total Batches</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-success">₱{{ number_format(\App\Models\DisbursementBatch::sum('total_amount') ?? 0, 0) }}</div>
                        <div class="text-slate-500 text-sm">Total Amount</div>
                    </div>
                </div>
                <div class="mt-4">
                    @php
                        $pendingBatches = \App\Models\DisbursementBatch::where('status', 'pending')->count();
                        $disbursedBatches = \App\Models\DisbursementBatch::where('status', 'disbursed')->count();
                    @endphp
                    <div class="flex items-center justify-between text-sm">
                        <span>Disbursement Status</span>
                        <span class="font-medium">{{ $disbursedBatches }}/{{ \App\Models\DisbursementBatch::count() }} Completed</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Retention Analysis -->
        <div class="col-span-12">
            <div class="intro-y box p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium text-base">Retention Analysis</h3>
                    <a href="{{ route('super-admin.retention-stats') }}" class="btn btn-outline-secondary btn-sm">
                        <i data-lucide="external-link" class="w-4 h-4 mr-1"></i> View Details
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary">{{ \App\Models\Scholar::where('status', 'active')->count() }}</div>
                        <div class="text-slate-500 text-sm">Currently Active</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-success">{{ \App\Models\Scholar::where('status', 'graduated')->count() }}</div>
                        <div class="text-slate-500 text-sm">Successfully Graduated</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-warning">{{ \App\Models\Scholar::where('status', 'discontinued')->count() }}</div>
                        <div class="text-slate-500 text-sm">Discontinued</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-span-12">
            <div class="intro-y box p-5">
                <h3 class="font-medium text-base mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <a href="{{ route('super-admin.reports') }}" class="btn btn-outline-primary">
                        <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Generate Reports
                    </a>
                    <a href="{{ route('disbursements.index') }}" class="btn btn-outline-secondary">
                        <i data-lucide="credit-card" class="w-4 h-4 mr-2"></i> Manage Disbursements
                    </a>
                    <a href="{{ route('scholars') }}" class="btn btn-outline-success">
                        <i data-lucide="users" class="w-4 h-4 mr-2"></i> View Scholars
                    </a>
                    <a href="{{ route('system-settings.index') }}" class="btn btn-outline-warning">
                        <i data-lucide="settings" class="w-4 h-4 mr-2"></i> System Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Default Dashboard for Admin -->
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="col-span-12">
            <div class="intro-y box p-5">
                <h3 class="font-medium text-base mb-4">Welcome to SureScholarShip</h3>
                <p class="text-slate-600">Use the sidebar navigation to access different features of the system.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Profile Setup Modal -->
    @if(session('show_profile_setup_modal'))
    <div id="profile-setup-modal" class="modal" tabindex="-1" aria-hidden="true" data-tw-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-lucide="user-check" class="w-16 h-16 text-primary mx-auto mt-3"></i>
                        <div class="text-3xl mt-5">Welcome to SureScholarShip!</div>
                        <div class="text-slate-500 mt-2">
                            Would you like to set up your profile now?
                            <br>
                            This will help us process your scholarship application faster.
                        </div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <form id="skip-profile-form" action="{{ route('dashboard') }}" method="GET" class="inline">
                            <button type="submit" class="btn btn-outline-secondary w-24 mr-1">
                                Skip
                            </button>
                        </form>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary w-24">
                            Set Up Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
    @if(session('show_profile_setup_modal'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = tailwind.Modal.getOrCreateInstance(document.querySelector("#profile-setup-modal"));
            modal.show();
        });
    </script>
    @endif
    @endpush
    
@endsection