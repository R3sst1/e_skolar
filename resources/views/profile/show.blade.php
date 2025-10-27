@extends('layouts.app')
@section('title', 'Profile Information')
@section('content')


<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-8">
        <!-- BEGIN: Profile Info -->
        <div class="intro-y box px-5 py-5">
            <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
                <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                        <img alt="{{ $user->first_name }}" class="rounded-full" src="{{ asset('Images/normalpicture.png') }}">
                    </div>
                    <div class="ml-5">
                        <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">{{ $user->first_name }} {{ $user->last_name }}</div>
                        <div class="text-slate-500">{{ ucfirst($user->role) }}</div>
                    </div>
                </div>
                <div class="flex mt-6 lg:mt-0 items-center lg:items-start flex-1 flex-col justify-center text-slate-500 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400">
                    <div class="truncate sm:whitespace-normal flex items-center mt-3">
                        <i data-lucide="user" class="w-4 h-4 mr-2"></i> Username: {{ $user->username }}
                    </div>
                    @if($user->email)
                    <div class="truncate sm:whitespace-normal flex items-center mt-3">
                        <i data-lucide="mail" class="w-4 h-4 mr-2"></i> Email: {{ $user->email }}
                    </div>
                    @endif
                    @if($user->phone_number)
                    <div class="truncate sm:whitespace-normal flex items-center mt-3">
                        <i data-lucide="phone" class="w-4 h-4 mr-2"></i> Contact: {{ $user->phone_number }}
                    </div>
                    @endif
                    @if($user->school)
                    <div class="truncate sm:whitespace-normal flex items-center mt-3">
                        <i data-lucide="book" class="w-4 h-4 mr-2"></i> School: {{ $user->school }}
                    </div>
                    @endif
                </div>
            </div>
            @if(Auth::id() === $user->id)
            <div class="flex flex-col lg:flex-row items-center p-5">
                <div class="w-full lg:w-auto flex flex-col justify-center items-center lg:items-start mt-4 lg:mt-0">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Edit Profile
                    </a>
                </div>
            </div>
            @endif
        </div>
        <!-- END: Profile Info -->

        <!-- BEGIN: Additional Information -->
        <div class="intro-y box mt-5">
            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">Additional Information</h2>
            </div>
            <div class="p-5">
                @if($user->barangay)
                <div class="flex flex-col sm:flex-row mt-4">
                    <div class="mr-auto">
                        <span class="font-medium">Barangay</span>
                        <div class="text-slate-500 mt-1">{{ $user->barangay }}</div>
                    </div>
                </div>
                @endif
                
                @if($user->age)
                <div class="flex flex-col sm:flex-row mt-4">
                    <div class="mr-auto">
                        <span class="font-medium">Age</span>
                        <div class="text-slate-500 mt-1">{{ $user->age }} years old</div>
                    </div>
                </div>
                @endif

                @if($user->created_at)
                <div class="flex flex-col sm:flex-row mt-4">
                    <div class="mr-auto">
                        <span class="font-medium">Member Since</span>
                        <div class="text-slate-500 mt-1">{{ $user->created_at->format('F d, Y') }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <!-- END: Additional Information -->
    </div>
</div>
@endsection 