@extends('layouts.app')
@section('title', '' )
@section('content')
<div class="content">
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Residence Profile 
        </h2>
        <div class="flex gap-3">
            <a href="{{ route('residence-data.index') }}" class="btn btn-outline-secondary">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Residence Data
            </a>
        </div>
    </div>
    
    <!-- BEGIN: Profile Info -->
    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                    <img alt="{{ $demographic->full_name }}" class="rounded-full" src="{{ asset('dist/images/profile-11.jpg') }}">
                    <div class="absolute mb-1 mr-1 flex items-center justify-center bottom-0 right-0 bg-primary rounded-full p-2"> 
                        <i class="w-4 h-4 text-white" data-lucide="camera"></i> 
                    </div>
                </div>
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">{{ $demographic->full_name }}</div>
                    <div class="text-slate-500">{{ $demographic->age ?? 'N/A' }} years old • {{ $demographic->sexInfo->name ?? 'N/A' }}</div>
                    <div class="text-slate-500 text-sm">{{ $demographic->geographicIdentification->barangay->name ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="font-medium text-center lg:text-left lg:mt-3">Contact Details</div>
                <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                    <div class="truncate sm:whitespace-normal flex items-center"> 
                        <i data-lucide="mail" class="w-4 h-4 mr-2"></i> 
                        {{ $demographic->geographicIdentification->email_address ?? 'No email' }} 
                    </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
                        <i data-lucide="phone" class="w-4 h-4 mr-2"></i> 
                        {{ $demographic->geographicIdentification->contact_number ?? 'No contact number' }} 
                    </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
                        <i data-lucide="home" class="w-4 h-4 mr-2"></i> 
                        {{ $demographic->geographicIdentification->house_number ?? 'N/A' }} {{ $demographic->geographicIdentification->street_name ?? '' }} 
                    </div>
                </div>
            </div>
            <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-slate-200/60 dark:border-darkmode-400 pt-5 lg:pt-0">
                <div class="font-medium text-center lg:text-left lg:mt-5">Family Information</div>
                <div class="flex items-center justify-center lg:justify-start mt-2">
                    <div class="mr-2 w-20 flex"> HH: <span class="ml-3 font-medium text-primary">{{ $demographic->household_number ?? 'N/A' }}</span> </div>
                </div>
                <div class="flex items-center justify-center lg:justify-start mt-2">
                    <div class="mr-2 w-20 flex"> Reg: <span class="ml-3 font-medium text-success">{{ $demographic->registry_number ?? 'N/A' }}</span> </div>
                </div>
                @if($demographic->familyIncome)
                <div class="flex items-center justify-center lg:justify-start mt-2">
                    <div class="mr-2 w-20 flex"> Income: <span class="ml-3 font-medium text-warning">₱{{ number_format($demographic->familyIncome->total_income ?? 0, 0) }}</span> </div>
                </div>
                @endif
            </div>
        </div>
        <ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center" role="tablist">
            <li id="personal-info-tab" class="nav-item" role="presentation"> 
                <a href="javascript:;" class="nav-link py-4 active" data-tw-target="#personal-info" aria-controls="personal-info" aria-selected="true" role="tab">Personal Info</a> 
            </li>
            <li id="residence-info-tab" class="nav-item" role="presentation"> 
                <a href="javascript:;" class="nav-link py-4" data-tw-target="#residence-info" aria-selected="false" role="tab">Residence</a> 
            </li>
            <li id="education-info-tab" class="nav-item" role="presentation"> 
                <a href="javascript:;" class="nav-link py-4" data-tw-target="#education-info" aria-selected="false" role="tab">Education</a> 
            </li>
            <li id="family-info-tab" class="nav-item" role="presentation"> 
                <a href="javascript:;" class="nav-link py-4" data-tw-target="#family-info" aria-selected="false" role="tab">Family</a> 
            </li>
        </ul>
    </div>
    <!-- END: Profile Info -->
    
    <div class="intro-y tab-content mt-5">
        <div id="personal-info" class="tab-pane active" role="tabpanel" aria-labelledby="personal-info-tab">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Personal Information -->
                <div class="intro-y box col-span-12 lg:col-span-6">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">Personal Information</h2>
                    </div>
                    <div class="p-5">
                        <div class="flex flex-col sm:flex-row">
                            <div class="mr-auto">
                                <div class="font-medium">Full Name</div>
                                <div class="text-slate-500 mt-1">{{ $demographic->full_name }}</div>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row mt-5">
                            <div class="mr-auto">
                                <div class="font-medium">Date of Birth</div>
                                <div class="text-slate-500 mt-1">{{ $demographic->date_of_birth ? $demographic->date_of_birth->format('M d, Y') : 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row mt-5">
                            <div class="mr-auto">
                                <div class="font-medium">Age</div>
                                <div class="text-slate-500 mt-1">{{ $demographic->age ?? 'N/A' }} years</div>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row mt-5">
                            <div class="mr-auto">
                                <div class="font-medium">Sex</div>
                                <div class="text-slate-500 mt-1">{{ $demographic->sexInfo->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row mt-5">
                            <div class="mr-auto">
                                <div class="font-medium">Marital Status</div>
                                <div class="text-slate-500 mt-1">{{ $demographic->maritalStatus->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row mt-5">
                            <div class="mr-auto">
                                <div class="font-medium">Registry Number</div>
                                <div class="text-slate-500 mt-1">{{ $demographic->registry_number ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Personal Information -->
                
                <!-- BEGIN: Place of Birth -->
                <div class="intro-y box col-span-12 lg:col-span-6">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">Place of Birth</h2>
                    </div>
                    <div class="p-5">
                        @if($demographic->placeOfBirth)
                            <div class="flex flex-col sm:flex-row">
                                <div class="mr-auto">
                                    <div class="font-medium">Place of Birth</div>
                                    <div class="text-slate-500 mt-1">{{ $demographic->placeOfBirth->place_of_birth ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row mt-5">
                                <div class="mr-auto">
                                    <div class="font-medium">Municipality</div>
                                    <div class="text-slate-500 mt-1">{{ $demographic->placeOfBirth->municipality_of_birth ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row mt-5">
                                <div class="mr-auto">
                                    <div class="font-medium">Province</div>
                                    <div class="text-slate-500 mt-1">{{ $demographic->placeOfBirth->province_of_birth ?? 'N/A' }}</div>
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
                <!-- END: Place of Birth -->
            </div>
        </div>
        
        <div id="residence-info" class="tab-pane" role="tabpanel" aria-labelledby="residence-info-tab">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Residence Information -->
                <div class="intro-y box col-span-12">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">Residence Information</h2>
                    </div>
                    <div class="p-5">
                        @if($demographic->geographicIdentification)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
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
                                </div>
                                <div class="space-y-4">
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
                            </div>
                        @else
                            <div class="text-slate-500 text-center py-4">
                                <i data-lucide="home" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                <p>No residence data available</p>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- END: Residence Information -->
            </div>
        </div>
        
        <div id="education-info" class="tab-pane" role="tabpanel" aria-labelledby="education-info-tab">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Education Information -->
                <div class="intro-y box col-span-12">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">Education Information</h2>
                    </div>
                    <div class="p-5">
                        @if($demographic->educationAndLiteracy)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
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
                                </div>
                                <div class="space-y-4">
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
                                </div>
                            </div>
                        @else
                            <div class="text-slate-500 text-center py-4">
                                <i data-lucide="graduation-cap" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                <p>No education data available</p>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- END: Education Information -->
            </div>
        </div>
        
        <div id="family-info" class="tab-pane" role="tabpanel" aria-labelledby="family-info-tab">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Family Information -->
                <div class="intro-y box col-span-12 lg:col-span-6">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">Family Relationships</h2>
                    </div>
                    <div class="p-5">
                        <div class="space-y-4">
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
                </div>
                <!-- END: Family Information -->
                
                <!-- BEGIN: Family Income -->
                <div class="intro-y box col-span-12 lg:col-span-6">
                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">Family Income</h2>
                    </div>
                    <div class="p-5">
                        @if($demographic->familyIncome)
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Current Family Income:</span>
                                    <span class="font-medium">₱{{ number_format($demographic->familyIncome->total_annual_income_current_family_members ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Former Family Income:</span>
                                    <span class="font-medium">₱{{ number_format($demographic->familyIncome->total_annual_income_current_former_family_members ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between border-t pt-2">
                                    <span class="text-slate-600 font-medium">Combined Total:</span>
                                    <span class="font-bold text-primary">₱{{ number_format($demographic->familyIncome->total_income ?? 0, 2) }}</span>
                                </div>
                            </div>
                        @else
                            <div class="text-slate-500 text-center py-4">
                                <i data-lucide="dollar-sign" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                <p>No family income data available</p>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- END: Family Income -->
            </div>
        </div>
    </div>
    
    <!-- Account Creation Section -->
    <div class="intro-y box mt-5">
        <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
            <h2 class="font-medium text-base mr-auto">Account Management</h2>
        </div>
        <div class="p-5">
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

            <div class="flex items-center justify-between">
                @if($existingUser)
                    <div class="flex items-center p-4 bg-green-50 border border-green-200 rounded-lg flex-1">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-green-800">Account Already Exists</p>
                            <p class="text-sm text-green-600">Username: {{ $existingUser->username }} | Role: {{ ucfirst($existingUser->role) }}</p>
                        </div>
                    </div>
                    <div class="ml-4">
                        <a href="{{ route('view.profile.other', $existingUser->id) }}" class="btn btn-sm btn-outline-primary">
                            <i data-lucide="eye" class="w-4 h-4 mr-2"></i> View Account
                        </a>
                    </div>
                @else
                    <div class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg flex-1">
                        <i data-lucide="user-plus" class="w-5 h-5 text-blue-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-blue-800">No Account Found</p>
                            <p class="text-sm text-blue-600">This resident does not have a user account yet.</p>
                        </div>
                    </div>
                    <div class="ml-4">
                        <a href="{{ route('create-account-demographic', $demographic->id) }}" class="btn btn-sm btn-primary">
                            <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i> Create Account
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
