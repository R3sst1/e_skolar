@extends('layouts.app')
@section('title', 'Account Management')
@section('content')
<div class="content">
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Account Management
        </h2>
        <div class="flex gap-3">
            <a href="{{ route('residence-data.index') }}" class="btn btn-outline-secondary">
                <i data-lucide="users" class="w-4 h-4 mr-2"></i> Residence Data
            </a>
            <a href="{{ route('accounts.create') }}" class="btn btn-primary">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i> Create Account
            </a>
        </div>
    </div>


    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="box p-4">
            <div class="flex items-center">
                <div class="text-slate-500">Total Users</div>
                <div class="ml-auto">
                    <div class="text-2xl font-bold text-slate-600">{{ $users->total() }}</div>
                </div>
            </div>
        </div>
        <div class="box p-4">
            <div class="flex items-center">
                <div class="text-slate-500">Super Admins</div>
                <div class="ml-auto">
                    <div class="text-2xl font-bold text-primary">{{ $users->where('role', 'superadmin')->count() }}</div>
                </div>
            </div>
        </div>
        <div class="box p-4">
            <div class="flex items-center">
                <div class="text-slate-500">Admins</div>
                <div class="ml-auto">
                    <div class="text-2xl font-bold text-success">{{ $users->where('role', 'admin')->count() }}</div>
                </div>
            </div>
        </div>
        <div class="box p-4">
            <div class="flex items-center">
                <div class="text-slate-500">Applicants</div>
                <div class="ml-auto">
                    <div class="text-2xl font-bold text-warning">{{ $users->where('role', 'applicant')->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="px-5 pb-5">
            <form method="GET" action="{{ route('accounts.index') }}">
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               class="form-control" 
                               placeholder="Search by name, username, or email...">
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="form-label text-sm">Per Page:</label>
                        <select name="per_page" class="form-select w-20">
                            <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="search" class="w-4 h-4 mr-2"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="box p-6">
        <div class="overflow-x-auto">
            <table class="table table-report border border-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="border-b border-slate-200 font-bold text-slate-700">ID</th>
                        <th class="border-b border-slate-200 font-bold text-slate-700">Full Name</th>
                        <th class="border-b border-slate-200 font-bold text-slate-700">Username/Email</th>
                        <th class="border-b border-slate-200 font-bold text-slate-700">Role</th>
                        <th class="border-b border-slate-200 font-bold text-slate-700">Created At</th>
                        <th class="border-b border-slate-200 font-bold text-slate-700 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-b border-slate-100 {{ $loop->even ? 'bg-slate-50' : 'bg-white' }}">
                        <td class="border-b border-slate-100 font-medium py-3">{{ $user->id }}</td>
                        <td class="border-b border-slate-100 py-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center mr-3">
                                    <i data-lucide="user" class="w-4 h-4 text-white"></i>
                                </div>
                                <div>
                                    <div class="font-medium">{{ $user->first_name }} {{ $user->last_name }}</div>
                                    @if($user->phone_number)
                                    <div class="text-sm text-slate-500">{{ $user->phone_number }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="border-b border-slate-100 py-3">
                            <div>
                                <div class="font-medium">{{ $user->username }}</div>
                                @if($user->email)
                                <div class="text-sm text-slate-500">{{ $user->email }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="border-b border-slate-100 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $user->role === 'superadmin' ? 'bg-primary text-white' : 
                                   ($user->role === 'admin' ? 'bg-success text-white' : 
                                   ($user->role === 'applicant' ? 'bg-warning text-white' : 'bg-slate-500 text-white')) }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="border-b border-slate-100 py-3">
                            <span class="text-slate-600">{{ $user->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="border-b border-slate-100 py-3 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <!-- Edit Button -->
                                <button class="btn btn-sm bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded" 
                                        onclick="editUser({{ $user->id }})">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>

                                <!-- Delete Button (only for Super Admin) -->
                                @if(Auth::user()->isSuperAdmin() && $user->id !== Auth::id())
                                <form method="POST" action="{{ route('accounts.destroy', $user->id) }}" class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this user account? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
                                            @if($user->role === 'superadmin' && $users->where('role', 'superadmin')->count() <= 1) disabled title="Cannot delete the last Super Admin" @endif>
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif

                                <!-- View Profile Button -->
                                <a href="{{ route('view.profile.other', $user->id) }}" 
                                   class="btn btn-sm bg-slate-500 hover:bg-slate-600 text-white px-3 py-1 rounded">
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
                            @if(isset($search) && !empty($search))
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