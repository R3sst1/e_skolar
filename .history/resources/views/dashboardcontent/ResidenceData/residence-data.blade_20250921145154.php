@extends('layouts.app')
@section('title', 'Residence Data - Demographic Information')
@section('content')

<div class="max-w-7xl mx-auto p-4">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Residence Data</h1>
                <p class="text-slate-600 mt-1">Search and view demographic information from the profiling system</p>
            </div>
            <div class="flex gap-3">
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
    <div class="box p-6">
        <div class="mb-4">
            <h2 class="text-lg font-semibold text-slate-700">
                @if($q)
                    Search Results for "{{ $q }}"
                @else
                    All Residents
                @endif
                @if($demographics->count() > 0)
                    <span class="text-sm font-normal text-slate-500">({{ $demographics->total() }} found)</span>
                @endif
            </h2>
        </div>

        @if($demographics->count() > 0)
            <div class="overflow-x-auto">
                <table class="table table-report border border-slate-200 w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="border-b border-slate-200 font-bold text-slate-700 px-4 py-3 text-left">Full Name</th>
                            <th class="border-b border-slate-200 font-bold text-slate-700 px-4 py-3 text-left">Barangay / Purok</th>
                            <th class="border-b border-slate-200 font-bold text-slate-700 px-4 py-3 text-left">Household / Registry</th>
                            <th class="border-b border-slate-200 font-bold text-slate-700 px-4 py-3 text-left">Age / Sex</th>
                            <th class="border-b border-slate-200 font-bold text-slate-700 px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($demographics as $demographic)
                        <tr class="border-b border-slate-100 {{ $loop->even ? 'bg-slate-50' : 'bg-white' }} hover:bg-slate-100 transition-colors">
                            <td class="border-b border-slate-100 py-4 px-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                        <i data-lucide="user" class="w-5 h-5 text-white"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-medium text-slate-900 truncate">{{ $demographic->full_name }}</div>
                                        @if($demographic->geographicIdentification && $demographic->geographicIdentification->email_address)
                                        <div class="text-sm text-slate-500 truncate">{{ $demographic->geographicIdentification->email_address }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="border-b border-slate-100 py-4 px-4">
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
                            <td class="border-b border-slate-100 py-4 px-4">
                                <div class="space-y-1">
                                    <div class="font-medium text-slate-900">
                                        HH: {{ $demographic->household_number ?? '—' }}
                                    </div>
                                    <div class="text-sm text-slate-500">
                                        Reg: {{ $demographic->registry_number ?? '—' }}
                                    </div>
                                </div>
                            </td>
                            <td class="border-b border-slate-100 py-4 px-4">
                                <div class="space-y-1">
                                    <div class="font-medium text-slate-900">
                                        {{ $demographic->age ?? '—' }} years
                                    </div>
                                    <div class="text-sm text-slate-500">
                                        {{ ucfirst($demographic->sex ?? '—') }}
                                    </div>
                                </div>
                            </td>
                            <td class="border-b border-slate-100 py-4 px-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <!-- View Profile Button -->
                                    <a href="{{ route('residence-data.show', $demographic->id) }}" 
                                       class="inline-flex items-center bg-blue-600 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-700"
                                       title="View detailed profile">
                                        <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                        View Profile
                                    </a>

                                    <!-- Create Account Button or Account Exists Label -->
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

                                    @if($existingUser)
                                        <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-sm rounded-md font-medium border border-green-200" 
                                              title="Account exists: {{ $existingUser->username }}">
                                            <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i>
                                            Account Exists
                                        </span>
                                    @else
                                        <a href="{{ route('residence-data.create-account', $demographic->id) }}" 
                                           class="inline-flex items-center bg-blue-600 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-700"
                                           title="Create account for this resident (admin only)">
                                            <i data-lucide="user-plus" class="w-4 h-4 mr-1"></i>
                                            Create Account
                                        </a>
                                    @endif

                                    <!-- Edit Button -->
                                    <a href="#" 
                                       class="inline-flex items-center bg-yellow-500 text-white px-3 py-1 rounded-md text-sm hover:bg-yellow-600"
                                       title="Edit demographic information">
                                        <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                                        Edit
                                    </a>

                                    <!-- Delete Button -->
                                    <a href="#" 
                                       class="inline-flex items-center bg-red-600 text-white px-3 py-1 rounded-md text-sm hover:bg-red-700"
                                       title="Delete demographic record">
                                        <i data-lucide="trash" class="w-4 h-4 mr-1"></i>
                                        Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                {{ $demographics->links() }}
            </div>
        @else
            <div class="text-center py-12 text-slate-500">
                <i data-lucide="users" class="w-16 h-16 mx-auto mb-4 text-slate-300"></i>
                @if($q)
                    <h3 class="text-lg font-semibold text-slate-700 mb-2">No Results Found</h3>
                    <p>No residents found matching "{{ $q }}"</p>
                    <p class="text-sm mt-2">Try adjusting your search criteria or search terms.</p>
                @else
                    <h3 class="text-lg font-semibold text-slate-700 mb-2">No Data Available</h3>
                    <p>No demographic data available in the profiling system.</p>
                    <p class="text-sm mt-2">Contact your system administrator to ensure data is properly loaded.</p>
                @endif
            </div>
        @endif
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