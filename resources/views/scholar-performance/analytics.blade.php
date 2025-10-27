@extends('layouts.app')
@section('title', 'Performance Analytics')
@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Performance Analytics</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('scholar-performance.index') }}" class="btn btn-outline-secondary shadow-md">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Performance
        </a>
    </div>
</div>

<!-- Key Statistics -->
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="users" class="report-box__icon text-primary"></i>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $analytics['total_scholars'] }}</div>
                <div class="text-base text-slate-500 mt-1">Active Scholars</div>
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="file-text" class="report-box__icon text-success"></i>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $analytics['total_performance_records'] }}</div>
                <div class="text-base text-slate-500 mt-1">Performance Records</div>
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="report-box zoom-in">
            <div class="flex">
                <i data-lucide="trending-up" class="report-box__icon text-warning"></i>
            </div>
            <div class="text-3xl font-medium leading-8 mt-6">{{ number_format($analytics['average_gwa'], 2) }}</div>
            <div class="text-base text-slate-500 mt-1">Average GWA</div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="report-box zoom-in">
            <div class="flex">
                <i data-lucide="target" class="report-box__icon text-primary"></i>
            </div>
            <div class="text-3xl font-medium leading-8 mt-6">{{ number_format($analytics['average_completion_rate'], 1) }}%</div>
            <div class="text-base text-slate-500 mt-1">Avg Completion Rate</div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- Academic Status Distribution -->
    <div class="col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Academic Status Distribution</h2>
            </div>
            <div class="p-5">
                <div class="flex items-center justify-center" style="height: 300px;">
                    <div class="w-full h-full" id="status-chart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Trends -->
    <div class="col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">GWA Trends by Semester</h2>
            </div>
            <div class="p-5">
                <div class="flex items-center justify-center" style="height: 300px;">
                    <div class="w-full h-full" id="trends-chart"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Performers and At-Risk Scholars -->
