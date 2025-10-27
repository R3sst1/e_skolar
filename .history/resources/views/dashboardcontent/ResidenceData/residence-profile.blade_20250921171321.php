@extends('layouts.app')
@section('title', 'Residence Profile - ' . $demographic->full_name)
@section('content')
<div class="max-w-7xl mx-auto p-4">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">{{ $demographic->full_name }}</h1>
                <p class="text-slate-600 mt-1">Residence Profile - Demographics ID: {{ $demographic->id }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('residence-data.index') }}" class="btn btn-outline-secondary">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Residence Data
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Personal Information -->
        <div class="box p-6">
            <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center">
                <i data-lucide="user" class="w-5 h-5 mr-2"></i>
                Personal Information
            </h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-slate-600">Full Name:</span>
                    <span class="font-medium">{{ $demographic->full_name }}</span>
                </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Sex:</span>
                        <span class="font-medium">
                            {{ $demographic->sexInfo->name ?? 'N/A' }}
                        </span>
                    </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Date of Birth:</span>
                    <span class="font-medium">{{ $demographic->date_of_birth ? $demographic->date_of_birth->format('M d, Y') : 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Age:</span>
                    <span class="font-medium">{{ $demographic->age ?? 'N/A' }} years</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Marital Status:</span>
                    <span class="font-medium">{{ $demographic->maritalStatus->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Registry Number:</span>
                    <span class="font-medium">{{ $demographic->registry_number ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Birth Registered:</span>
                    <span class="font-medium">{{ $demographic->birth_registered_in_local_registry ? 'Yes' : 'No' }}</span>
                </div>
            </div>
        </div>

        <!-- Residence Information -->
        <div class="box p-6">
            <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center">
                <i data-lucide="home" class="w-5 h-5 mr-2"></i>
                Residence Information
            </h2>
            @if($demographic->geographicIdentification)
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Barangay:</span>
                        <span class="font-medium">{{ $demographic->geographicIdentification->barangay->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">House Number:</span>
                        <span class="font-medium">{{ $demographic->geographicIdentification->house_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Street Name:</span>
                        <span class="font-medium">{{ $demographic->geographicIdentification->street_name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Subdivision/Village:</span>
                        <span class="font-medium">{{ $demographic->geographicIdentification->subdivision_village_name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Block/Lot Number:</span>
                        <span class="font-medium">{{ $demographic->geographicIdentification->block_lot_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Contact Number:</span>
                        <span class="font-medium">{{ $demographic->geographicIdentification->contact_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Email Address:</span>
                        <span class="font-medium">{{ $demographic->geographicIdentification->email_address ?? 'N/A' }}</span>
                    </div>
                    @if($demographic->geographicIdentification->latitude && $demographic->geographicIdentification->longitude)
                    <div class="flex justify-between">
                        <span class="text-slate-600">Coordinates:</span>
                        <span class="font-medium text-xs">{{ $demographic->geographicIdentification->latitude }}, {{ $demographic->geographicIdentification->longitude }}</span>
                    </div>
                    @endif
                </div>
            @else
                <div class="text-slate-500 text-center py-4">
                    <i data-lucide="home" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                    <p>No residence data available</p>
                </div>
            @endif
        </div>

        <!-- Education Information -->
        <div class="box p-6">
            <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center">
                <i data-lucide="graduation-cap" class="w-5 h-5 mr-2"></i>
                Education Information
            </h2>
            @if($demographic->educationAndLiteracy)
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Grade Year:</span>
                        <span class="font-medium">{{ $demographic->educationAndLiteracy->gradeYear->description ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Current Grade Year:</span>
                        <span class="font-medium">{{ $demographic->educationAndLiteracy->currentGradeYear->description ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">School Attendance:</span>
                        <span class="font-medium">{{ $demographic->educationAndLiteracy->current_school_attendance ? 'Yes' : 'No' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Type of School:</span>
                        <span class="font-medium">{{ $demographic->educationAndLiteracy->type_of_school ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Basic Literacy:</span>
                        <span class="font-medium">{{ $demographic->educationAndLiteracy->basic_literacy ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">TVL Graduate:</span>
                        <span class="font-medium">{{ $demographic->educationAndLiteracy->graduate_of_TVL ? 'Yes' : 'No' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">TVET Skills Dev:</span>
                        <span class="font-medium">{{ $demographic->educationAndLiteracy->is_currently_attending_TVET_skills_dev ? 'Yes' : 'No' }}</span>
                    </div>
                </div>
            @else
                <div class="text-slate-500 text-center py-4">
                    <i data-lucide="graduation-cap" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                    <p>No education data available</p>
                </div>
            @endif
        </div>

        <!-- Family & Household Information -->
        <div class="box p-6">
            <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center">
                <i data-lucide="users" class="w-5 h-5 mr-2"></i>
                Family & Household Information
            </h2>
            <div class="space-y-4">
                <!-- Family Relationships -->
                <div>
                    <h3 class="text-sm font-medium text-slate-600 mb-2">Family Relationships</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Relationship to Head:</span>
                            <span class="font-medium">{{ $demographic->familyHeadRelationship->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Nuclear Family Relationship:</span>
                            <span class="font-medium">{{ $demographic->nuclearFamilyRelationship->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Household Number:</span>
                            <span class="font-medium">{{ $demographic->household_number ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Family Income -->
                @if($demographic->familyIncome)
                    <div>
                        <h3 class="text-sm font-medium text-slate-600 mb-2">Family Income</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Total Annual Income (Current):</span>
                                <span class="font-medium">₱{{ number_format($demographic->familyIncome->total_annual_income_current_family_members ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Total Annual Income (Former):</span>
                                <span class="font-medium">₱{{ number_format($demographic->familyIncome->total_annual_income_current_former_family_members ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="text-slate-600 font-medium">Combined Total:</span>
                                <span class="font-bold text-primary">₱{{ number_format($demographic->familyIncome->total_income ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-slate-500 text-center py-2">
                        <p class="text-sm">No family income data available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Place of Birth -->
        <div class="box p-6 lg:col-span-2">
            <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center">
                <i data-lucide="map-pin" class="w-5 h-5 mr-2"></i>
                Place of Birth
            </h2>
            @if($demographic->placeOfBirth)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Place of Birth:</span>
                        <span class="font-medium">{{ $demographic->placeOfBirth->place_of_birth ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Municipality:</span>
                        <span class="font-medium">{{ $demographic->placeOfBirth->municipality_of_birth ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Province:</span>
                        <span class="font-medium">{{ $demographic->placeOfBirth->province_of_birth ?? 'N/A' }}</span>
                    </div>
                </div>
            @else
                <div class="text-slate-500 text-center py-4">
                    <i data-lucide="map-pin" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                    <p>No place of birth data available</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Account Creation Section -->
    <div class="mt-6">
        <div class="box p-6">
            <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center">
                <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i>
                Account Management
            </h2>
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
                <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-green-800">Account Already Exists</p>
                            <p class="text-sm text-green-600">Username: {{ $existingUser->username }} | Role: {{ ucfirst($existingUser->role) }}</p>
                        </div>
                    </div>
                    <a href="{{ route('view.profile.other', $existingUser->id) }}" class="btn btn-sm btn-outline-primary">
                        <i data-lucide="eye" class="w-4 h-4 mr-2"></i> View Account
                    </a>
                </div>
            @else
                <div class="flex items-center justify-between p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center">
                        <i data-lucide="user-plus" class="w-5 h-5 text-blue-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-blue-800">No Account Found</p>
                            <p class="text-sm text-blue-600">This resident does not have a user account yet.</p>
                        </div>
                    </div>
                    <a href="{{ route('create-account-demographic', $demographic->id) }}" class="btn btn-sm btn-primary">
                        <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i> Create Account
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
