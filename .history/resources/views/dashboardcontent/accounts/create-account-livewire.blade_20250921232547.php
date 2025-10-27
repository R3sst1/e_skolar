<div>
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Create Account</h1>
                    @if($isFromDemographic)
                        <p class="text-slate-600 mt-1">You are creating an account for <strong>{{ $demographicData->full_name ?? 'Unknown Resident' }}</strong></p>
                        <p class="text-sm text-slate-500">Demographics ID: {{ $demographicData->id ?? 'N/A' }}</p>
                    @else
                        <p class="text-slate-600 mt-1">You are creating an account for <strong>{{ $residenceData->full_name ?? 'Unknown Applicant' }}</strong></p>
                    @endif
                </div>
                <a href="{{ $isFromDemographic ? route('residence-data.index') : route('residence-data.index') }}" class="btn btn-outline-secondary">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to {{ $isFromDemographic ? 'Residence Data' : 'Residence Data' }}
                </a>
            </div>
        </div>

        <!-- Account Creation Form -->
        <div class="max-w-2xl mx-auto">
            <div class="box p-6">
                <form wire:submit.prevent="createAccount">
                    <!-- Applicant Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">{{ $isFromDemographic ? 'Resident' : 'Applicant' }} Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Full Name</label>
                                <input type="text" value="{{ $isFromDemographic ? ($demographicData->full_name ?? 'N/A') : ($residenceData->full_name ?? 'N/A') }}" class="form-control" readonly>
                            </div>
                            <div>
                                <label class="form-label">Contact Number</label>
                                <input type="text" value="{{ $isFromDemographic ? ($demographicData->geographicIdentification->contact_number ?? 'N/A') : ($residenceData->contact_number ?? 'N/A') }}" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Account Details -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Account Details</h3>
                        
                        <!-- Username -->
                        <div class="mb-4">
                            <label for="username" class="form-label">Username *</label>
                            <input type="text" 
                                   id="username"
                                   wire:model="username" 
                                   class="form-control @error('username') border-danger @enderror" 
                                   placeholder="Enter username">
                            @error('username') 
                                <div class="text-danger text-sm mt-1">{{ $message }}</div> 
                            @enderror
                            <div class="text-sm text-slate-500 mt-1">
                                This will be used for login. Pre-filled with applicant's name but can be edited.
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label">Email (Optional)</label>
                            <input type="email" 
                                   id="email"
                                   wire:model="email" 
                                   class="form-control @error('email') border-danger @enderror" 
                                   placeholder="Enter email address">
                            @error('email') 
                                <div class="text-danger text-sm mt-1">{{ $message }}</div> 
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" 
                                   id="password"
                                   wire:model="password" 
                                   class="form-control @error('password') border-danger @enderror" 
                                   placeholder="Enter password">
                            @error('password') 
                                <div class="text-danger text-sm mt-1">{{ $message }}</div> 
                            @enderror
                            <div class="text-sm text-slate-500 mt-1">
                                Default password is "password123" but can be changed.
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-6">
                            <label for="confirmPassword" class="form-label">Confirm Password *</label>
                            <input type="password" 
                                   id="confirmPassword"
                                   wire:model="confirmPassword" 
                                   class="form-control @error('confirmPassword') border-danger @enderror" 
                                   placeholder="Confirm password">
                            @error('confirmPassword') 
                                <div class="text-danger text-sm mt-1">{{ $message }}</div> 
                            @enderror
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-semibold text-blue-800 mb-2">Account Information</h4>
                        <div class="text-sm text-blue-700 space-y-1">
                            <p><strong>Role:</strong> Applicant</p>
                            <p><strong>Access Level:</strong> Applicant Dashboard</p>
                            <p><strong>Permissions:</strong> View applications, submit documents, track status</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('residence-data.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i> Create Account
                        </button>
                    </div>
                </form>
            </div>

            <!-- Instructions -->
            <div class="mt-6 box p-4 bg-slate-50">
                <h4 class="font-semibold text-slate-800 mb-2">Instructions</h4>
                <ul class="text-sm text-slate-600 space-y-1">
                    <li>• The applicant will receive their login credentials after account creation</li>
                    <li>• They can change their password after first login</li>
                    <li>• The account will have access to the Applicant Dashboard</li>
                    <li>• All existing application data will be linked to this account</li>
                </ul>
            </div>
        </div>
    </div>
</div>
