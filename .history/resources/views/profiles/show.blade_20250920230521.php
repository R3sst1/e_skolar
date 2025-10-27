@extends('layouts.app')
@section('title', 'Scholar Profile')
@section('content')

<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Scholar Profile</h1>
                <p class="text-slate-600 mt-1">Complete demographic and academic information</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
                </a>
                @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                <button class="btn btn-primary">
                    <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Edit Profile
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Personal Information Card -->
        <div class="box p-6">
            <div class="flex items-center mb-4">
                <i data-lucide="user" class="w-5 h-5 text-primary mr-2"></i>
                <h2 class="text-lg font-semibold text-slate-800">Personal Information</h2>
            </div>
            
            @if($demographic)
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Full Name:</span>
                    <span class="text-slate-800">{{ $demographic->first_name ?? '' }} {{ $demographic->middle_name ?? '' }} {{ $demographic->last_name ?? '' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Sex:</span>
                    <span class="text-slate-800">{{ $demographic->sex ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Date of Birth:</span>
                    <span class="text-slate-800">{{ $demographic->date_of_birth ? \Carbon\Carbon::parse($demographic->date_of_birth)->format('M d, Y') : 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Age:</span>
                    <span class="text-slate-800">{{ $demographic->age ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Marital Status:</span>
                    <span class="text-slate-800">{{ $demographic->maritalStatus->name ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2">
                    <span class="text-slate-600 font-medium">Place of Birth:</span>
                    <span class="text-slate-800">{{ $demographic->placeOfBirth->name ?? 'No data available' }}</span>
                </div>
            </div>
            @else
            <div class="text-center py-8 text-slate-500">
                <i data-lucide="user-x" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p>No demographic data available</p>
            </div>
            @endif
        </div>

        <!-- Residence Information Card -->
        <div class="box p-6">
            <div class="flex items-center mb-4">
                <i data-lucide="map-pin" class="w-5 h-5 text-primary mr-2"></i>
                <h2 class="text-lg font-semibold text-slate-800">Residence Information</h2>
            </div>
            
            @if($geographic)
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Region:</span>
                    <span class="text-slate-800">{{ $geographic->region ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Province:</span>
                    <span class="text-slate-800">{{ $geographic->province ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">City:</span>
                    <span class="text-slate-800">{{ $geographic->city ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Barangay:</span>
                    <span class="text-slate-800">{{ $geographic->barangay ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Sitio/Purok:</span>
                    <span class="text-slate-800">{{ $geographic->sitio_purok ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Subdivision/Village:</span>
                    <span class="text-slate-800">{{ $geographic->subdivision_village ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Street Name:</span>
                    <span class="text-slate-800">{{ $geographic->street_name ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">House Number:</span>
                    <span class="text-slate-800">{{ $geographic->house_number ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Block/Lot:</span>
                    <span class="text-slate-800">{{ $geographic->block_lot ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Contact Number:</span>
                    <span class="text-slate-800">{{ $geographic->contact_number ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2">
                    <span class="text-slate-600 font-medium">Email:</span>
                    <span class="text-slate-800">{{ $geographic->email ?? 'No data available' }}</span>
                </div>
            </div>
            @else
            <div class="text-center py-8 text-slate-500">
                <i data-lucide="map-pin" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p>No residence data available</p>
            </div>
            @endif
        </div>

        <!-- Education Information Card -->
        <div class="box p-6">
            <div class="flex items-center mb-4">
                <i data-lucide="graduation-cap" class="w-5 h-5 text-primary mr-2"></i>
                <h2 class="text-lg font-semibold text-slate-800">Education Information</h2>
            </div>
            
            @if($education)
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Current Grade/Year:</span>
                    <span class="text-slate-800">{{ $education->gradeYear->name ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">School Attendance:</span>
                    <span class="text-slate-800">{{ $education->school_attendance ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Type of School:</span>
                    <span class="text-slate-800">{{ $education->type_of_school ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Literacy:</span>
                    <span class="text-slate-800">{{ $education->literacy ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2">
                    <span class="text-slate-600 font-medium">TVL/TVET Details:</span>
                    <span class="text-slate-800">{{ $education->tvl_tvet_details ?? 'No data available' }}</span>
                </div>
            </div>
            @else
            <div class="text-center py-8 text-slate-500">
                <i data-lucide="graduation-cap" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p>No education data available</p>
            </div>
            @endif
        </div>

        <!-- Family & Household Information Card -->
        <div class="box p-6">
            <div class="flex items-center mb-4">
                <i data-lucide="users" class="w-5 h-5 text-primary mr-2"></i>
                <h2 class="text-lg font-semibold text-slate-800">Family & Household</h2>
            </div>
            
            @if($familyHeadRelationship || $nuclearFamilyRelationship)
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Family Relationship:</span>
                    <span class="text-slate-800">{{ $familyHeadRelationship->name ?? $nuclearFamilyRelationship->name ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Household Number:</span>
                    <span class="text-slate-800">{{ $demographic->household_number ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Head of Household:</span>
                    <span class="text-slate-800">{{ $demographic->head_of_household ?? 'No data available' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2">
                    <span class="text-slate-600 font-medium">Nuclear Family Relationship:</span>
                    <span class="text-slate-800">{{ $nuclearFamilyRelationship->name ?? 'No data available' }}</span>
                </div>
            </div>
            @else
            <div class="text-center py-8 text-slate-500">
                <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p>No family data available</p>
            </div>
            @endif
        </div>

        <!-- Income Information Card -->
        <div class="box p-6 lg:col-span-2">
            <div class="flex items-center mb-4">
                <i data-lucide="dollar-sign" class="w-5 h-5 text-primary mr-2"></i>
                <h2 class="text-lg font-semibold text-slate-800">Income Information</h2>
            </div>
            
            @if($familyIncome)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-slate-50 p-4 rounded-lg">
                    <div class="text-sm text-slate-600 mb-1">Monthly Income</div>
                    <div class="text-lg font-semibold text-slate-800">₱{{ number_format($familyIncome->monthly_income ?? 0, 2) }}</div>
                </div>
                
                <div class="bg-slate-50 p-4 rounded-lg">
                    <div class="text-sm text-slate-600 mb-1">Annual Income</div>
                    <div class="text-lg font-semibold text-slate-800">₱{{ number_format($familyIncome->annual_income ?? 0, 2) }}</div>
                </div>
                
                <div class="bg-slate-50 p-4 rounded-lg">
                    <div class="text-sm text-slate-600 mb-1">Income Source</div>
                    <div class="text-lg font-semibold text-slate-800">{{ $familyIncome->income_source ?? 'No data available' }}</div>
                </div>
                
                <div class="bg-slate-50 p-4 rounded-lg">
                    <div class="text-sm text-slate-600 mb-1">Employment Status</div>
                    <div class="text-lg font-semibold text-slate-800">{{ $familyIncome->employment_status ?? 'No data available' }}</div>
                </div>
                
                <div class="bg-slate-50 p-4 rounded-lg">
                    <div class="text-sm text-slate-600 mb-1">Number of Dependents</div>
                    <div class="text-lg font-semibold text-slate-800">{{ $familyIncome->number_of_dependents ?? 'No data available' }}</div>
                </div>
                
                <div class="bg-slate-50 p-4 rounded-lg">
                    <div class="text-sm text-slate-600 mb-1">Additional Income</div>
                    <div class="text-lg font-semibold text-slate-800">₱{{ number_format($familyIncome->additional_income ?? 0, 2) }}</div>
                </div>
            </div>
            @else
            <div class="text-center py-8 text-slate-500">
                <i data-lucide="dollar-sign" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p>No income data available</p>
            </div>
            @endif
        </div>

        <!-- Geographic Coordinates Card (if available) -->
        @if($geographic && ($geographic->latitude || $geographic->longitude))
        <div class="box p-6 lg:col-span-2">
            <div class="flex items-center mb-4">
                <i data-lucide="navigation" class="w-5 h-5 text-primary mr-2"></i>
                <h2 class="text-lg font-semibold text-slate-800">Geographic Coordinates</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-slate-50 p-4 rounded-lg">
                    <div class="text-sm text-slate-600 mb-1">Latitude</div>
                    <div class="text-lg font-semibold text-slate-800">{{ $geographic->latitude ?? 'No data available' }}</div>
                </div>
                
                <div class="bg-slate-50 p-4 rounded-lg">
                    <div class="text-sm text-slate-600 mb-1">Longitude</div>
                    <div class="text-lg font-semibold text-slate-800">{{ $geographic->longitude ?? 'No data available' }}</div>
                </div>
            </div>
            
            @if($geographic->latitude && $geographic->longitude)
            <div class="mt-4">
                <a href="https://www.google.com/maps?q={{ $geographic->latitude }},{{ $geographic->longitude }}" 
                   target="_blank" 
                   class="btn btn-outline-primary">
                    <i data-lucide="external-link" class="w-4 h-4 mr-2"></i> View on Google Maps
                </a>
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="mt-8 flex justify-center gap-4">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to List
        </a>
        
        @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
        <button class="btn btn-primary">
            <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Edit Profile
        </button>
        
        <button class="btn btn-outline-primary">
            <i data-lucide="download" class="w-4 h-4 mr-2"></i> Export Profile
        </button>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Add any additional JavaScript functionality here
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any interactive elements
        console.log('Profile view loaded');
    });
</script>
@endpush
