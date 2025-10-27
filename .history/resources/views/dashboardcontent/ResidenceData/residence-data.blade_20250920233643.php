@extends('layouts.app')
@section('title', 'Residence Data Management')
@section('content')

<div class="container mx-auto px-4">
    
    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif

    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Residence Data Management</h1>
                <p class="text-slate-600 mt-1">Manage applicant data and create user accounts</p>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
            <button class="btn btn-primary" data-tw-toggle="modal" data-tw-target="#add-applicant-modal">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i> Add Applicant
            </button>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="box p-4">
            <div class="flex items-center">
                <div class="text-slate-500">Total Applicants</div>
                <div class="ml-auto">
                    <div class="text-2xl font-bold text-slate-600">{{ $residenceData->total() }}</div>
                </div>
            </div>
        </div>
        <div class="box p-4">
            <div class="flex items-center">
                <div class="text-slate-500">Accounts Created</div>
                <div class="ml-auto">
                    <div class="text-2xl font-bold text-success">{{ $residenceData->where('account_created', true)->count() }}</div>
                </div>
            </div>
        </div>
        <div class="box p-4">
            <div class="flex items-center">
                <div class="text-slate-500">Pending Accounts</div>
                <div class="ml-auto">
                    <div class="text-2xl font-bold text-warning">{{ $residenceData->where('account_created', false)->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="box p-6">
        <div class="overflow-x-auto">
            <table class="table table-report">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th class="text-center">Contact</th>
                        <th class="text-center">Barangay</th>
                        <th class="text-center">Age</th>
                        <th class="text-center">Account Status</th>
                        <th class="text-center">Created</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($residenceData as $applicant)
                    <tr>
                        <td>
                            <div class="font-medium">{{ $applicant->full_name }}</div>
                            @if($applicant->email)
                            <div class="text-sm text-slate-500">{{ $applicant->email }}</div>
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $applicant->contact_number ?? 'N/A' }}
                        </td>
                        <td class="text-center">
                            {{ $applicant->barangay ?? 'N/A' }}
                        </td>
                        <td class="text-center">
                            {{ $applicant->age ?? 'N/A' }}
                        </td>
                        <td class="text-center">
                            <span class="px-2 py-1 rounded-full text-xs {{ $applicant->account_status_badge }}">
                                {{ $applicant->account_status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="text-slate-600">{{ $applicant->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="text-center">
                            <div class="flex justify-center items-center gap-2">
                                <!-- View Profile -->
                                <a href="{{ route('profiles.show', $applicant->id) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i data-lucide="user" class="w-4 h-4 mr-1"></i> View Profile
                                </a>

                                @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                                    <!-- Create Account Button (only if not created) -->
                                    @if(!$applicant->account_created)
                                    <a href="{{ route('residence-data.create-account', $applicant->id) }}" 
                                       class="btn btn-sm btn-success">
                                        <i data-lucide="user-plus" class="w-4 h-4 mr-1"></i> Create Account
                                    </a>
                                    @else
                                    <span class="text-sm text-slate-500">Account Created</span>
                                    @endif

                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-outline-secondary" 
                                            onclick="editApplicant({{ $applicant->id }})">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('residence-data.destroy', $applicant->id) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Are you sure you want to delete this applicant?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-slate-500">
                            <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                            <p>No applicants found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $residenceData->links() }}
        </div>
    </div>
</div>

<!-- Add Applicant Modal -->
@if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
<div id="add-applicant-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Add New Applicant</h2>
                <button data-tw-dismiss="modal" class="btn btn-outline-secondary hidden sm:flex">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <form action="{{ route('residence-data.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div>
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div>
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" class="form-control">
                        </div>
                        <div>
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" class="form-control">
                        </div>
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div>
                            <label class="form-label">Barangay</label>
                            <input type="text" name="barangay" class="form-control">
                        </div>
                        <div>
                            <label class="form-label">Age</label>
                            <input type="number" name="age" class="form-control" min="1" max="120">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                    <button type="submit" class="btn btn-primary w-20">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
function editApplicant(id) {
    // Implement edit functionality
    console.log('Edit applicant:', id);
    // You can add edit modal or redirect to edit page
}
</script>
@endpush
