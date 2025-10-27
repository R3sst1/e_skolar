@extends('layouts.app')
@section('title', 'Applicants')
@section('content')
<h2 class="intro-y text-lg font-medium mt-10">
    @if($scholarshipProgram)
        Applicants for {{ $scholarshipProgram->name }}
    @else
        Applicants for Scholarship
    @endif
</h2>
@if($scholarshipProgram)
<div class="intro-y box p-5 mt-5">
    <div class="flex items-center">
        <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mr-4">
            <i data-lucide="award" class="w-6 h-6 text-primary"></i>
        </div>
        <div>
            <h3 class="font-medium text-lg">{{ $scholarshipProgram->name }}</h3>
            <p class="text-slate-500 text-sm">{{ $scholarshipProgram->description }}</p>
            <div class="flex items-center mt-2">
                <span class="px-2 py-1 rounded-full text-xs {{ $scholarshipProgram->status === 'active' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                    {{ ucfirst($scholarshipProgram->status) }}
                </span>
                @if($scholarshipProgram->type === 'budgeted')
                <span class="ml-2 px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                    Budget: ₱{{ number_format($scholarshipProgram->allocated_budget ?? 0, 2) }}
                </span>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-slate-500">
                <form method="GET" action="{{ route('applications.applicants') }}">
                    @if(request('scholarship_id'))
                        <input type="hidden" name="scholarship_id" value="{{ request('scholarship_id') }}">
                    @endif
                    <input type="text" name="search" class="form-control w-56 box pr-10" placeholder="Search..." value="{{ request('search') }}">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </form>
            </div>
        </div>
    </div>
    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">PROFILE</th>
                    <th class="whitespace-nowrap">NAME</th>
                    <th class="whitespace-nowrap">SCHOLARSHIP APPLIED</th>
                    <th class="text-center whitespace-nowrap">AGE</th>
                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                    <th class="text-center whitespace-nowrap">DETAILS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $application)
                <tr class="intro-x">
                    <td class="w-40">
                        <div class="flex">
                            <div class="w-10 h-10 image-fit zoom-in">
                                <img alt="Profile" class="tooltip rounded-full" src="{{ asset('Images/normalpicture.png') }}">
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="font-medium whitespace-nowrap">
                            {{ $application->user->first_name }} {{ $application->user->last_name }}
                        </span>
                    </td>
                    <td>
                        <span class="font-medium whitespace-nowrap">{{ $application->scholarship->name ?? 'No Scholarship' }}</span>
                        <div class="text-xs text-slate-500">ID: {{ $application->id }} | Status: {{ $application->status }}</div>
                    </td>
                    <td class="text-center">{{ $application->user->age ?? '-' }}</td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center gap-2">
                            <button class="flex items-center btn btn-success btn-sm" onclick="handleApplicantAction({{ $application->id }}, 'approve')">
                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Approve
                            </button>
                            <button class="flex items-center btn btn-danger btn-sm" onclick="handleApplicantAction({{ $application->id }}, 'reject')">
                                <i data-lucide="x" class="w-4 h-4 mr-1"></i> Reject
                            </button>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="flex justify-center items-center gap-2">
                                <!-- <button class="flex items-center btn btn-primary btn-sm" onclick="showApplicationDetails({{ $application->id }})">
                                    <i data-lucide="info" class="w-4 h-4 mr-1"></i> Application Details
                                </button> -->
                                <a href="{{ route('requirements.index', $application->id) }}" class="btn btn-outline-secondary btn-sm" target="_self">
                                    <i data-lucide="file-text" class="w-4 h-4 mr-1"></i> Review Documents
                                </a>
                            </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-10 text-slate-500">No applicants found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $applications->links() }}
        </div>
    </div>
</div>

<!-- Application Details Modal -->
<div id="application-details-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Application Details</h2>
                <button type="button" class="btn-close" data-tw-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="application-details-content">
                <!-- Details will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Fix for tailwind.Modal error
if (typeof tailwind !== 'undefined' && tailwind.Modal) {
    // Tailwind Modal is available
} else {
    console.warn('Tailwind Modal not available, using fallback');
    // Create a simple fallback
    window.tailwind = window.tailwind || {};
    window.tailwind.Modal = {
        getOrCreateInstance: function(element) {
            return {
                show: function() { console.log('Modal show called'); },
                hide: function() { console.log('Modal hide called'); }
            };
        }
    };
}

function handleApplicantAction(applicationId, action) {
    if (!confirm(`Are you sure you want to ${action} this applicant?`)) {
        return;
    }
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                     document.querySelector('input[name="_token"]')?.value;
    
    if (!csrfToken) {
        console.error('CSRF token not found');
        alert('Security token not found. Please refresh the page and try again.');
        return;
    }
    
    console.log(`Attempting to ${action} application ${applicationId}`);
    
    fetch(`/applications/${applicationId}/${action}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.error || `HTTP ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            alert(data.message || 'Action successful');
            window.location.reload();
        } else {
            alert(data.error || 'Failed to process action');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to process action: ' + error.message);
    });
}

function showApplicationDetails(applicationId) {
    fetch(`/applications/${applicationId}/status`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('application-details-content').innerHTML = html;
        // Use fallback if tailwind.Modal is not available
        if (typeof tailwind !== 'undefined' && tailwind.Modal) {
            const modal = tailwind.Modal.getOrCreateInstance(document.getElementById('application-details-modal'));
            modal.show();
        } else {
            console.log('Modal functionality not available');
        }
    })
    .catch(error => {
        alert('Failed to load application details.');
    });
}
</script>
@endpush
@endsection 