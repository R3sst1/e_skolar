@extends('layouts.app')

@section('title', 'Analytics Overview')

@section('header')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Analytics Overview
        </h2>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- Key Metrics -->
    <div class="col-span-12 lg:col-span-3">
        <div class="intro-y box p-5">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-primary"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold">{{ $totalScholars }}</div>
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
                    <div class="text-2xl font-bold">{{ $activeScholars }}</div>
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
                    <div class="text-2xl font-bold">{{ $graduatedScholars }}</div>
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
                    <div class="text-2xl font-bold">₱{{ number_format($totalDisbursements, 0) }}</div>
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
                    <div class="text-2xl font-bold text-primary">{{ $activeScholars }}</div>
                    <div class="text-slate-500 text-sm">Active</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-success">{{ $graduatedScholars }}</div>
                    <div class="text-slate-500 text-sm">Graduated</div>
                </div>
            </div>
            <div class="mt-4">
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
                    <div class="text-2xl font-bold text-primary">{{ $totalBatches }}</div>
                    <div class="text-slate-500 text-sm">Total Batches</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-success">₱{{ number_format($totalDisbursements, 0) }}</div>
                    <div class="text-slate-500 text-sm">Total Amount</div>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center justify-between text-sm">
                    <span>Disbursement Status</span>
                    <span class="font-medium">{{ $disbursedBatches }}/{{ $totalBatches }} Completed</span>
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
                    <div class="text-2xl font-bold text-primary">{{ $totalApplications }}</div>
                    <div class="text-slate-500 text-sm">Total Applications</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-success">{{ $approvedApplications }}</div>
                    <div class="text-slate-500 text-sm">Approved</div>
                </div>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-4">
                <div class="text-center">
                    <div class="text-lg font-bold text-warning">{{ $pendingApplications }}</div>
                    <div class="text-slate-500 text-sm">Pending</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-bold text-danger">{{ $rejectedApplications }}</div>
                    <div class="text-slate-500 text-sm">Rejected</div>
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
            <div class="flex flex-col lg:flex-row gap-6 items-center justify-center">
                <div class="text-center">
                    <div class="text-3xl font-bold text-primary">{{ $activeScholars }}</div>
                    <div class="text-slate-500 text-sm font-medium">Currently Active</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-success">{{ $graduatedScholars }}</div>
                    <div class="text-slate-500 text-sm font-medium">Successfully Graduated</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-warning">{{ $discontinuedScholars }}</div>
                    <div class="text-slate-500 text-sm font-medium">Discontinued</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Institution Breakdown -->
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
@endsection
