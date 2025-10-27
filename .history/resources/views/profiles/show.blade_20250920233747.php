@extends('layouts.app')
@section('title', 'Residence Profile - ' . $demographic->full_name)
@section('content')

<div class="container mx-auto px-4 py-6">
    
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Residence Profile</h1>
                <p class="text-slate-600 mt-1">Complete demographic and residence information</p>
            </div>
            <a href="{{ route('residence-data.index') }}" class="btn btn-outline-secondary">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Residence Data
            </a>
        </div>
    </div>

    <!-- Profile Header Card -->
    <div class="box p-6 mb-6">
        <div class="flex items-center">
            <div class="w-20 h-20 bg-primary rounded-full flex items-center justify-center mr-6">
                <i data-lucide="user" class="w-10 h-10 text-white"></i>
            </div>
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-slate-800">{{ $demographic->full_name }}</h2>
                <div class="flex items-center gap-4 mt-2 text-slate-600">
                    <span class="flex items-center">
                        <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                        {{ $demographic->age ?? 'N/A' }} years old
                    </span>
                    <span class="flex items-center">
                        <i data-lucide="users" class="w-4 h-4 mr-1"></i>
                        {{ $demographic->sex ?? 'N/A' }}
                    </span>
                    @if($demographic->maritalStatus)
                    <span class="flex items-center">
                        <i data-lucide="heart" class="w-4 h-4 mr-1"></i>
                        {{ $demographic->maritalStatus->name }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Personal Information -->
        <div class="box p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                <i data-lucide="user" class="w-5 h-5 mr-2 text-primary"></i>
                Personal Information
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-slate-600">Full Name:</span>
                    <span class="font-medium">{{ $demographic->full_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Sex:</span>
                    <span class="font-medium">{{ $demographic->sex ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Date of Birth:</span>
                    <span class="font-medium">{{ $demographic->date_of_birth ? $demographic->date_of_birth->format('M d, Y') : 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Age:</span>
                    <span class="font-medium">{{ $demographic->age ?? 'N/A' }}</span>
                </div>
                @if($demographic->maritalStatus)
                <div class="flex justify-between">
                    <span class="text-slate-600">Marital Status:</span>
                    <span class="font-medium">{{ $demographic->maritalStatus->name }}</span>
                </div>
                @endif
                @if($demographic->placeOfBirth)
                <div class="flex justify-between">
                    <span class="text-slate-600">Place of Birth:</span>
                    <span class="font-medium">{{ $demographic->placeOfBirth->full_place_of_birth }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-slate-600">Birth Registered:</span>
                    <span class="font-medium">
                        @if($demographic->birth_registered_in_local_registry)
                            <span class="text-success">Yes</span>
                        @else
                            <span class="text-danger">No</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Family Information -->
        <div class="box p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                <i data-lucide="users" class="w-5 h-5 mr-2 text-primary"></i>
                Family Information
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-slate-600">Household Number:</span>
                    <span class="font-medium">{{ $demographic->household_number ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Line Number:</span>
                    <span class="font-medium">{{ $demographic->line_number ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Registry Number:</span>
                    <span class="font-medium">{{ $demographic->registry_number ?? 'N/A' }}</span>
                </div>
                @if($demographic->familyHeadRelationship)
                <div class="flex justify-between">
                    <span class="text-slate-600">Relationship to Head:</span>
                    <span class="font-medium">{{ $demographic->familyHeadRelationship->name }}</span>
                </div>
                @endif
                @if($demographic->nuclearFamilyRelationship)
                <div class="flex justify-between">
                    <span class="text-slate-600">Nuclear Family Relationship:</span>
                    <span class="font-medium">{{ $demographic->nuclearFamilyRelationship->name }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-slate-600">Nuclear Family Assignment:</span>
                    <span class="font-medium">{{ $demographic->nuclear_family_assignment ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Geographic Information -->
        <div class="box p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                <i data-lucide="map-pin" class="w-5 h-5 mr-2 text-primary"></i>
                Geographic Information
            </h3>
            @if($demographic->geographicIdentification)
            <div class="space-y-3">
                @if($demographic->geographicIdentification->barangay)
                <div class="flex justify-between">
                    <span class="text-slate-600">Barangay:</span>
                    <span class="font-medium">{{ $demographic->geographicIdentification->barangay->name }}</span>
                </div>
                @endif
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
                    <span class="text-slate-600">Floor Number:</span>
                    <span class="font-medium">{{ $demographic->geographicIdentification->floor_number ?? 'N/A' }}</span>
                </div>
                @if($demographic->geographicIdentification->contact_number)
                <div class="flex justify-between">
                    <span class="text-slate-600">Contact Number:</span>
                    <span class="font-medium">{{ $demographic->geographicIdentification->contact_number }}</span>
                </div>
                @endif
                @if($demographic->geographicIdentification->email_address)
                <div class="flex justify-between">
                    <span class="text-slate-600">Email Address:</span>
                    <span class="font-medium">{{ $demographic->geographicIdentification->email_address }}</span>
                </div>
                @endif
                @if($demographic->geographicIdentification->latitude && $demographic->geographicIdentification->longitude)
                <div class="flex justify-between">
                    <span class="text-slate-600">Coordinates:</span>
                    <span class="font-medium">{{ $demographic->geographicIdentification->latitude }}, {{ $demographic->geographicIdentification->longitude }}</span>
                </div>
                @endif
            </div>
            @else
            <div class="text-center py-8 text-slate-500">
                <i data-lucide="map-pin" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p>No geographic information available</p>
            </div>
            @endif
        </div>

        <!-- Education Information -->
        <div class="box p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                <i data-lucide="graduation-cap" class="w-5 h-5 mr-2 text-primary"></i>
                Education Information
            </h3>
            @if($demographic->educationAndLiteracy)
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-slate-600">Basic Literacy:</span>
                    <span class="font-medium">{{ $demographic->educationAndLiteracy->basic_literacy ?? 'N/A' }}</span>
                </div>
                @if($demographic->educationAndLiteracy->gradeYear)
                <div class="flex justify-between">
                    <span class="text-slate-600">Grade/Year:</span>
                    <span class="font-medium">{{ $demographic->educationAndLiteracy->gradeYear->description ?? 'N/A' }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-slate-600">School Attendance:</span>
                    <span class="font-medium">
                        @if($demographic->educationAndLiteracy->current_school_attendance)
                            <span class="text-success">Currently Attending</span>
                        @else
                            <span class="text-warning">Not Attending</span>
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Type of School:</span>
                    <span class="font-medium">{{ $demographic->educationAndLiteracy->type_of_school ?? 'N/A' }}</span>
                </div>
                @if($demographic->educationAndLiteracy->currentGradeYear)
                <div class="flex justify-between">
                    <span class="text-slate-600">Current Grade/Year:</span>
                    <span class="font-medium">{{ $demographic->educationAndLiteracy->currentGradeYear->description ?? 'N/A' }}</span>
                </div>
                @endif
                @if($demographic->educationAndLiteracy->reason_not_attending_school)
                <div class="flex justify-between">
                    <span class="text-slate-600">Reason Not Attending:</span>
                    <span class="font-medium">{{ $demographic->educationAndLiteracy->reason_not_attending_school }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-slate-600">TVL Graduate:</span>
                    <span class="font-medium">
                        @if($demographic->educationAndLiteracy->graduate_of_TVL)
                            <span class="text-success">Yes</span>
                        @else
                            <span class="text-danger">No</span>
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Currently in TVET:</span>
                    <span class="font-medium">
                        @if($demographic->educationAndLiteracy->is_currently_attending_TVET_skills_dev)
                            <span class="text-success">Yes</span>
                        @else
                            <span class="text-danger">No</span>
                        @endif
                    </span>
                </div>
            </div>
            @else
            <div class="text-center py-8 text-slate-500">
                <i data-lucide="graduation-cap" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p>No education information available</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Family Income Information -->
    @if($demographic->familyIncome)
    <div class="box p-6 mt-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
            <i data-lucide="dollar-sign" class="w-5 h-5 mr-2 text-primary"></i>
            Family Income Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="space-y-3">
                <h4 class="font-medium text-slate-700">Employment Income</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Salaries & Wages:</span>
                        <span class="font-medium">₱{{ number_format($demographic->familyIncome->salaries_and_wages ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Commissions, Tips, Bonuses:</span>
                        <span class="font-medium">₱{{ number_format($demographic->familyIncome->commissions_tips_bonuses_etc ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Other Forms of Income:</span>
                        <span class="font-medium">₱{{ number_format($demographic->familyIncome->other_forms_of_income ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="space-y-3">
                <h4 class="font-medium text-slate-700">Business & Professional</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Business Receipts:</span>
                        <span class="font-medium">₱{{ number_format($demographic->familyIncome->net_receipts_from_business ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Professional Practice:</span>
                        <span class="font-medium">₱{{ number_format($demographic->familyIncome->net_receipts_from_professional_practice ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Farm & Livestock:</span>
                        <span class="font-medium">₱{{ number_format($demographic->familyIncome->net_receipts_from_farm_and_other_livestock ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="space-y-3">
                <h4 class="font-medium text-slate-700">Government Benefits</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">4Ps Benefits:</span>
                        <span class="font-medium">₱{{ number_format($demographic->familyIncome->{'4Ps_benefits'} ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Social Pension:</span>
                        <span class="font-medium">₱{{ number_format($demographic->familyIncome->social_pension_benefits ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Social Amelioration:</span>
                        <span class="font-medium">₱{{ number_format($demographic->familyIncome->social_amelioration_benefits ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Income Summary -->
        <div class="mt-6 pt-6 border-t border-slate-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="text-center p-4 bg-slate-50 rounded-lg">
                    <h4 class="font-medium text-slate-700 mb-2">Current Family Members</h4>
                    <p class="text-2xl font-bold text-primary">₱{{ number_format($demographic->familyIncome->total_annual_income_current_family_members ?? 0, 2) }}</p>
                </div>
                <div class="text-center p-4 bg-slate-50 rounded-lg">
                    <h4 class="font-medium text-slate-700 mb-2">Former Family Members</h4>
                    <p class="text-2xl font-bold text-slate-600">₱{{ number_format($demographic->familyIncome->total_annual_income_current_former_family_members ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="box p-6 mt-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
            <i data-lucide="dollar-sign" class="w-5 h-5 mr-2 text-primary"></i>
            Family Income Information
        </h3>
        <div class="text-center py-8 text-slate-500">
            <i data-lucide="dollar-sign" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
            <p>No income information available</p>
        </div>
    </div>
    @endif

    <!-- Additional Information -->
    <div class="box p-6 mt-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
            <i data-lucide="info" class="w-5 h-5 mr-2 text-primary"></i>
            Additional Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-slate-600">Enumeration Area:</span>
                    <span class="font-medium">{{ $demographic->geographicIdentification->enumeration_area_number ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Building Serial:</span>
                    <span class="font-medium">{{ $demographic->geographicIdentification->building_serial_number ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Housing Unit Serial:</span>
                    <span class="font-medium">{{ $demographic->geographicIdentification->housing_unit_serial_number ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-slate-600">Household Serial:</span>
                    <span class="font-medium">{{ $demographic->geographicIdentification->household_serial_number ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Respondent Line:</span>
                    <span class="font-medium">{{ $demographic->geographicIdentification->respondent_line_number ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Household Head ID:</span>
                    <span class="font-medium">{{ $demographic->geographicIdentification->household_head_id ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