<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- Top Performers -->
    <div class="col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Top Performing Scholars</h2>
            </div>
            <div class="p-5">
                <div class="overflow-x-auto">
                    <table class="table table-report -mt-2">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap">Scholar</th>
                                <th class="whitespace-nowrap">GWA</th>
                                <th class="whitespace-nowrap">Semester</th>
                                <th class="whitespace-nowrap">Completion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topPerformers as $performer)
                                <tr class="intro-x">
                                    <td>
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 image-fit zoom-in">
                                                <img alt="Scholar" class="tooltip rounded-full" 
                                                     src="{{ asset('public/Images/normalpicture.png') }}">
                                            </div>
                                            <div class="ml-3">
                                                <div class="font-medium whitespace-nowrap">
                                                    {{ $performer->scholar->user->first_name }} {{ $performer->scholar->user->last_name }}
                                                </div>
                                                <div class="text-slate-500 text-xs whitespace-nowrap">
                                                    {{ $performer->scholar->institution }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-medium text-success">{{ number_format($performer->gwa, 2) }}</div>
                                    </td>
                                    <td>
                                        <div class="text-slate-900">{{ $performer->semester }}</div>
                                        <div class="text-slate-500 text-xs">{{ $performer->school_year }}</div>
                                    </td>
                                    <td>
                                        <div class="font-medium">{{ $performer->getCompletionRate() }}%</div>
                                        <div class="text-slate-500 text-xs">{{ $performer->units_completed }}/{{ $performer->units_enrolled }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-slate-500">
                                        No performance records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scholars Needing Attention -->
    <div class="col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Scholars Needing Attention</h2>
            </div>
            <div class="p-5">
                <div class="overflow-x-auto">
                    <table class="table table-report -mt-2">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap">Scholar</th>
                                <th class="whitespace-nowrap">GWA</th>
                                <th class="whitespace-nowrap">Status</th>
                                <th class="whitespace-nowrap">Issues</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($needsAttention as $scholar)
                                <tr class="intro-x">
                                    <td>
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 image-fit zoom-in">
                                                <img alt="Scholar" class="tooltip rounded-full" 
                                                     src="{{ asset('public/Images/normalpicture.png') }}">
                                            </div>
                                            <div class="ml-3">
                                                <div class="font-medium whitespace-nowrap">
                                                    {{ $scholar->scholar->user->first_name }} {{ $scholar->scholar->user->last_name }}
                                                </div>
                                                <div class="text-slate-500 text-xs whitespace-nowrap">
                                                    {{ $scholar->scholar->institution }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-medium text-danger">{{ number_format($scholar->gwa, 2) }}</div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $scholar->getStatusBadgeClass() }}">
                                                <i data-lucide="{{ $scholar->academic_status === 'warning' ? 'alert-triangle' : 'x' }}" 
                                                   class="w-4 h-4 text-white"></i>
                                            </div>
                                            <span class="ml-2 text-sm">{{ $scholar->getStatusText() }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-xs">
                                            @if(!$scholar->meetsGWARequirement())
                                                <div class="text-danger">GWA too high</div>
                                            @endif
                                            @if(!$scholar->meetsUnitsRequirement())
                                                <div class="text-danger">Insufficient units</div>
                                            @endif
                                            @if(!$scholar->meetsNoFailedSubjectsRequirement())
                                                <div class="text-danger">{{ $scholar->subjects_failed }} failed subjects</div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-slate-500">
                                        No scholars need attention.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Semester Performance Table -->
<div class="intro-y box mt-5">
    <div class="flex items-center p-5 border-b border-slate-200/60">
        <h2 class="font-medium text-base mr-auto">Semester Performance Overview</h2>
    </div>
    <div class="p-5">
        <div class="overflow-x-auto">
            <table class="table table-report">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">Semester</th>
                        <th class="whitespace-nowrap">School Year</th>
                        <th class="whitespace-nowrap">Avg GWA</th>
                        <th class="whitespace-nowrap">Avg Completion Rate</th>
                        <th class="whitespace-nowrap">Records</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($semesterTrends as $trend)
                        <tr class="intro-x">
                            <td>
                                <div class="font-medium">{{ $trend->semester }}</div>
                            </td>
                            <td>
                                <div class="text-slate-900">{{ $trend->school_year }}</div>
                            </td>
                            <td>
                                <div class="font-medium">{{ number_format($trend->avg_gwa, 2) }}</div>
                            </td>
                            <td>
                                <div class="font-medium">{{ number_format($trend->avg_completion_rate, 1) }}%</div>
                            </td>
                            <td>
                                <div class="text-slate-500">{{ $trend->count ?? 'N/A' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-slate-500">
                                No semester data available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Academic Status Distribution Chart
const statusData = @json($statusDistribution);
const statusChart = new ApexCharts(document.querySelector("#status-chart"), {
    series: statusData.map(item => item.count),
    chart: {
        type: 'donut',
        height: 300
    },
    labels: statusData.map(item => {
        switch(item.academic_status) {
            case 'good': return 'Good Standing';
            case 'warning': return 'Warning';
            case 'probation': return 'Probation';
            default: return item.academic_status;
        }
    }),
    colors: ['#10b981', '#f59e0b', '#ef4444'],
    legend: {
        position: 'bottom'
    }
});
statusChart.render();

// Performance Trends Chart
const trendsData = @json($semesterTrends);
const trendsChart = new ApexCharts(document.querySelector("#trends-chart"), {
    series: [{
        name: 'Average GWA',
        data: trendsData.map(item => parseFloat(item.avg_gwa))
    }],
    chart: {
        type: 'line',
        height: 300
    },
    xaxis: {
        categories: trendsData.map(item => `${item.semester} ${item.school_year}`)
    },
    yaxis: {
        reversed: true,
        min: 1,
        max: 5
    },
    colors: ['#3b82f6'],
    stroke: {
        curve: 'smooth'
    }
});
trendsChart.render();
</script>
@endpush
@endsection 