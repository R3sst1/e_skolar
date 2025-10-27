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
        <!-- Key Metrics -->
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
                        <div class="text-2xl font-bold">₱{{ number_format(\App\Models\DisbursementBatch::sum('total_amount') ?? 0, 0) }}</div>
                        <div class="text-slate-500 text-sm">Total Disbursed</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="col-span-12 lg:col-span-6">
            <div class="intro-y box p-5">
                <h3 class="font-medium text-base mb-4">Scholar Status Distribution</h3>
                <div class="relative h-64">
                    <canvas id="scholarStatusChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-6">
            <div class="intro-y box p-5">
                <h3 class="font-medium text-base mb-4">Application Status Overview</h3>
                <div class="relative h-64">
                    <canvas id="applicationStatusChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-6">
            <div class="intro-y box p-5">
                <h3 class="font-medium text-base mb-4">Monthly Disbursements</h3>
                <div class="relative h-64">
                    <canvas id="monthlyDisbursementChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-6">
            <div class="intro-y box p-5">
                <h3 class="font-medium text-base mb-4">Retention Rate Trend</h3>
                <div class="relative h-64">
                    <canvas id="retentionTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Scholar Statistics -->
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

        <!-- Disbursement Analytics -->
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

        <!-- Application Statistics -->
        <div class="col-span-12 lg:col-span-6">
            <div class="intro-y box p-5">
                <h3 class="font-medium text-base mb-4">Application Statistics</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary">{{ \App\Models\Application::count() }}</div>
                        <div class="text-slate-500 text-sm">Total Applications</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-success">{{ \App\Models\Application::where('status', 'approved')->count() }}</div>
                        <div class="text-slate-500 text-sm">Approved</div>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <div class="text-lg font-bold text-warning">{{ \App\Models\Application::where('status', 'pending')->count() }}</div>
                        <div class="text-slate-500 text-sm">Pending</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-danger">{{ \App\Models\Application::where('status', 'rejected')->count() }}</div>
                        <div class="text-slate-500 text-sm">Rejected</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Retention Analysis -->
        <div class="col-span-12 lg:col-span-6">
            <div class="intro-y box p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium text-base">Retention Analysis</h3>
                    <a href="{{ route('super-admin.retention-stats') }}" class="btn btn-outline-secondary btn-sm">
                        <i data-lucide="external-link" class="w-4 h-4 mr-1"></i> View Details
                    </a>
                </div>
                <div class="flex flex-col lg:flex-row gap-6 items-center justify-center">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary">{{ \App\Models\Scholar::where('status', 'active')->count() }}</div>
                        <div class="text-slate-500 text-sm font-medium">Currently Active</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-success">{{ \App\Models\Scholar::where('status', 'graduated')->count() }}</div>
                        <div class="text-slate-500 text-sm font-medium">Successfully Graduated</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-warning">{{ \App\Models\Scholar::where('status', 'discontinued')->count() }}</div>
                        <div class="text-slate-500 text-sm font-medium">Discontinued</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Institution Breakdown -->
        @php
            $institutionStats = \App\Models\Scholar::select('institution')
                ->selectRaw('COUNT(*) as total')
                ->selectRaw('SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active')
                ->selectRaw('SUM(CASE WHEN status = "graduated" THEN 1 ELSE 0 END) as graduated')
                ->groupBy('institution')
                ->get();
        @endphp
        @if($institutionStats->count() > 0)
        <div class="col-span-12">
            <div class="intro-y box p-5">
                <h3 class="font-medium text-base mb-4">Institution Breakdown</h3>
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Institution</th>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Total</th>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Active</th>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Graduated</th>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Success Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($institutionStats as $institution)
                            <tr>
                                <td class="border-b dark:border-dark-5">{{ $institution->institution }}</td>
                                <td class="border-b dark:border-dark-5">{{ $institution->total }}</td>
                                <td class="border-b dark:border-dark-5">{{ $institution->active }}</td>
                                <td class="border-b dark:border-dark-5">{{ $institution->graduated }}</td>
                                <td class="border-b dark:border-dark-5">
                                    @php
                                        $successRate = $institution->total > 0 ? (($institution->active + $institution->graduated) / $institution->total) * 100 : 0;
                                    @endphp
                                    <span class="font-medium">{{ number_format($successRate, 1) }}%</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Activity -->
        <div class="col-span-12 lg:col-span-6">
            <div class="intro-y box p-5">
                <h3 class="font-medium text-base mb-4">Recent Applications</h3>
                @php
                    $recentApplications = \App\Models\Application::with('user')->latest()->take(5)->get();
                @endphp
                @if($recentApplications->count() > 0)
                <div class="space-y-3">
                    @foreach($recentApplications as $application)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <div>
                            <div class="font-medium">{{ $application->user->first_name }} {{ $application->user->last_name }}</div>
                            <div class="text-slate-500 text-sm">{{ $application->school }} - {{ $application->course }}</div>
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs 
                            @if($application->status === 'pending') bg-warning text-white
                            @elseif($application->status === 'approved') bg-success text-white
                            @elseif($application->status === 'rejected') bg-danger text-white
                            @else bg-primary text-white @endif">
                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i data-lucide="file-text" class="w-12 h-12 mx-auto text-slate-400 mb-4"></i>
                    <div class="text-slate-500">No recent applications</div>
                </div>
                @endif
            </div>
        </div>

        <div class="col-span-12 lg:col-span-6">
            <div class="intro-y box p-5">
                <h3 class="font-medium text-base mb-4">Recent Disbursements</h3>
                @php
                    $recentDisbursements = \App\Models\DisbursementBatch::latest()->take(5)->get();
                @endphp
                @if($recentDisbursements->count() > 0)
                <div class="space-y-3">
                    @foreach($recentDisbursements as $disbursement)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <div>
                            <div class="font-medium">{{ $disbursement->batch_name }}</div>
                            <div class="text-slate-500 text-sm">{{ $disbursement->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-medium">₱{{ number_format($disbursement->total_amount, 0) }}</div>
                            <span class="px-2 py-1 rounded-full text-xs 
                                @if($disbursement->status === 'disbursed') bg-success text-white
                                @elseif($disbursement->status === 'pending') bg-warning text-white
                                @else bg-primary text-white @endif">
                                {{ ucfirst($disbursement->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i data-lucide="credit-card" class="w-12 h-12 mx-auto text-slate-400 mb-4"></i>
                    <div class="text-slate-500">No recent disbursements</div>
                </div>
                @endif
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

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dashboard Scholar Chart
        const dashboardScholarCtx = document.getElementById('dashboardScholarChart');
        if (dashboardScholarCtx) {
            new Chart(dashboardScholarCtx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: ['Active', 'Graduated', 'Discontinued'],
                    datasets: [{
                        data: [
                            {{ \App\Models\Scholar::where('status', 'active')->count() }},
                            {{ \App\Models\Scholar::where('status', 'graduated')->count() }},
                            {{ \App\Models\Scholar::where('status', 'discontinued')->count() }}
                        ],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(245, 158, 11, 0.8)'
                        ],
                        borderColor: [
                            'rgba(59, 130, 246, 1)',
                            'rgba(34, 197, 94, 1)',
                            'rgba(245, 158, 11, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }

        // Dashboard Disbursement Chart
        const dashboardDisbursementCtx = document.getElementById('dashboardDisbursementChart');
        if (dashboardDisbursementCtx) {
            new Chart(dashboardDisbursementCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Disbursements',
                        data: [
                            {{ \App\Models\DisbursementBatch::whereMonth('created_at', 1)->sum('total_amount') ?? 0 }},
                            {{ \App\Models\DisbursementBatch::whereMonth('created_at', 2)->sum('total_amount') ?? 0 }},
                            {{ \App\Models\DisbursementBatch::whereMonth('created_at', 3)->sum('total_amount') ?? 0 }},
                            {{ \App\Models\DisbursementBatch::whereMonth('created_at', 4)->sum('total_amount') ?? 0 }},
                            {{ \App\Models\DisbursementBatch::whereMonth('created_at', 5)->sum('total_amount') ?? 0 }},
                            {{ \App\Models\DisbursementBatch::whereMonth('created_at', 6)->sum('total_amount') ?? 0 }}
                        ],
                        borderColor: 'rgba(34, 197, 94, 1)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(34, 197, 94, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    });
    </script>
    @endpush
    
@endsection