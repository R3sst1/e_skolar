@extends('layouts.app')
@section('title', 'Retention Statistics')

@section('header')
    <div class="intro-y flex items-center mt-8">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mr-3">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Dashboard
        </a>
        <h2 class="text-lg font-medium mr-auto">
            Retention Statistics
        </h2>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-6">
    <!-- Filter Section -->
    <div class="col-span-12 mt-8">
        <!-- <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">Retention & Renewal Statistics</h2>
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
                <select class="form-select w-full" id="institutionFilter">
                    <option value="">All Institutions</option>
                    @foreach($institutions as $institution)
                        <option value="{{ $institution }}">{{ $institution }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="flex space-x-2">
                    <a href="{{ route('super-admin.reports.retention') }}?format=excel" class="btn btn-warning flex-1">
                        <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                        Export Excel
                    </a>
                    <a href="{{ route('super-admin.reports.retention') }}?format=csv" class="btn btn-outline-secondary">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="col-span-12 mt-8">
        <div class="grid grid-cols-12 gap-6">
            <!-- Overall Retention Rate -->
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <i data-lucide="users" class="report-box__icon text-primary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6" data-stat="overallRetentionRate">{{ number_format($overallRetentionRate, 1) }}%</div>
                        <div class="text-base text-slate-500 mt-1">Overall Retention Rate</div>
                    </div>
                </div>
            </div>
            <!-- Renewal Rate -->
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <i data-lucide="refresh-cw" class="report-box__icon text-success"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6" data-stat="renewalRate">{{ number_format($renewalRate, 1) }}%</div>
                        <div class="text-base text-slate-500 mt-1">Renewal Rate</div>
                    </div>
                </div>
            </div>
            <!-- Graduation Rate -->
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <i data-lucide="award" class="report-box__icon text-warning"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6" data-stat="graduationRate">{{ number_format($graduationRate, 1) }}%</div>
                        <div class="text-base text-slate-500 mt-1">Graduation Rate</div>
                    </div>
                </div>
            </div>
            <!-- Dropout Rate -->
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <i data-lucide="user-minus" class="report-box__icon text-danger"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6" data-stat="dropoutRate">{{ number_format($dropoutRate, 1) }}%</div>
                        <div class="text-base text-slate-500 mt-1">Dropout Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Retention Trends</h2>
            </div>
            <div class="p-5">
                <canvas id="retentionTrendChart" height="280"></canvas>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Institution Comparison</h2>
            </div>
            <div class="p-5">
                <canvas id="institutionComparisonChart" height="280"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Tables -->
    <div class="col-span-12 mt-6">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Retention Analysis by Institution</h2>
            </div>
            <div class="p-5">
                <table class="table table-report -mt-2" id="institutionBreakdownTable">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">Institution</th>
                            <th class="text-center whitespace-nowrap">Total Scholars</th>
                            <th class="text-center whitespace-nowrap">Active</th>
                            <th class="text-center whitespace-nowrap">Graduated</th>
                            <th class="text-center whitespace-nowrap">Discontinued</th>
                            <th class="text-center whitespace-nowrap">Retention Rate</th>
                            <th class="text-center whitespace-nowrap">Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($institutionStats as $stat)
                        <tr>
                            <td>{{ $stat->institution }}</td>
                            <td class="text-center">{{ $stat->total_scholars }}</td>
                            <td class="text-center">{{ $stat->active_scholars }}</td>
                            <td class="text-center">{{ $stat->graduated_scholars }}</td>
                            <td class="text-center">{{ $stat->discontinued_scholars }}</td>
                            <td class="text-center">
                                <div class="flex items-center justify-center">
                                    <div class="w-2 h-2 @if($stat->retention_rate >= 80) bg-success @elseif($stat->retention_rate >= 60) bg-warning @else bg-danger @endif rounded-full mr-2"></div>
                                    {{ number_format($stat->retention_rate, 1) }}%
                                </div>
                            </td>
                            <td class="text-center">
                                @if($stat->trend > 0)
                                    <i data-lucide="trending-up" class="w-4 h-4 text-success mx-auto"></i>
                                @elseif($stat->trend < 0)
                                    <i data-lucide="trending-down" class="w-4 h-4 text-danger mx-auto"></i>
                                @else
                                    <i data-lucide="minus" class="w-4 h-4 text-slate-500 mx-auto"></i>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let retentionTrendChart, institutionComparisonChart;

    // Initialize Charts
    function initCharts() {
        // Retention Trend Chart
        const retentionCtx = document.getElementById('retentionTrendChart').getContext('2d');
        retentionTrendChart = new Chart(retentionCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($trendLabels) !!},
                datasets: [{
                    label: 'Retention Rate',
                    data: {!! json_encode($trendData) !!},
                    borderColor: 'rgb(45, 125, 246)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });

        // Institution Comparison Chart
        const institutionCtx = document.getElementById('institutionComparisonChart').getContext('2d');
        institutionComparisonChart = new Chart(institutionCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($institutionLabels) !!},
                datasets: [{
                    label: 'Retention Rate',
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
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    }

    // Update UI with new data
    function updateUI(data) {
        // Update statistics cards
        document.querySelector('[data-stat="overallRetentionRate"]').textContent = data.stats.overallRetentionRate + '%';
        document.querySelector('[data-stat="renewalRate"]').textContent = data.stats.renewalRate + '%';
        document.querySelector('[data-stat="graduationRate"]').textContent = data.stats.graduationRate + '%';
        document.querySelector('[data-stat="dropoutRate"]').textContent = data.stats.dropoutRate + '%';

        // Update trend chart
        retentionTrendChart.data.labels = data.charts.trend.labels;
        retentionTrendChart.data.datasets[0].data = data.charts.trend.data;
        retentionTrendChart.update();

        // Update institution comparison chart
        institutionComparisonChart.data.labels = data.charts.institution.labels;
        institutionComparisonChart.data.datasets[0].data = data.charts.institution.data;
        institutionComparisonChart.update();

        // Update institution breakdown table
        const tableBody = document.querySelector('#institutionBreakdownTable tbody');
        tableBody.innerHTML = '';
        data.table.institutionStats.forEach(stat => {
            const trendIcon = stat.trend > 0 
                ? '<i data-lucide="trending-up" class="w-4 h-4 text-success mx-auto"></i>'
                : (stat.trend < 0 
                    ? '<i data-lucide="trending-down" class="w-4 h-4 text-danger mx-auto"></i>'
                    : '<i data-lucide="minus" class="w-4 h-4 text-slate-500 mx-auto"></i>');

            const rateColor = stat.retention_rate >= 80 
                ? 'bg-success' 
                : (stat.retention_rate >= 60 ? 'bg-warning' : 'bg-danger');

            tableBody.innerHTML += `
                <tr>
                    <td>${stat.institution}</td>
                    <td class="text-center">${stat.total_scholars}</td>
                    <td class="text-center">${stat.active_scholars}</td>
                    <td class="text-center">${stat.graduated_scholars}</td>
                    <td class="text-center">${stat.discontinued_scholars}</td>
                    <td class="text-center">
                        <div class="flex items-center justify-center">
                            <div class="w-2 h-2 ${rateColor} rounded-full mr-2"></div>
                            ${Number(stat.retention_rate).toFixed(1)}%
                        </div>
                    </td>
                    <td class="text-center">${trendIcon}</td>
                </tr>
            `;
        });

        // Re-initialize Lucide icons for new table rows
        if (window.Lucide) {
            window.Lucide.createIcons();
        }
    }

    // Handle filter changes
    function handleFilterChange() {
        const year = document.getElementById('yearFilter').value;
        const institution = document.getElementById('institutionFilter').value;

        // Show loading state
        document.querySelectorAll('.report-box').forEach(box => {
            box.style.opacity = '0.5';
        });

        // Fetch updated data
        fetch(`{{ route('super-admin.retention-stats') }}?${new URLSearchParams({
            year,
            institution
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
        document.getElementById('institutionFilter').addEventListener('change', handleFilterChange);
    });
</script>
@endpush
@endsection 