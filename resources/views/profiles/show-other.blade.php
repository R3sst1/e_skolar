@extends('layouts.app')

@section('title', 'User Profile')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">User Profile</h2>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 lg:col-span-8">
        <div class="intro-y box p-5">
            <h3 class="font-medium text-base mb-4">Personal Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">First Name</label>
                    <div class="form-control">{{ $user->first_name ?? 'N/A' }}</div>
                </div>
                
                <div>
                    <label class="form-label">Last Name</label>
                    <div class="form-control">{{ $user->last_name ?? 'N/A' }}</div>
                </div>
                
                <div>
                    <label class="form-label">Middle Name</label>
                    <div class="form-control">{{ $user->middle_name ?? 'N/A' }}</div>
                </div>
                
                <div>
                    <label class="form-label">Username</label>
                    <div class="form-control">{{ $user->username ?? 'N/A' }}</div>
                </div>
                
                <div>
                    <label class="form-label">Email</label>
                    <div class="form-control">{{ $user->email ?? 'N/A' }}</div>
                </div>
                
                <div>
                    <label class="form-label">Phone Number</label>
                    <div class="form-control">{{ $user->phone_number ?? 'N/A' }}</div>
                </div>
                
                <div>
                    <label class="form-label">Barangay</label>
                    <div class="form-control">{{ $user->barangay ?? 'N/A' }}</div>
                </div>
                
                <div>
                    <label class="form-label">Age</label>
                    <div class="form-control">{{ $user->age ?? 'N/A' }}</div>
                </div>
                
                <div>
                    <label class="form-label">Role</label>
                    <div class="form-control">
                        <span class="px-2 py-1 rounded-full text-xs 
                            @if($user->role === 'super_admin') bg-danger text-white
                            @elseif($user->role === 'admin') bg-warning text-white
                            @else bg-primary text-white @endif">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-span-12 lg:col-span-4">
        <div class="intro-y box p-5">
            <h3 class="font-medium text-base mb-4">Account Information</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="form-label">Account Created</label>
                    <div class="form-control">{{ $user->created_at->format('M d, Y h:i A') }}</div>
                </div>
                
                <div>
                    <label class="form-label">Last Updated</label>
                    <div class="form-control">{{ $user->updated_at->format('M d, Y h:i A') }}</div>
                </div>
                
                @if($user->email_verified_at)
                <div>
                    <label class="form-label">Email Verified</label>
                    <div class="form-control text-success">✓ Verified</div>
                </div>
                @else
                <div>
                    <label class="form-label">Email Verified</label>
                    <div class="form-control text-warning">⚠ Not Verified</div>
                </div>
                @endif
            </div>
        </div>
        
        @if($user->applications()->count() > 0)
        <div class="intro-y box p-5 mt-5">
            <h3 class="font-medium text-base mb-4">Applications</h3>
            
            <div class="space-y-3">
                @foreach($user->applications()->latest()->take(3)->get() as $application)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div>
                        <div class="font-medium">{{ $application->application_number }}</div>
                        <div class="text-slate-500 text-sm">{{ $application->school }} - {{ $application->course }}</div>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs 
                        @if($application->status === 'pending') bg-warning text-white
                        @elseif($application->status === 'under_review') bg-primary text-white
                        @elseif($application->status === 'approved') bg-success text-white
                        @else bg-danger text-white @endif">
                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                    </span>
                </div>
                @endforeach
            </div>
            
            @if($user->applications()->count() > 3)
            <div class="mt-3 text-center">
                <a href="{{ route('applications.applicants') }}?search={{ $user->username }}" class="btn btn-outline-secondary btn-sm">
                    View All Applications
                </a>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
