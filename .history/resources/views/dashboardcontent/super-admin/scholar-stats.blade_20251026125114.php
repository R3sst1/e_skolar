@extends('layouts.app')
@section('title', 'Scholar Statistics')

@section('header')
    <div class="intro-y flex items-center mt-8">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mr-3">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Dashboard
        </a>
        <h2 class="text-lg font-medium mr-auto">
            Scholar Statistics
        </h2>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-6">
    <!-- Filter Section -->
    <div class="col-span-12 mt-8">
        <!-- <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">Scholar Statistics</h2>
        </div> -->
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <select class="form-select w-full" id="yearFilter">
                    <option value="">All Years</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <select class="form-select w-full" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="graduated">Graduated</option>
                    <option value="discontinued">Discontinued</option>
                </select>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <select class="form-select w-full" id="institutionFilter">
                    <option value="">All Institutions</option>
                    @foreach($institutions as $institution)
                        <option value="{{ $institution }}">{{ $institution }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <select class="form-select w-full" id="barangayFilter">
                    <option value="">All Barangays</option>
                    @foreach($barangays as $barangay)
                        <option value="{{ $barangay }}">{{ $barangay }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="flex space-x-2">
                    <a href="{{ route('super-admin.reports.active-scholars') }}?format=excel" class="btn btn-primary flex-1">
                        <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                        Export Excel
                    </a>
                    <a href="{{ route('super-admin.reports.active-scholars') }}?format=csv" class="btn btn-outline-secondary">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="col-span-12 mt-8">
        <div class="grid grid-cols-12 gap-6">
            <!-- Total Scholars Card -->
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <i data-lucide="users" class="report-box__icon text-primary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6" data-stat="totalScholars">{{ $totalScholars }}</div>
                        <div class="text-base text-slate-500 mt-1">Total Scholars</div>
                    </div>
                </div>
            </div>
            <!-- Active Scholars Card -->
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <i data-lucide="user-check" class="report-box__icon text-success"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6" data-stat="activeScholars">{{ $activeScholars }}</div>
                        <div class="text-base text-slate-500 mt-1">Active Scholars</div>
                    </div>
                </div>
            </div>
            <!-- Graduated Scholars Card -->
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <i data-lucide="award" class="report-box__icon text-warning"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6" data-stat="graduatedScholars">{{ $graduatedScholars }}</div>
                        <div class="text-base text-slate-500 mt-1">Graduated Scholars</div>
                    </div>
                </div>
            </div>
            <!-- Retention Rate Card -->
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <i data-lucide="trending-up" class="report-box__icon text-pending"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6" data-stat="retentionRate">{{ number_format($retentionRate, 1) }}%</div>
                        <div class="text-base text-slate-500 mt-1">Retention Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Scholars by Institution</h2>
            </div>
            <div class="p-5">
                <canvas id="institutionChart" height="280"></canvas>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Scholars by Barangay</h2>
            </div>
            <div class="p-5">
                <canvas id="barangayChart" height="280"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Tables -->
    <div class="col-span-12 mt-6">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Institution Breakdown</h2>
            </div>
            <div class="p-5">
                <table class="table table-report -mt-2" id="institutionBreakdownTable">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">Institution</th>
                            <th class="text-center whitespace-nowrap">Total Scholars</th>
                            <th class="text-center whitespace-nowrap">Active</th>
                            <th class="text-center whitespace-nowrap">Graduated</th>
                            <th class="text-center whitespace-nowrap">Retention Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($institutionStats as $stat)
                        <tr>
                            <td>{{ $stat->institution }}</td>
                            <td class="text-center">{{ $stat->total }}</td>
                            <td class="text-center">{{ $stat->active }}</td>
                            <td class="text-center">{{ $stat->graduated }}</td>
                            <td class="text-center">{{ number_format($stat->retention_rate, 1) }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let institutionChart, barangayChart;

    // Initialize Charts
    function initCharts() {
        // Institution Chart
        const institutionCtx = document.getElementById('institutionChart').getContext('2d');
        institutionChart = new Chart(institutionCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($institutionLabels) !!},
                datasets: [{
                    label: 'Number of Scholars',
                    data: {!! json_encode($institutionData) !!},
                    backgroundColor: 'rgba(45, 125, 246, 0.5)',
                    borderColor: 'rgb(45, 125, 246)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Barangay Chart
        const barangayCtx = document.getElementById('barangayChart').getContext('2d');
        barangayChart = new Chart(barangayCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($barangayLabels) !!},
                datasets: [{
                    data: {!! json_encode($barangayData) !!},
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
    }

    // Update UI with new data
    function updateUI(data) {
        // Update statistics cards
        document.querySelector('[data-stat="totalScholars"]').textContent = data.stats.totalScholars;
        document.querySelector('[data-stat="activeScholars"]').textContent = data.stats.activeScholars;
        document.querySelector('[data-stat="graduatedScholars"]').textContent = data.stats.graduatedScholars;
        document.querySelector('[data-stat="retentionRate"]').textContent = data.stats.retentionRate + '%';

        // Update institution chart
        institutionChart.data.labels = data.charts.institution.labels;
        institutionChart.data.datasets[0].data = data.charts.institution.data;
        institutionChart.update();

        // Update barangay chart
        barangayChart.data.labels = data.charts.barangay.labels;
        barangayChart.data.datasets[0].data = data.charts.barangay.data;
        barangayChart.update();

        // Update institution breakdown table
        const tableBody = document.querySelector('#institutionBreakdownTable tbody');
        tableBody.innerHTML = '';
        data.table.institutionStats.forEach(stat => {
            tableBody.innerHTML += `
                <tr>
                    <td>${stat.institution}</td>
                    <td class="text-center">${stat.total}</td>
                    <td class="text-center">${stat.active}</td>
                    <td class="text-center">${stat.graduated}</td>
                    <td class="text-center">${Number(stat.retention_rate).toFixed(1)}%</td>
                </tr>
            `;
        });
    }

    // Handle filter changes
    function handleFilterChange() {
        const year = document.getElementById('yearFilter').value;
        const status = document.getElementById('statusFilter').value;
        const institution = document.getElementById('institutionFilter').value;
        const barangay = document.getElementById('barangayFilter').value;

        // Show loading state
        document.querySelectorAll('.report-box').forEach(box => {
            box.style.opacity = '0.5';
        });

        // Fetch updated data
        fetch(`{{ route('super-admin.scholar-stats') }}?${new URLSearchParams({
            year,
            status,
            institution,
            barangay
        })}`)
        .then(response => response.json())
        .then(data => {
            updateUI(data);
            // Remove loading state
            document.querySelectorAll('.report-box').forEach(box => {
                box.style.opacity = '1';
            });
        })
        .catch(error => {
            console.error('Error:', error);
            // Remove loading state and show error
            document.querySelectorAll('.report-box').forEach(box => {
                box.style.opacity = '1';
            });
            alert('Error updating statistics. Please try again.');
        });
    }

    // Initialize everything
    document.addEventListener('DOMContentLoaded', function() {
        initCharts();

        // Add event listeners to filters
        document.getElementById('yearFilter').addEventListener('change', handleFilterChange);
        document.getElementById('statusFilter').addEventListener('change', handleFilterChange);
        document.getElementById('institutionFilter').addEventListener('change', handleFilterChange);
        document.getElementById('barangayFilter').addEventListener('change', handleFilterChange);
    });
</script>
@endpush 