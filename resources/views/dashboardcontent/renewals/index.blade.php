@extends('layouts.app')
@section('title', 'Renewal Management')
@section('content')
    <!-- BEGIN: Statistics -->
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="report-box zoom-in">
                <div class="box p-5">
                    <div class="flex">
                        <i data-lucide="clock" class="report-box__icon text-pending"></i>
                    </div>
                    <div class="text-3xl font-medium leading-8 mt-6">{{ $stats['total_pending'] }}</div>
                    <div class="text-base text-slate-500 mt-1">Pending Renewals</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="report-box zoom-in">
                <div class="box p-5">
                    <div class="flex">
                        <i data-lucide="search" class="report-box__icon text-warning"></i>
                    </div>
                    <div class="text-3xl font-medium leading-8 mt-6">{{ $stats['total_under_review'] }}</div>
                    <div class="text-base text-slate-500 mt-1">Under Review</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="report-box zoom-in">
                <div class="box p-5">
                    <div class="flex">
                        <i data-lucide="check-circle" class="report-box__icon text-success"></i>
                    </div>
                    <div class="text-3xl font-medium leading-8 mt-6">{{ $stats['total_approved'] }}</div>
                    <div class="text-base text-slate-500 mt-1">Approved</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="report-box zoom-in">
                <div class="box p-5">
                    <div class="flex">
                        <i data-lucide="x-circle" class="report-box__icon text-danger"></i>
                    </div>
                    <div class="text-3xl font-medium leading-8 mt-6">{{ $stats['total_rejected'] }}</div>
                    <div class="text-base text-slate-500 mt-1">Rejected</div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Statistics -->

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <div class="flex flex-wrap gap-2">
                <select class="form-select box" name="status" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>

                <select class="form-select box" name="semester" onchange="this.form.submit()">
                    <option value="">All Semesters</option>
                    <option value="First" {{ request('semester') == 'First' ? 'selected' : '' }}>First Semester</option>
                    <option value="Second" {{ request('semester') == 'Second' ? 'selected' : '' }}>Second Semester</option>
                    <option value="Summer" {{ request('semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                </select>

                <select class="form-select box" name="school_year" onchange="this.form.submit()">
                    <option value="">All School Years</option>
                    <option value="2023-2024" {{ request('school_year') == '2023-2024' ? 'selected' : '' }}>2023-2024</option>
                    <option value="2024-2025" {{ request('school_year') == '2024-2025' ? 'selected' : '' }}>2024-2025</option>
                    <option value="2025-2026" {{ request('school_year') == '2025-2026' ? 'selected' : '' }}>2025-2026</option>
                </select>
            </div>

            <div class="hidden md:block mx-auto text-slate-500">
                Showing {{ $renewals->firstItem() ?? 0 }} to {{ $renewals->lastItem() ?? 0 }} of {{ $renewals->total() ?? 0 }} entries
            </div>
        </div>

        <!-- BEGIN: Renewals List -->
        <div class="intro-y col-span-12">
            <div class="overflow-x-auto">
                <table class="table table-report -mt-2">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">Renewal #</th>
                            <th class="whitespace-nowrap">Scholar Name</th>
                            <th class="whitespace-nowrap">Semester</th>
                            <th class="whitespace-nowrap">School Year</th>
                            <th class="whitespace-nowrap">GWA</th>
                            <th class="whitespace-nowrap">Status</th>
                            <th class="whitespace-nowrap">Submitted</th>
                            <th class="whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($renewals as $renewal)
                        <tr class="intro-x">
                            <td>
                                <a href="{{ route('renewals.show', $renewal) }}" class="font-medium whitespace-nowrap">
                                    {{ $renewal->renewal_number }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('view.profile.other', $renewal->scholar->user_id) }}" class="font-medium whitespace-nowrap">
                                    {{ $renewal->scholar->user->first_name }} {{ $renewal->scholar->user->last_name }}
                                </a>
                                <div class="text-slate-500 text-xs mt-0.5">{{ $renewal->scholar->institution }}</div>
                            </td>
                            <td>{{ $renewal->semester }}</td>
                            <td>{{ $renewal->school_year }}</td>
                            <td>{{ $renewal->gwa }}</td>
                            <td>
                                <span class="px-2 py-1 rounded-full text-xs 
                                    @if($renewal->status === 'pending') bg-pending text-white
                                    @elseif($renewal->status === 'under_review') bg-warning text-white
                                    @elseif($renewal->status === 'approved') bg-success text-white
                                    @else bg-danger text-white @endif">
                                    {{ ucfirst(str_replace('_', ' ', $renewal->status)) }}
                                </span>
                            </td>
                            <td>{{ $renewal->submitted_at ? $renewal->submitted_at->format('M d, Y') : 'N/A' }}</td>
                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <a href="{{ route('renewals.show', $renewal) }}" class="flex items-center mr-3">
                                        <i data-lucide="eye" class="w-4 h-4 mr-1"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-slate-500">No renewal applications found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END: Renewals List -->

        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            {{ $renewals->links() }}
        </div>
        <!-- END: Pagination -->
    </div>
@endsection 