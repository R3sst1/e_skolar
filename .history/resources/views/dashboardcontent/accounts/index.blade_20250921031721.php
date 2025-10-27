<div>
    
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
                <h1 class="text-2xl font-bold text-slate-800">Account Management</h1>
                <p class="text-slate-600 mt-1">Manage user accounts and permissions</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('residence-data.index') }}" class="btn btn-outline-secondary">
                    <i data-lucide="users" class="w-4 h-4 mr-2"></i> Residence Data
                </a>
                <a href="{{ route('residence-data.index') }}" class="btn btn-primary">
                    <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i> Create Account
                </a>
            </div>
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
    <div class="box p-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       class="form-control" 
                       placeholder="Search by name, username, or email...">
            </div>
            <div class="flex items-center gap-2">
                <label class="form-label text-sm">Per Page:</label>
                <select wire:model.live="perPage" class="form-select w-20">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="box p-6">
        <div class="overflow-x-auto">
            <table class="table table-report">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="font-medium">{{ $user->id }}</td>
                        <td>
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
                        <td>
                            <span class="font-medium">{{ $user->username }}</span>
                        </td>
                        <td>
                            <span class="text-slate-600">{{ $user->email ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $user->role === 'superadmin' ? 'bg-primary text-white' : 
                                   ($user->role === 'admin' ? 'bg-success text-white' : 
                                   ($user->role === 'applicant' ? 'bg-warning text-white' : 'bg-slate-500 text-white')) }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="text-slate-600">{{ $user->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="text-center">
                            <div class="flex justify-center items-center gap-2">
                                <!-- Edit Button -->
                                <button class="btn btn-sm btn-outline-primary" 
                                        onclick="editUser({{ $user->id }})">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>

                                <!-- Delete Button (only for Super Admin) -->
                                @if(Auth::user()->isSuperAdmin() && $user->id !== Auth::id())
                                <button class="btn btn-sm btn-danger" 
                                        wire:click="deleteUser({{ $user->id }})"
                                        wire:confirm="Are you sure you want to delete this user account? This action cannot be undone."
                                        @if($user->role === 'superadmin' && $users->where('role', 'superadmin')->count() <= 1) disabled title="Cannot delete the last Super Admin" @endif>
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
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
                        <td colspan="7" class="text-center py-10 text-slate-500">
                            <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                            <p>No users found.</p>
                            @if($search)
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