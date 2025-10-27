@extends('layouts.app')
@section('title', '')
@section('content')
<div class="content">
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Create New Account
            @if($isFromDemographic)
                <span class="text-sm text-slate-500 font-normal">for {{ $demographicData->full_name ?? 'Resident' }}</span>
            @endif
        </h2>
        <div class="flex gap-3">
            <a href="{{ $isFromDemographic ? route('residence-data.index') : route('accounts.index') }}" class="btn btn-outline-secondary">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to {{ $isFromDemographic ? 'Residence Data' : 'Account Management' }}
            </a>
        </div>
    </div>

    <!-- Account Creation Form -->
    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="px-5 pb-5">
            <form method="POST" action="{{ route('accounts.store') }}">
                @csrf
                
                <!-- Personal Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-700 mb-4 flex items-center">
                        <i data-lucide="user" class="w-5 h-5 mr-2"></i>
                        Personal Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="first_name" class="form-label">First Name *</label>
                            <input type="text" 
                                   id="first_name"
                                   name="first_name" 
                                   value="{{ old('first_name') }}"
                                   class="form-control @error('first_name') border-danger @enderror" 
                                   placeholder="Enter first name"
                                   required>
                            @error('first_name') 
                                <div class="text-danger text-sm mt-1">{{ $message }}</div> 
                            @enderror
                        </div>
                        
                        <div>
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" 
                                   id="middle_name"
                                   name="middle_name" 
                                   value="{{ old('middle_name') }}"
                                   class="form-control @error('middle_name') border-danger @enderror" 
                                   placeholder="Enter middle name">
                            @error('middle_name') 
                                <div class="text-danger text-sm mt-1">{{ $message }}</div> 
                            @enderror
                        </div>
                        
                        <div>
                            <label for="last_name" class="form-label">Last Name *</label>
                            <input type="text" 
                                   id="last_name"
                                   name="last_name" 
                                   value="{{ old('last_name') }}"
                                   class="form-control @error('last_name') border-danger @enderror" 
                                   placeholder="Enter last name"
                                   required>
                            @error('last_name') 
                                <div class="text-danger text-sm mt-1">{{ $message }}</div> 
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Account Details -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-700 mb-4 flex items-center">
                        <i data-lucide="key" class="w-5 h-5 mr-2"></i>
                        Account Details
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Username -->
                        <div>
                            <label for="username" class="form-label">Username *</label>
                            <input type="text" 
                                   id="username"
                                   name="username" 
                                   value="{{ old('username') }}"
                                   class="form-control @error('username') border-danger @enderror" 
                                   placeholder="Enter username"
                                   required>
                            @error('username') 
                                <div class="text-danger text-sm mt-1">{{ $message }}</div> 
                            @enderror
                            <div class="text-sm text-slate-500 mt-1">
                                Username will be auto-generated from the name but can be edited.
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="form-label">Email (Optional)</label>
                            <input type="email" 
                                   id="email"
                                   name="email" 
                                   value="{{ old('email') }}"
                                   class="form-control @error('email') border-danger @enderror" 
                                   placeholder="Enter email address">
                            @error('email') 
                                <div class="text-danger text-sm mt-1">{{ $message }}</div> 
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <!-- Password -->
                        <div>
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" 
                                   id="password"
                                   name="password" 
                                   value="{{ old('password', 'password123') }}"
                                   class="form-control @error('password') border-danger @enderror" 
                                   placeholder="Enter password"
                                   required>
                            @error('password') 
                                <div class="text-danger text-sm mt-1">{{ $message }}</div> 
                            @enderror
                            <div class="text-sm text-slate-500 mt-1">
                                Default password is "password123" but can be changed.
                            </div>
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="form-label">Role *</label>
                            <select id="role" 
                                    name="role" 
                                    class="form-select @error('role') border-danger @enderror" 
                                    required>
                                <option value="">Select Role</option>
                                @if(Auth::user()->isSuperAdmin())
                                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                @endif
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="applicant" {{ old('role') == 'applicant' ? 'selected' : '' }}>Applicant</option>
                            </select>
                            @error('role') 
                                <div class="text-danger text-sm mt-1">{{ $message }}</div> 
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-700 mb-4 flex items-center">
                        <i data-lucide="info" class="w-5 h-5 mr-2"></i>
                        Additional Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Phone Number -->
                        <div>
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" 
                                   id="phone_number"
                                   name="phone_number" 
                                   value="{{ old('phone_number') }}"
                                   class="form-control @error('phone_number') border-danger @enderror" 
                                   placeholder="Enter phone number">
                            @error('phone_number') 
                                <div class="text-danger text-sm mt-1">{{ $message }}</div> 
                            @enderror
                        </div>

                        <!-- Barangay -->
                        <div>
                            <label for="barangay" class="form-label">Barangay</label>
                            <input type="text" 
                                   id="barangay"
                                   name="barangay" 
                                   value="{{ old('barangay') }}"
                                   class="form-control @error('barangay') border-danger @enderror" 
                                   placeholder="Enter barangay">
                            @error('barangay') 
                                <div class="text-danger text-sm mt-1">{{ $message }}</div> 
                            @enderror
                        </div>

                        <!-- Age -->
                        <div>
                            <label for="age" class="form-label">Age</label>
                            <input type="number" 
                                   id="age"
                                   name="age" 
                                   value="{{ old('age') }}"
                                   class="form-control @error('age') border-danger @enderror" 
                                   placeholder="Enter age"
                                   min="1" 
                                   max="120">
                            @error('age') 
                                <div class="text-danger text-sm mt-1">{{ $message }}</div> 
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                    <a href="{{ route('accounts.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i> Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Instructions -->
    <div class="intro-y box mt-5">
        <div class="px-5 py-4">
            <h4 class="font-semibold text-slate-800 mb-2 flex items-center">
                <i data-lucide="info" class="w-4 h-4 mr-2"></i>
                Instructions
            </h4>
            <ul class="text-sm text-slate-600 space-y-1">
                <li>• The user will receive their login credentials after account creation</li>
                <li>• They can change their password after first login</li>
                <li>• The account will have access based on the assigned role</li>
                <li>• Username is auto-generated but can be customized</li>
            </ul>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const firstNameInput = document.getElementById('first_name');
    const lastNameInput = document.getElementById('last_name');
    const usernameInput = document.getElementById('username');
    
    function generateUsername() {
        const firstName = firstNameInput.value.trim().toLowerCase();
        const lastName = lastNameInput.value.trim().toLowerCase();
        
        if (firstName && lastName) {
            const username = firstName + lastName;
            usernameInput.value = username;
        }
    }
    
    firstNameInput.addEventListener('input', generateUsername);
    lastNameInput.addEventListener('input', generateUsername);
});
</script>
@endsection