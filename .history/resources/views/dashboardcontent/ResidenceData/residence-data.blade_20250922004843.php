@extends('layouts.app')
@section('title', '')
@section('content')

<div class="max-w-7xl mx-auto p-4">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Residence Data</h1>
                <p class="text-slate-600 mt-1">Search and view demographic information from the profiling system</p>
            </div>
            
            <div class="hidden md:block mx-auto text-slate-1500">
                <a href="{{ route('accounts.index') }}" class="btn btn-outline-secondary">
                    <i data-lucide="user-check" class="w-4 h-4 mr-2"></i> Account Management
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info mb-4">{{ session('info') }}</div>
    @endif

    <!-- Search Form -->
    <div class="box p-6 mb-6">
        <form method="GET" action="{{ route('residence-data.index') }}" class="flex gap-4">
            <div class="flex-1">
                <input type="text" 
                       name="q" 
                       value="{{ $q ?? '' }}" 
                       class="form-control" 
                       placeholder="Search by full name, first name, last name, registry number, or email...">
            </div>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="search" class="w-4 h-4 mr-2"></i> Search
            </button>
            @if($q)
                <a href="{{ route('residence-data.index') }}" class="btn btn-outline-secondary">
                    <i data-lucide="x" class="w-4 h-4 mr-2"></i> Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Demographics List -->
    <div class="intro-y box mt-5">
        <div class="flex items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto">
                @if($q)
                    Search Results for "{{ $q }}"
                @else
                    Resident Records
                @endif
                @if($demographics->count() > 0)
                    <span class="text-sm font-normal text-slate-500 ml-2">({{ $demographics->total() }} found)</span>
                @endif
            </h2>
        </div>
        <div class="p-5">
            @if($demographics->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table table-report -mt-2">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap">Resident</th>
                                <th class="whitespace-nowrap">Location</th>
                                <th class="whitespace-nowrap">Household</th>
                                <th class="whitespace-nowrap">Demographics</th>
                                <th class="whitespace-nowrap">Contact</th>
                                <th class="whitespace-nowrap">Account Status</th>
                                <th class="whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($demographics as $demographic)
                            @php
                                $existingUser = null;
                                // Check if account already exists
                                if ($demographic->geographicIdentification && $demographic->geographicIdentification->email_address) {
                                    $existingUser = \App\Models\User::where('email', $demographic->geographicIdentification->email_address)->first();
                                }
                                if (!$existingUser) {
                                    $fullName = strtolower(trim($demographic->full_name));
                                    $existingUser = \App\Models\User::whereRaw('LOWER(CONCAT(first_name, " ", last_name)) = ?', [$fullName])->first();
                                }
                                if (!$existingUser) {
                                    $existingUser = \App\Models\User::where('first_name', $demographic->first_name)
                                                                   ->where('last_name', $demographic->last_name)
                                                                   ->first();
                                }
                            @endphp
                            <tr>
                                <td>
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                            <i data-lucide="user" class="w-5 h-5 text-white"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="font-medium text-slate-900 truncate">{{ $demographic->full_name }}</div>
                                            <div class="text-sm text-slate-500 truncate">ID: {{ $demographic->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="min-w-0">
                                        @if($demographic->geographicIdentification && $demographic->geographicIdentification->barangay)
                                            <div class="font-medium text-slate-900">{{ $demographic->geographicIdentification->barangay->name ?? '—' }}</div>
                                            @if($demographic->geographicIdentification->subdivision_village_name)
                                                <div class="text-sm text-slate-500">{{ $demographic->geographicIdentification->subdivision_village_name }}</div>
                                            @else
                                                <div class="text-sm text-slate-400">No purok data</div>
                                            @endif
                                        @else
                                            <div class="text-slate-400">—</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="space-y-1">
                                        <div class="font-medium text-slate-900">
                                            HH: {{ $demographic->household_number ?? '—' }}
                                        </div>
                                        <div class="text-sm text-slate-500">
                                            Reg: {{ $demographic->registry_number ?? '—' }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="space-y-1">
                                        <div class="font-medium text-slate-900">
                                            {{ $demographic->age ?? '—' }} years
                                        </div>
                                        <div class="text-sm text-slate-500">
                                            {{ $demographic->sexInfo->name ?? '—' }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="min-w-0">
                                        @if($demographic->geographicIdentification && $demographic->geographicIdentification->contact_number)
                                            <div class="font-medium text-slate-900">{{ $demographic->geographicIdentification->contact_number }}</div>
                                        @else
                                            <div class="text-slate-400">—</div>
                                        @endif
                                        @if($demographic->geographicIdentification && $demographic->geographicIdentification->email_address)
                                            <div class="text-sm text-slate-500 truncate">{{ $demographic->geographicIdentification->email_address }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($existingUser)
                                        <div class="flex items-center text-success">
                                            <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i>
                                            <span class="text-sm font-medium">Account Exists</span>
                                        </div>
                                        <div class="text-xs text-slate-500 mt-1">{{ $existingUser->username }}</div>
                                    @else
                                        <div class="flex items-center text-slate-400">
                                            <i data-lucide="user-x" class="w-4 h-4 mr-1"></i>
                                            <span class="text-sm">No Account</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('residence-data.show', $demographic->id) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="View detailed profile">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        @if(!$existingUser)
                                            <a href="{{ route('accounts.create', ['demographic_id' => $demographic->id]) }}" 
                                               class="btn btn-sm btn-primary"
                                               title="Create account for this resident">
                                                <i data-lucide="user-plus" class="w-4 h-4"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-5">
                {{ $demographics->links() }}
            </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table table-report -mt-2">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap">Resident</th>
                                <th class="whitespace-nowrap">Location</th>
                                <th class="whitespace-nowrap">Household</th>
                                <th class="whitespace-nowrap">Demographics</th>
                                <th class="whitespace-nowrap">Contact</th>
                                <th class="whitespace-nowrap">Account Status</th>
                                <th class="whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center py-8 text-slate-500">
                                    @if($q)
                                        <div class="flex flex-col items-center">
                                            <i data-lucide="search" class="w-12 h-12 mb-3 text-slate-300"></i>
                                            <h3 class="text-lg font-semibold text-slate-700 mb-2">No Results Found</h3>
                                            <p>No residents found matching "{{ $q }}"</p>
                                            <p class="text-sm mt-2">Try adjusting your search criteria or search terms.</p>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center">
                                            <i data-lucide="users" class="w-12 h-12 mb-3 text-slate-300"></i>
                                            <h3 class="text-lg font-semibold text-slate-700 mb-2">No Data Available</h3>
                                            <p>No demographic data available in the profiling system.</p>
                                            <p class="text-sm mt-2">Contact your system administrator to ensure data is properly loaded.</p>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .table th {
        background-color: #f8fafc;
        font-weight: 600;
        color: #334155;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .btn:active {
        transform: translateY(0);
    }
    
    .table tbody tr:hover {
        background-color: #f1f5f9 !important;
    }
</style>
@endpush