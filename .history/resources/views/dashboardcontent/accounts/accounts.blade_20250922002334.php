@extends('layouts.app')
@section('title', 'Account Management')
@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Account Management</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('accounts.create') }}" class="btn btn-primary shadow-md mr-2">
            <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i> Create Account
        </a>
        <a href="{{ route('residence-data.index') }}" class="btn btn-outline-secondary shadow-md">
            <i data-lucide="users" class="w-4 h-4 mr-2"></i> Residence Data
        </a>
    </div>
</div>

<!-- Statistics -->
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="users" class="report-box__icon text-primary"></i>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $users->total() }}</div>
                <div class="text-base text-slate-500 mt-1">Total Users</div>
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="crown" class="report-box__icon text-primary"></i>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $users->where('role', 'super_admin')->count() }}</div>
                <div class="text-base text-slate-500 mt-1">Super Admins</div>
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="user-check" class="report-box__icon text-success"></i>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $users->where('role', 'admin')->count() }}</div>
                <div class="text-base text-slate-500 mt-1">Admins</div>
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="user-plus" class="report-box__icon text-warning"></i>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $users->where('role', 'applicant')->count() }}</div>
                <div class="text-base text-slate-500 mt-1">Applicants</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="intro-y box mt-5">
    <div class="flex items-center p-5 border-b border-slate-200/60">
        <h2 class="font-medium text-base mr-auto">Filters</h2>
    </div>
    <div class="p-5">
        <form method="GET" action="{{ route('accounts.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Search User</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="form-control w-full" placeholder="Name, username, or email">
            </div>
            <div>
                <label class="form-label">Role</label>
                <select name="role" class="form-select w-full">
                    <option value="">All Roles</option>
                    <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="applicant" {{ request('role') == 'applicant' ? 'selected' : '' }}>Applicant</option>
                </select>
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-select w-full">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div>
                <label class="form-label">Per Page</label>
                <select name="per_page" class="form-select w-full">
                    <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 per page</option>
                    <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 per page</option>
                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 per page</option>
                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100 per page</option>
                </select>
            </div>
            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="btn btn-primary mr-2">
                    <i data-lucide="search" class="w-4 h-4 mr-2"></i> Filter
                </button>
                <a href="{{ route('accounts.index') }}" class="btn btn-outline-secondary">
                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Role Management Legend -->
@if(Auth::user()->isSuperAdmin())
<div class="intro-y box mt-5">
    <div class="flex items-center p-5 border-b border-slate-200/60">
        <h2 class="font-medium text-base mr-auto">Role Management</h2>
    </div>
    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div class="flex items-center">
                <div class="w-3 h-3 bg-green-500 rounded mr-2"></div>
                <span><strong>Admin:</strong> Promote to Admin role</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-purple-500 rounded mr-2"></div>
                <span><strong>Super:</strong> Promote to Super Admin</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-orange-500 rounded mr-2"></div>
                <span><strong>Demote:</strong> Demote to Applicant</span>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Users Table -->
<div class="intro-y box mt-5">
    <div class="flex items-center p-5 border-b border-slate-200/60">
        <h2 class="font-medium text-base mr-auto">User Accounts</h2>
    </div>
    <div class="p-5">
        <div class="overflow-x-auto">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">User</th>
                        <th class="whitespace-nowrap">Username/Email</th>
                        <th class="whitespace-nowrap">Role</th>
                        <th class="whitespace-nowrap">Status</th>
                        <th class="whitespace-nowrap">Created</th>
                        <th class="whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="intro-x">
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 image-fit zoom-in">
                                        <img alt="User" class="tooltip rounded-full" 
                                             src="{{ asset('public/Images/normalpicture.png') }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium whitespace-nowrap">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </div>
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                            ID: {{ $user->id }}
                                        </div>
                                        @if($user->phone_number)
                                        <div class="text-slate-500 text-xs whitespace-nowrap">
                                            {{ $user->phone_number }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-medium whitespace-nowrap">{{ $user->username }}</div>
                                @if($user->email)
                                <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ $user->email }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center justify-center">
                                    <div class="w-2 h-2 bg-{{ $user->role === 'super_admin' ? 'primary' : ($user->role === 'admin' ? 'success' : 'warning') }} rounded-full mr-2"></div>
                                    <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center justify-center">
                                    <div class="w-2 h-2 bg-success rounded-full mr-2"></div>
                                    <span class="font-medium text-success">Active</span>
                                </div>
                            </td>
                            <td>
                                <div class="text-slate-500 text-xs whitespace-nowrap">{{ $user->created_at->format('M d, Y') }}</div>
                                <div class="text-slate-500 text-xs whitespace-nowrap">{{ $user->created_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                <div class="flex items-center justify-center">
                                    <!-- Promotion/Demotion Buttons (only for Super Admin) -->
                                    @if(Auth::user()->isSuperAdmin() && $user->id !== Auth::id())
                                        @if($user->role === 'applicant')
                                            <div class="flex items-center justify-center">
                                                <form method="POST" action="{{ route('accounts.promote', $user->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success mr-1"
                                                            onclick="return confirm('Are you sure you want to promote this user to Admin?')">
                                                        <i data-lucide="arrow-up" class="w-4 h-4 mr-1"></i> Admin
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('accounts.promote-super', $user->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary mr-1"
                                                            onclick="return confirm('Are you sure you want to promote this user to Super Admin? This gives them full system access.')">
                                                        <i data-lucide="crown" class="w-4 h-4 mr-1"></i> Super
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($user->role === 'admin')
                                            <div class="flex items-center justify-center">
                                                <form method="POST" action="{{ route('accounts.demote', $user->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning mr-1"
                                                            onclick="return confirm('Are you sure you want to demote this user to Applicant?')">
                                                        <i data-lucide="arrow-down" class="w-4 h-4 mr-1"></i> Demote
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('accounts.promote-super', $user->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary mr-1"
                                                            onclick="return confirm('Are you sure you want to promote this user to Super Admin? This gives them full system access.')">
                                                        <i data-lucide="crown" class="w-4 h-4 mr-1"></i> Super
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endif

                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-outline-secondary mr-1" 
                                            onclick="editUser({{ $user->id }})">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>

                                    <!-- Delete Button (only for Super Admin) -->
                                    @if(Auth::user()->isSuperAdmin() && $user->id !== Auth::id())
                                    <form method="POST" action="{{ route('accounts.destroy', $user->id) }}" class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this user account? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger mr-1"
                                                @if($user->role === 'super_admin' && $users->where('role', 'super_admin')->count() <= 1) disabled title="Cannot delete the last Super Admin" @endif>
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <!-- View Profile Button -->
                                    <a href="{{ route('view.profile.other', $user->id) }}" 
                                       class="btn btn-sm btn-outline-secondary">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-500">
                                <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                                <p>No users found.</p>
                                @if(request('search'))
                                <p class="text-sm mt-2">Try adjusting your search criteria.</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>
</div>

    <!-- JavaScript for Edit Functionality -->
    <script>
        function editUser(userId) {
            // Implement edit functionality
            console.log('Edit user:', userId);
            // You can add edit modal or redirect to edit page
            alert('Edit functionality will be implemented in the next phase.');
        }
    </script>
</div>
@endsection 