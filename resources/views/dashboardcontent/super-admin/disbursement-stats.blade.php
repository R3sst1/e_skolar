@extends('layouts.app')
@section('title', 'Disbursement Statistics')

@section('header')
    <div class="intro-y flex items-center mt-8">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mr-3">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Dashboard
        </a>
        <h2 class="text-lg font-medium mr-auto">
            Disbursement Statistics
        </h2>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- Statistics Cards -->
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Total Batches</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-slate-600">{{ $totalBatches }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Pending Batches</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-warning">{{ $pendingBatches }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Reviewed Batches</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-primary">{{ $reviewedBatches }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Disbursed Batches</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-success">{{ $disbursedBatches }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Total Amount Disbursed</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-slate-600">₱{{ number_format($totalAmount, 2) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="text-slate-500">Total Students</div>
                <div class="ml-auto">
                    <div class="text-base font-medium text-slate-600">{{ $totalStudents }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
        <div class="box p-5">
            <div class="flex items-center border-b border-slate-200 dark:border-darkmode-400 pb-5 mb-5">
                <div class="font-medium text-base">Batch Status Distribution</div>
            </div>
            <div class="mt-3">
                <canvas id="statusChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
        <div class="box p-5">
            <div class="flex items-center border-b border-slate-200 dark:border-darkmode-400 pb-5 mb-5">
                <div class="font-medium text-base">Monthly Disbursements</div>
            </div>
            <div class="mt-3">
                <canvas id="monthlyChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Status Statistics Table -->
    <div class="col-span-12">
        <div class="box p-5">
            <div class="flex items-center border-b border-slate-200 dark:border-darkmode-400 pb-5 mb-5">
                <div class="font-medium text-base">Status Statistics</div>
            </div>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="border-b-2 dark:border-darkmode-400">Status</th>
                            <th class="border-b-2 dark:border-darkmode-400">Total Batches</th>
                            <th class="border-b-2 dark:border-darkmode-400">Total Amount</th>
                            <th class="border-b-2 dark:border-darkmode-400">Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($statusStats as $stat)
                        <tr>
                            <td class="border-b dark:border-darkmode-400">
                                <span class="px-2 py-1 rounded-full text-xs 
                                    {{ $stat->status === 'pending' ? 'bg-warning text-white' : 
                                       ($stat->status === 'reviewed' ? 'bg-primary text-white' : 'bg-success text-white') }}">
                                    {{ ucfirst($stat->status) }}
                                </span>
                            </td>
                            <td class="border-b dark:border-darkmode-400">{{ $stat->total_batches }}</td>
                            <td class="border-b dark:border-darkmode-400">₱{{ number_format($stat->total_amount, 2) }}</td>
                            <td class="border-b dark:border-darkmode-400">
                                {{ $totalBatches > 0 ? number_format(($stat->total_batches / $totalBatches) * 100, 1) : 0 }}%
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

@section('scripts')
<script>
    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: @json($statusLabels),
            datasets: [{
                data: @json($statusData),
                backgroundColor: [
                    '#f59e0b', // warning
                    '#3b82f6', // primary
                    '#10b981'  // success
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: @json($monthlyLabels),
            datasets: [{
                label: 'Amount Disbursed',
                data: @json($monthlyData),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
