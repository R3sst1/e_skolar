@extends('layouts.app')
@section('title', 'Residence Data Search')
@section('content')
<div class="max-w-7xl mx-auto p-4">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Residence Data Search</h1>
                <p class="text-slate-600 mt-1">Search and view demographic information from the profiling system</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Dashboard
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
        <form method="GET" action="{{ route('residence.index') }}" class="flex gap-4">
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
                <a href="{{ route('residence.index') }}" class="btn btn-outline-secondary">
                    <i data-lucide="x" class="w-4 h-4 mr-2"></i> Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Search Results -->
    @if($q)
        <div class="box p-6">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-slate-700">
                    Search Results for "{{ $q }}"
                    @if($results->count() > 0)
                        <span class="text-sm font-normal text-slate-500">({{ $results->total() }} found)</span>
                    @endif
                </h2>
            </div>

            @if($results->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table table-report border border-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="border-b border-slate-200 font-bold text-slate-700">Full Name</th>
                                <th class="border-b border-slate-200 font-bold text-slate-700">Barangay / Purok</th>
                                <th class="border-b border-slate-200 font-bold text-slate-700">Household / Registry</th>
                                <th class="border-b border-slate-200 font-bold text-slate-700">Age / Sex</th>
                                <th class="border-b border-slate-200 font-bold text-slate-700 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $demographic)
                            <tr class="border-b border-slate-100 {{ $loop->even ? 'bg-slate-50' : 'bg-white' }}">
                                <td class="border-b border-slate-100 py-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center mr-3">
                                            <i data-lucide="user" class="w-4 h-4 text-white"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium">{{ $demographic->full_name }}</div>
                                            @if($demographic->geographicIdentification && $demographic->geographicIdentification->email_address)
                                            <div class="text-sm text-slate-500">{{ $demographic->geographicIdentification->email_address }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="border-b border-slate-100 py-3">
                                    <div>
                                        @if($demographic->geographicIdentification && $demographic->geographicIdentification->barangay)
                                            <div class="font-medium">{{ $demographic->geographicIdentification->barangay->name ?? 'N/A' }}</div>
                                        @else
                                            <div class="text-slate-500">No data</div>
                                        @endif
                                        @if($demographic->geographicIdentification && $demographic->geographicIdentification->subdivision_village_name)
                                            <div class="text-sm text-slate-500">{{ $demographic->geographicIdentification->subdivision_village_name }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="border-b border-slate-100 py-3">
                                    <div>
                                        <div class="font-medium">HH: {{ $demographic->household_number ?? 'N/A' }}</div>
                                        <div class="text-sm text-slate-500">Reg: {{ $demographic->registry_number ?? 'N/A' }}</div>
                                    </div>
                                </td>
                                <td class="border-b border-slate-100 py-3">
                                    <div>
                                        <div class="font-medium">{{ $demographic->age ?? 'N/A' }} years</div>
                                        <div class="text-sm text-slate-500">{{ ucfirst($demographic->sex ?? 'N/A') }}</div>
                                    </div>
                                </td>
                                <td class="border-b border-slate-100 py-3 text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        <!-- View Profile Button -->
                                        <a href="{{ route('residence.show', $demographic->id) }}" 
                                           class="btn btn-sm bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
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
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full" 
                                                  title="Account exists: {{ $existingUser->username }}">
                                                Account exists
                                            </span>
                                        @else
                                            <a href="{{ route('residence.create-account', $demographic->id) }}" 
                                               class="btn btn-sm bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded"
                                               title="Create account for this resident (admin only)">
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
                <div class="mt-6">
                    {{ $results->links() }}
                </div>
            @else
                <div class="text-center py-10 text-slate-500">
                    <i data-lucide="search" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                    <p>No residents found matching "{{ $q }}"</p>
                    <p class="text-sm mt-2">Try adjusting your search criteria.</p>
                </div>
            @endif
        </div>
    @else
        <!-- Search Instructions -->
        <div class="box p-6 text-center">
            <i data-lucide="search" class="w-16 h-16 mx-auto mb-4 text-slate-300"></i>
            <h3 class="text-lg font-semibold text-slate-700 mb-2">Search Residence Data</h3>
            <p class="text-slate-600 mb-4">Enter a search term above to find residents in the profiling system.</p>
            <div class="text-sm text-slate-500">
                <p>You can search by:</p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>Full name</li>
                    <li>First name or last name</li>
                    <li>Registry number</li>
                    <li>Email address</li>
                </ul>
            </div>
        </div>
    @endif
</div>
@endsection
