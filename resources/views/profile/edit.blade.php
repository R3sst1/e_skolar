@extends('layouts.app')
@section('title', 'Edit Profile')
@section('content')
    @if(session('setup'))
    <div class="intro-y box px-5 py-5 mt-5">
        <div class="flex items-center">
            <div class="ml-4">
                <div class="text-base font-medium">Welcome to SureScholarShip! 🎓</div>
                <div class="text-slate-500 mt-1">
                    To get started with your scholarship application, please complete your profile information below. 
                    This information will be used in your scholarship applications.
                </div>
            </div>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')
        <div class="intro-y box px-5 pt-5 mt-5">
            <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
                <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                        <img alt="Profile Picture" class="rounded-full" src="{{ asset('Images/normalpicture.png') }}">
                        <div class="absolute mb-1 mr-1 flex items-center justify-center bottom-0 right-0 bg-primary rounded-full p-2"> <i class="w-4 h-4 text-white" data-lucide="camera"></i> </div>
                    </div>
                    <div class="ml-5">
                        <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg mb-2">
                            {{ Auth::user()->first_name }} {{ Auth::user()->middle_name }} {{ Auth::user()->last_name }}
                        </div>
                        <!-- Name Fields -->
                        <div class="grid grid-cols-1 gap-2">
                            <input name="first_name" type="text" class="form-control text-sm" value="{{ old('first_name', Auth::user()->first_name) }}" placeholder="First Name">
                            <x-input-error class="mt-1" :messages="$errors->get('first_name')" />
                            <input name="middle_name" type="text" class="form-control text-sm" value="{{ old('middle_name', Auth::user()->middle_name) }}" placeholder="Middle Name">
                            <x-input-error class="mt-1" :messages="$errors->get('middle_name')" />
                            <input name="last_name" type="text" class="form-control text-sm" value="{{ old('last_name', Auth::user()->last_name) }}" placeholder="Last Name">
                            <x-input-error class="mt-1" :messages="$errors->get('last_name')" />
                        </div>
                        <div class="font-medium text-center lg:text-left lg:mt-3">School</div>
                        <input name="school" type="text" class="form-control mt-1 block w-full" value="{{ old('school', Auth::user()->school) }}">
                        <x-input-error class="mt-2" :messages="$errors->get('school')" />
                    </div>
                </div>
                <div class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                    <div class="font-medium text-center lg:text-left lg:mt-3">Contact Details</div>
                    <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                        <div class="truncate sm:whitespace-normal flex items-center">
                            <i data-lucide="mail" class="w-4 h-4 mr-2"></i>
                            <input name="email" type="email" class="form-control mt-1 block w-full" value="{{ old('email', Auth::user()->email) }}" placeholder="Email (Optional)">
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        <div class="truncate sm:whitespace-normal flex items-center mt-3">
                            <i data-lucide="phone" class="w-4 h-4 mr-2"></i>
                            <input name="phone_number" type="text" class="form-control mt-1 block w-full" value="{{ old('phone_number', Auth::user()->phone_number) }}" placeholder="Phone Number">
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
                    </div>
                </div>
                <div class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                    <div class="font-medium text-center lg:whitespace-normal flex items-center:mt-3"><i data-lucide="map-pin" class="w-4 h-4 mr-2"></i>Location</div>
                    <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                        <input name="barangay" type="text" class="form-control mt-1 block w-full" value="{{ old('barangay', Auth::user()->barangay) }}" placeholder="Barangay">
                        <x-input-error class="mt-2" :messages="$errors->get('barangay')" />
                        <input name="age" type="number" min="1" class="form-control mt-1 block w-full mt-3" value="{{ old('age', Auth::user()->age) }}" placeholder="Age">
                        <x-input-error class="mt-2" :messages="$errors->get('age')" />
                    </div>
                </div>
            </div>
            
            <!-- Siblings Section -->
            <div class="border-t border-slate-200/60 dark:border-darkmode-400 pt-5 -mx-5 px-5">
                <div class="font-medium text-center lg:text-left lg:mt-3 mb-4">Siblings Information</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Number of Brothers</label>
                        <input name="siblings_boy" type="number" min="0" max="10" class="form-control mt-1 block w-full" value="{{ old('siblings_boy', Auth::user()->siblings_boy) }}" placeholder="0">
                        <x-input-error class="mt-2" :messages="$errors->get('siblings_boy')" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Number of Sisters</label>
                        <input name="siblings_girl" type="number" min="0" max="10" class="form-control mt-1 block w-full" value="{{ old('siblings_girl', Auth::user()->siblings_girl) }}" placeholder="0">
                        <x-input-error class="mt-2" :messages="$errors->get('siblings_girl')" />
                    </div>
                </div>
                
                <!-- Dynamic Sibling Names -->
                <div id="sibling-names-container" class="mt-4">
                    <!-- Brother Names -->
                    <div id="brother-names" class="mb-4" style="display: none;">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Brother Names</h4>
                        <div id="brother-names-list" class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <!-- Dynamic brother name fields will be added here -->
                        </div>
                    </div>
                    
                    <!-- Sister Names -->
                    <div id="sister-names" class="mb-4" style="display: none;">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sister Names</h4>
                        <div id="sister-names-list" class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <!-- Dynamic sister name fields will be added here -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Parents Section -->
            <div class="border-t border-slate-200/60 dark:border-darkmode-400 pt-5 -mx-5 px-5">
                <div class="font-medium text-center lg:text-left lg:mt-3 mb-2">Parents Information</div>
                <div class="text-sm text-slate-500 mb-4">Recommended if you want to apply for scholarship</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mother's Maiden Name</label>
                        <input name="mother_maiden_name" type="text" class="form-control mt-1 block w-full" value="{{ old('mother_maiden_name', Auth::user()->mother_maiden_name) }}" placeholder="Mother's Maiden Name">
                        <x-input-error class="mt-2" :messages="$errors->get('mother_maiden_name')" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Father's Name</label>
                        <input name="father_name" type="text" class="form-control mt-1 block w-full" value="{{ old('father_name', Auth::user()->father_name) }}" placeholder="Father's Name">
                        <x-input-error class="mt-2" :messages="$errors->get('father_name')" />
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end mt-6 space-x-3 pb-5">
                <a href="{{ route('profile.show') }}" onclick="return confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')" class="btn btn-outline-secondary w-24">
                    <i data-lucide="x" class="w-4 h-4 mr-2"></i> Cancel
                </a>
                <button type="submit" onclick="return confirm('Save changes to profile?')" class="btn btn-primary w-24">
                    <i data-lucide="check" class="w-4 h-4 mr-2"></i> Save
                </button>
            </div>
        </div>
    </form>
    
    <!-- Password Change Section -->
    <div class="intro-y box px-5 pt-5 mt-5">
        @include('profile.partials.update-password-form')
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const brothersInput = document.querySelector('input[name="siblings_boy"]');
            const sistersInput = document.querySelector('input[name="siblings_girl"]');
            const brotherNamesDiv = document.getElementById('brother-names');
            const sisterNamesDiv = document.getElementById('sister-names');
            const brotherNamesList = document.getElementById('brother-names-list');
            const sisterNamesList = document.getElementById('sister-names-list');
            
            // Get existing sibling names from PHP
            const existingBrotherNames = @json(Auth::user()->brother_names ?? []);
            const existingSisterNames = @json(Auth::user()->sister_names ?? []);
            
            function updateSiblingNames() {
                const brothersCount = parseInt(brothersInput.value) || 0;
                const sistersCount = parseInt(sistersInput.value) || 0;
                
                // Update brother names
                brotherNamesList.innerHTML = '';
                if (brothersCount > 0) {
                    brotherNamesDiv.style.display = 'block';
                    for (let i = 1; i <= brothersCount; i++) {
                        const div = document.createElement('div');
                        const existingName = existingBrotherNames[i-1] || '';
                        div.innerHTML = `
                            <input name="brother_names[]" type="text" 
                                   class="form-control text-sm" 
                                   placeholder="Brother ${i} Name" 
                                   value="${existingName}">
                        `;
                        brotherNamesList.appendChild(div);
                    }
                } else {
                    brotherNamesDiv.style.display = 'none';
                }
                
                // Update sister names
                sisterNamesList.innerHTML = '';
                if (sistersCount > 0) {
                    sisterNamesDiv.style.display = 'block';
                    for (let i = 1; i <= sistersCount; i++) {
                        const div = document.createElement('div');
                        const existingName = existingSisterNames[i-1] || '';
                        div.innerHTML = `
                            <input name="sister_names[]" type="text" 
                                   class="form-control text-sm" 
                                   placeholder="Sister ${i} Name" 
                                   value="${existingName}">
                        `;
                        sisterNamesList.appendChild(div);
                    }
                } else {
                    sisterNamesDiv.style.display = 'none';
                }
            }
            
            // Add event listeners
            brothersInput.addEventListener('input', updateSiblingNames);
            sistersInput.addEventListener('input', updateSiblingNames);
            
            // Initialize on page load
            updateSiblingNames();
        });
    </script>
@endsection
