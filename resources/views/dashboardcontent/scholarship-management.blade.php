@extends('layouts.app')
@section('title', 'Scholarship Management')
@section('content')
    
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal" data-tw-target="#add-scholarship-modal">Add New Scholarship</button>
            <a href="{{ route('applications.applicants') }}" class="btn btn-secondary shadow-md mr-2">
                <i data-lucide="users" class="w-4 h-4 mr-2"></i>View Applicants
            </a>
            <!-- <div class="dropdown">
                <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="plus"></i> </span>
                </button>
                <div class="dropdown-menu w-40">
                    <ul class="dropdown-content">
                        <li>
                            <a href="" class="dropdown-item"> <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Print </a>
                        </li>
                        <li>
                            <a href="" class="dropdown-item"> <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Export to Excel </a>
                        </li>
                        <li>
                            <a href="" class="dropdown-item"> <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Export to PDF </a>
                        </li>
                    </ul>
                </div>
            </div> -->
            <div class="hidden md:block mx-auto text-slate-500">Showing 1 to 10 of 150 entries</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Search...">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i> 
                </div>
            </div>
            
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <div class="max-w-7xl mx-auto px-4">
                <table class="table table-report -mt-2 w-full text-center">
                <thead>
                <tr>
                    <th>SCHOLARSHIP NAME</th>
                    <th class="whitespace-nowrap">DETAILS</th>
                    <th >STATUS</th>
                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($scholarships as $scholarship)
                    <tr class="intro-x">
                        <!-- Name + Description -->
                        <td class="align-top">
                            <span class="font-medium text-base block">{{ $scholarship->name }}</span>
                            @if($scholarship->description)
                            <div class="text-xs text-slate-600 leading-relaxed mt-1">
                                {{ Str::limit($scholarship->description, 100) }}
                            </div>
                            @endif
                        </td>

                        <!-- Details -->
                        <td class="align-top">
                            <div class="flex flex-col gap-1 text-sm">
                                <div class="flex items-center gap-2">
                                    <strong>Type:</strong> 
                                    <span class="px-2 py-1 rounded-full text-xs {{ $scholarship->type === 'budgeted' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($scholarship->type) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <strong>Deadline:</strong> 
                                    <span class="text-slate-600">{{ $scholarship->deadline ? \Carbon\Carbon::parse($scholarship->deadline)->format('M d, Y') : '-' }}</span>
                                </div>
                                @if($scholarship->type === 'budgeted')
                                <div class="text-xs mt-2 text-slate-600 space-y-1">
                                    <div><strong>Budget:</strong> ₱{{ number_format($scholarship->allocated_budget ?? 0, 2) }}</div>
                                    <div><strong>Per Scholar:</strong> ₱{{ number_format($scholarship->per_scholar_amount ?? 0, 2) }}</div>
                                </div>
                                @endif
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="w-40">
                            <div class="flex items-center justify-center {{ $scholarship->status === 'active' ? 'text-success' : 'text-danger' }}">
                                <i data-lucide="check-square" class="w-4 h-4 mr-2"></i> {{ ucfirst($scholarship->status) }}
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal" data-tw-target="#edit-scholarship-modal-{{ $scholarship->id }}">
                                    <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Edit
                                </a>
                                <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-scholarship-modal-{{ $scholarship->id }}">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete
                                </a>
                            </div>
                            <!-- Edit Modal -->
                            <div id="edit-scholarship-modal-{{ $scholarship->id }}" class="modal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form class="modal-content" method="POST" action="{{ route('scholarship.management.update', $scholarship->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h2 class="font-medium text-base mr-auto">Edit Scholarship</h2>
                                            <button type="button" class="btn-close" data-tw-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                            <div class="col-span-12">
                                                <label class="form-label">Scholarship Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ $scholarship->name }}" required>
                                            </div>
                                            <div class="col-span-12">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control">{{ $scholarship->description }}</textarea>
                                            </div>
                                            <div class="col-span-12">
                                                <label class="form-label">Deadline</label>
                                                <input type="date" name="deadline" class="form-control" value="{{ $scholarship->deadline }}">
                                            </div>
                                            <div class="col-span-12">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="active" @if($scholarship->status=='active') selected @endif>Active</option>
                                                    <option value="inactive" @if($scholarship->status=='inactive') selected @endif>Inactive</option>
                                                </select>
                                            </div>
                                            <div class="col-span-12">
                                                <label class="form-label">Program Type</label>
                                                <select name="type" class="form-select" required>
                                                    <option value="unbudgeted" @if($scholarship->type=='unbudgeted') selected @endif>Unbudgeted</option>
                                                    <option value="budgeted" @if($scholarship->type=='budgeted') selected @endif>Budgeted</option>
                                                </select>
                                            </div>
                                            <div class="col-span-12" id="budget-fields-edit-{{ $scholarship->id }}" style="display: {{ $scholarship->type == 'budgeted' ? 'block' : 'none' }};">
                                                <div class="grid grid-cols-12 gap-4 gap-y-3">
                                                    <div class="col-span-6">
                                                        <label class="form-label">Allocated Budget (₱)</label>
                                                        <input type="number" name="allocated_budget" class="form-control" step="0.01" min="0" value="{{ $scholarship->allocated_budget }}" placeholder="0.00">
                                                    </div>
                                                    <div class="col-span-6">
                                                        <label class="form-label">Per Scholar Amount (₱)</label>
                                                        <input type="number" name="per_scholar_amount" class="form-control" step="0.01" min="0" value="{{ $scholarship->per_scholar_amount }}" placeholder="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-span-12">
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="auto_close" value="1" class="form-check-input" id="auto_close_edit_{{ $scholarship->id }}" {{ $scholarship->auto_close ? 'checked' : '' }}>
                                                    <label class="form-check-label ml-2" for="auto_close_edit_{{ $scholarship->id }}">
                                                        Auto close when budget limit is reached
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-span-12">
                                                <label class="form-label">Image (optional)</label>
                                                <input type="file" name="image" class="form-control">
                                                @if($scholarship->image)
                                                    <img src="{{ asset('storage/' . $scholarship->image) }}" alt="Current Image" class="mt-2 w-16 h-16 rounded-full">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer text-right">
                                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                                            <button type="submit" class="btn btn-primary w-24">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Delete Modal -->
                            <div id="delete-scholarship-modal-{{ $scholarship->id }}" class="modal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form class="modal-content" method="POST" action="{{ route('scholarship.management.destroy', $scholarship->id) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h2 class="font-medium text-base mr-auto">Delete Scholarship</h2>
                                            <button type="button" class="btn-close" data-tw-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-5 text-center">
                                            <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                            <div class="text-3xl mt-5">Are you sure?</div>
                                            <div class="text-slate-500 mt-2">
                                                Do you really want to delete this scholarship program?<br>This process cannot be undone.
                                            </div>
                                        </div>
                                        <div class="modal-footer text-right">
                                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                                            <button type="submit" class="btn btn-danger w-24">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-10 text-slate-500">No scholarships found.</td>
                </tr>
                @endforelse
                </tbody>
                </table>
            </div>
        </div>
        <!-- END: Data List -->
        <!-- Add Scholarship Modal -->
        <div id="add-scholarship-modal" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" method="POST" action="{{ route('scholarship.management.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">Add New Scholarship</h2>
                        <button type="button" class="btn-close" data-tw-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12">
                            <label class="form-label">Scholarship Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-span-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                        <div class="col-span-12">
                            <label class="form-label">Deadline</label>
                            <input type="date" name="deadline" class="form-control">
                        </div>
                        <div class="col-span-12">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-span-12">
                            <label class="form-label">Program Type</label>
                            <select name="type" class="form-select" required>
                                <option value="unbudgeted">Unbudgeted</option>
                                <option value="budgeted">Budgeted</option>
                            </select>
                        </div>
                        <div class="col-span-12" id="budget-fields" style="display: none;">
                            <div class="grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-6">
                                    <label class="form-label">Allocated Budget (₱)</label>
                                    <input type="number" name="allocated_budget" class="form-control" step="0.01" min="0" placeholder="0.00">
                                </div>
                                <div class="col-span-6">
                                    <label class="form-label">Per Scholar Amount (₱)</label>
                                    <input type="number" name="per_scholar_amount" class="form-control" step="0.01" min="0" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12">
                            <div class="flex items-center">
                                <input type="checkbox" name="auto_close" value="1" class="form-check-input" id="auto_close">
                                <label class="form-check-label ml-2" for="auto_close">
                                    Auto close when budget limit is reached
                                </label>
                            </div>
                        </div>
                        <div class="col-span-12">
                            <label class="form-label">Image (optional)</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer text-right">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                        <button type="submit" class="btn btn-primary w-24">Add</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- END: Add Scholarship Modal -->
        <!-- BEGIN: Pagination -->
        <!-- <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            <nav class="w-full sm:w-auto sm:mr-auto">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="#"> <i class="w-4 h-4" data-lucide="chevrons-left"></i> </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#"> <i class="w-4 h-4" data-lucide="chevron-left"></i> </a>
                    </li>
                    <li class="page-item"> <a class="page-link" href="#">...</a> </li>
                    <li class="page-item"> <a class="page-link" href="#">1</a> </li>
                    <li class="page-item active"> <a class="page-link" href="#">2</a> </li>
                    <li class="page-item"> <a class="page-link" href="#">3</a> </li>
                    <li class="page-item"> <a class="page-link" href="#">...</a> </li>
                    <li class="page-item">
                        <a class="page-link" href="#"> <i class="w-4 h-4" data-lucide="chevron-right"></i> </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#"> <i class="w-4 h-4" data-lucide="chevrons-right"></i> </a>
                    </li>
                </ul>
            </nav>
            <select class="w-20 form-select box mt-3 sm:mt-0">
                <option>10</option>
                <option>25</option>
                <option>35</option>
                <option>50</option>
            </select>
        </div> -->
        <!-- END: Pagination -->
    </div>
    <!-- BEGIN: Delete Confirmation Modal -->
    <div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i> 
                        <div class="text-3xl mt-5">Are you sure?</div>
                        <div class="text-slate-500 mt-2">
                            Do you really want to delete these records? 
                            <br>
                            This process cannot be undone.
                        </div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                        <button type="button" class="btn btn-danger w-24">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Delete Confirmation Modal -->

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle budget fields visibility for create modal
            const typeSelect = document.querySelector('select[name="type"]');
            const budgetFields = document.getElementById('budget-fields');
            
            if (typeSelect && budgetFields) {
                typeSelect.addEventListener('change', function() {
                    if (this.value === 'budgeted') {
                        budgetFields.style.display = 'block';
                    } else {
                        budgetFields.style.display = 'none';
                    }
                });
            }

            // Handle budget fields visibility for edit modals
            document.querySelectorAll('select[name="type"]').forEach(function(select) {
                const modalId = select.closest('.modal').id;
                const budgetFieldsId = 'budget-fields-edit-' + modalId.replace('edit-scholarship-modal-', '');
                const budgetFields = document.getElementById(budgetFieldsId);
                
                if (budgetFields) {
                    select.addEventListener('change', function() {
                        if (this.value === 'budgeted') {
                            budgetFields.style.display = 'block';
                        } else {
                            budgetFields.style.display = 'none';
                        }
                    });
                }
            });
        });
    </script>
    @endpush
@endsection 