@extends('layouts.app')
@section('title', 'Super Admin Dashboard')
@section('content')
    <div class="grid grid-cols-12 gap-6">
        <!-- BEGIN: General Report -->
        <div class="col-span-12 mt-8">
            <div class="intro-y flex items-center h-10">
                <h2 class="text-lg font-medium truncate mr-5">General Report</h2>
            </div>
            <div class="grid grid-cols-12 gap-6 mt-5">
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <i data-lucide="user" class="report-box__icon text-primary"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6">{{ $stats['total_applicants'] }}</div>
                            <div class="text-base text-slate-500 mt-1">Total Applicants</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <i data-lucide="users" class="report-box__icon text-pending"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6">{{ $stats['total_admins'] }}</div>
                            <div class="text-base text-slate-500 mt-1">Total Admins</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <i data-lucide="user-check" class="report-box__icon text-warning"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6">{{ $stats['active_scholars'] }}</div>
                            <div class="text-base text-slate-500 mt-1">Active Scholars</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <i data-lucide="dollar-sign" class="report-box__icon text-success"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6">₱{{ number_format($stats['total_disbursed'], 2) }}</div>
                            <div class="text-base text-slate-500 mt-1">Total Disbursements</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: General Report -->

        <!-- BEGIN: Charts -->
        <div class="col-span-12 lg:col-span-6">
            <div class="intro-y box">
                <div class="flex items-center p-5 border-b border-slate-200/60">
                    <h2 class="font-medium text-base mr-auto">Scholars by Status</h2>
                </div>
                <div class="p-5">
                    <canvas id="scholarStatusChart" height="280"></canvas>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-6">
            <div class="intro-y box">
                <div class="flex items-center p-5 border-b border-slate-200/60">
                    <h2 class="font-medium text-base mr-auto">Disbursements by Category</h2>
                </div>
                <div class="p-5">
                    <canvas id="disbursementCategoryChart" height="280"></canvas>
                </div>
            </div>
        </div>
        <!-- END: Charts -->

        <!-- BEGIN: Recent Activities -->
        <div class="col-span-12 mt-6">
            <div class="intro-y block sm:flex items-center h-10">
                <h2 class="text-lg font-medium truncate mr-5">Recent Activities</h2>
            </div>
            <div class="grid grid-cols-12 gap-6 mt-5">
                <!-- Recent Scholars -->
                <div class="col-span-12 lg:col-span-6">
                    <div class="intro-y box">
                        <div class="flex items-center p-5 border-b border-slate-200/60">
                            <h2 class="font-medium text-base mr-auto">New Scholars</h2>
                        </div>
                        <div class="p-5">
                            @forelse($recentScholars as $scholar)
                            <div class="flex items-center mb-5 last:mb-0">
                                <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                    <img src="{{ asset('Images/normalpicture.png') }}" alt="Scholar photo">
                                </div>
                                <div class="ml-4 mr-auto">
                                    <div class="font-medium">{{ $scholar->user->first_name }} {{ $scholar->user->last_name }}</div>
                                    <div class="text-slate-500 text-xs mt-0.5">{{ $scholar->institution }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-medium">{{ $scholar->status }}</div>
                                    <div class="text-slate-500 text-xs mt-0.5">{{ $scholar->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-slate-500 py-4">No recent scholars</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Disbursements -->
                <div class="col-span-12 lg:col-span-6">
                    <div class="intro-y box">
                        <div class="flex items-center p-5 border-b border-slate-200/60">
                            <h2 class="font-medium text-base mr-auto">Recent Disbursements</h2>
                        </div>
                        <div class="p-5">
                            @forelse($recentDisbursements as $disbursement)
                            <div class="flex items-center mb-5 last:mb-0">
                                <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                    <img src="{{ asset('Images/normalpicture.png') }}" alt="Scholar photo">
                                </div>
                                <div class="ml-4 mr-auto">
                                    <div class="font-medium">{{ $disbursement->scholar->user->first_name }} {{ $disbursement->scholar->user->last_name }}</div>
                                    <div class="text-slate-500 text-xs mt-0.5">{{ $disbursement->category }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-medium">₱{{ number_format($disbursement->amount, 2) }}</div>
                                    <div class="text-slate-500 text-xs mt-0.5">{{ $disbursement->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-slate-500 py-4">No recent disbursements</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Recent Activities -->

        <!-- BEGIN: Quick Links -->
        <div class="col-span-12 mt-6">
            <div class="intro-y block sm:flex items-center h-10">
                <h2 class="text-lg font-medium truncate mr-5">Quick Links</h2>
            </div>
            <div class="grid grid-cols-12 gap-6 mt-5">
                <a href="{{ route('accounts.index') }}" class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="box p-5 zoom-in">
                        <div class="flex items-center">
                            <div class="w-2/4 flex-none">
                                <div class="text-lg font-medium truncate">Manage Accounts</div>
                                <div class="text-slate-500 mt-1">User Management</div>
                            </div>
                            <div class="flex-none ml-auto relative">
                                <i data-lucide="users" class="w-12 h-12 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('super-admin.scholar-stats') }}" class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="box p-5 zoom-in">
                        <div class="flex items-center">
                            <div class="w-2/4 flex-none">
                                <div class="text-lg font-medium truncate">Scholar Stats</div>
                                <div class="text-slate-500 mt-1">By Institution/Barangay</div>
                            </div>
                            <div class="flex-none ml-auto relative">
                                <i data-lucide="pie-chart" class="w-12 h-12 text-pending"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('super-admin.disbursement-stats') }}" class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="box p-5 zoom-in">
                        <div class="flex items-center">
                            <div class="w-2/4 flex-none">
                                <div class="text-lg font-medium truncate">Disbursements</div>
                                <div class="text-slate-500 mt-1">Financial Summary</div>
                            </div>
                            <div class="flex-none ml-auto relative">
                                <i data-lucide="credit-card" class="w-12 h-12 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('super-admin.retention-stats') }}" class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                    <div class="box p-5 zoom-in">
                        <div class="flex items-center">
                            <div class="w-2/4 flex-none">
                                <div class="text-lg font-medium truncate">Retention</div>
                                <div class="text-slate-500 mt-1">Renewal Rates</div>
                            </div>
                            <div class="flex-none ml-auto relative">
                                <i data-lucide="activity" class="w-12 h-12 text-success"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <!-- END: Quick Links -->
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Scholar Status Chart
        const scholarCtx = document.getElementById('scholarStatusChart').getContext('2d');
        new Chart(scholarCtx, {
            type: 'pie',
            data: {
                labels: Object.keys({!! json_encode($scholarsByStatus) !!}),
                datasets: [{
                    data: Object.values({!! json_encode($scholarsByStatus) !!}),
                    backgroundColor: [
                        'rgba(45, 125, 246, 0.5)',
                        'rgba(52, 195, 143, 0.5)',
                        'rgba(241, 180, 76, 0.5)',
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

        // Disbursement Category Chart
        const disbursementCtx = document.getElementById('disbursementCategoryChart').getContext('2d');
        new Chart(disbursementCtx, {
            type: 'bar',
            data: {
                labels: Object.keys({!! json_encode($disbursementsByCategory) !!}),
                datasets: [{
                    label: 'Amount Disbursed',
                    data: Object.values({!! json_encode($disbursementsByCategory) !!}),
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
    @endpush
@endsection 