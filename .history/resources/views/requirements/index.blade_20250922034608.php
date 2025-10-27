@extends('layouts.app')
@section('title', 'Document Review - ' . $application->user->first_name . ' ' . $application->user->last_name)
@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Document Review</h2>
    <div class="ml-auto flex">
        <a href="{{ route('applications.applicants') }}" class="btn btn-outline-secondary mr-2">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Back to Applicants
        </a>
        <button id="bulk-approve" class="btn btn-primary mr-2" style="display: none;">
            <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
            Approve Selected
        </button>
        <button id="bulk-reject" class="btn btn-danger" style="display: none;">
            <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i>
            Reject Selected
        </button>
    </div>
</div>

<!-- Application Summary -->
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Application Summary</h2>
                <span class="px-2 py-1 rounded-full text-xs 
                    @if($application->status === 'pending') bg-warning text-white
                    @elseif($application->status === 'under_review') bg-primary text-white
                    @elseif($application->status === 'approved') bg-success text-white
                    @else bg-danger text-white @endif">
                    {{ ucfirst($application->status) }}
                </span>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-6">
                        <div class="text-slate-500 text-xs">Applicant</div>
                        <div class="font-medium">{{ $application->user->first_name }} {{ $application->user->last_name }}</div>
                    </div>
                    <div class="col-span-6">
                        <div class="text-slate-500 text-xs">Application #</div>
                        <div class="font-medium">{{ $application->application_number }}</div>
                    </div>
                    <div class="col-span-6">
                        <div class="text-slate-500 text-xs">School</div>
                        <div class="font-medium">{{ $application->school }}</div>
                    </div>
                    <div class="col-span-6">
                        <div class="text-slate-500 text-xs">Course</div>
                        <div class="font-medium">{{ $application->course }}</div>
                    </div>
                    <div class="col-span-6">
                        <div class="text-slate-500 text-xs">GWA</div>
                        <div class="font-medium">{{ $application->gwa }}</div>
                    </div>
                    <div class="col-span-6">
                        <div class="text-slate-500 text-xs">Family Income</div>
                        <div class="font-medium">₱{{ number_format($application->family_income, 2) }}</div>
                    </div>
                    @if($application->grade_photo)
                    <div class="col-span-12">
                        <div class="text-slate-500 text-xs mb-2">Grade Photo</div>
                        <div class="flex items-center gap-3">
                            <div class="w-16 h-16 bg-slate-100 rounded-lg flex items-center justify-center overflow-hidden">
                                <img src="{{ asset('storage/' . $application->grade_photo) }}" 
                                     alt="Grade Photo" 
                                     class="w-full h-full object-cover cursor-pointer"
                                     onclick="previewGradePhoto()">
                            </div>
                            <div class="flex gap-2">
                                <button class="btn btn-sm btn-outline-primary" onclick="previewGradePhoto()">
                                    <i data-lucide="eye" class="w-3 h-3 mr-1"></i>
                                    Preview
                                </button>
                                <a href="{{ asset('storage/' . $application->grade_photo) }}" 
                                   download 
                                   class="btn btn-sm btn-outline-secondary">
                                    <i data-lucide="download" class="w-3 h-3 mr-1"></i>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Document Review Grid -->
<div class="grid grid-cols-12 gap-6 mt-5">
    @foreach($application->requirements as $requirement)
    <div class="intro-y col-span-12 lg:col-span-6 xl:col-span-4">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <div class="flex items-center">
                    <i data-lucide="{{ $requirement->getFileIconAttribute() }}" class="w-5 h-5 mr-2"></i>
                    <h3 class="font-medium text-base">{{ $requirement->name }}</h3>
                </div>
                <div class="ml-auto">
                    <input type="checkbox" class="form-check-input requirement-checkbox" value="{{ $requirement->id }}">
                </div>
            </div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-slate-500 text-xs">Status</div>
                    <span class="px-2 py-1 rounded-full text-xs 
                        @if($requirement->status === 'pending') bg-warning text-white
                        @elseif($requirement->status === 'approved') bg-success text-white
                        @else bg-danger text-white @endif">
                        {{ ucfirst($requirement->status) }}
                    </span>
                </div>
                
                <div class="flex items-center justify-between mb-3">
                    <div class="text-slate-500 text-xs">File Size</div>
                    <div class="font-medium text-xs">{{ $requirement->getFileSize() }}</div>
                </div>
                
                <div class="flex items-center justify-between mb-4">
                    <div class="text-slate-500 text-xs">File Type</div>
                    <div class="font-medium text-xs uppercase">{{ $requirement->file_type }}</div>
                </div>

                @if($requirement->remarks)
                <div class="mb-4 p-3 bg-slate-50 rounded-md">
                    <div class="text-slate-500 text-xs mb-1">Remarks</div>
                    <div class="text-sm">{{ $requirement->remarks }}</div>
                </div>
                @endif

                <div class="flex flex-wrap gap-2">
                    <button class="btn btn-sm btn-outline-primary preview-btn" 
                            data-id="{{ $requirement->id }}"
                            data-name="{{ $requirement->name }}">
                        <i data-lucide="eye" class="w-3 h-3 mr-1"></i>
                        Preview
                    </button>
                    <a href="{{ route('requirements.download', $requirement->id) }}" 
                       class="btn btn-sm btn-outline-secondary">
                        <i data-lucide="download" class="w-3 h-3 mr-1"></i>
                        Download
                    </a>
                    @if($requirement->status === 'pending')
                    <button class="btn btn-sm btn-success approve-btn" 
                            data-id="{{ $requirement->id }}"
                            data-name="{{ $requirement->name }}">
                        <i data-lucide="check" class="w-3 h-3 mr-1"></i>
                        Approve
                    </button>
                    <button class="btn btn-sm btn-danger reject-btn" 
                            data-id="{{ $requirement->id }}"
                            data-name="{{ $requirement->name }}">
                        <i data-lucide="x" class="w-3 h-3 mr-1"></i>
                        Reject
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Document Preview Modal -->
<div id="document-preview-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto" id="preview-title">Document Preview</h2>
                <button data-tw-dismiss="modal" class="btn-close" aria-label="Close">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="preview-content" class="text-center">
                    <div class="loading-spinner">
                        <i data-lucide="loader" class="w-8 h-8 animate-spin mx-auto"></i>
                        <div class="mt-2">Loading document...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve/Reject Modal -->
<div id="action-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto" id="action-title">Document Action</h2>
                <button data-tw-dismiss="modal" class="btn-close" aria-label="Close">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="action-form">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Document Name</label>
                        <input type="text" id="action-document-name" class="form-control" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Remarks (Optional)</label>
                        <textarea name="remarks" class="form-control" rows="3" 
                                  placeholder="Add any remarks about this document..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary mr-1">Cancel</button>
                <button type="button" id="confirm-action" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentRequirementId = null;
let currentAction = null;

// Select all functionality
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('requirement-checkbox')) {
        updateBulkActions();
    }
});

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.requirement-checkbox:checked');
    const bulkButtons = document.querySelectorAll('#bulk-approve, #bulk-reject');
    
    if (checkboxes.length > 0) {
        bulkButtons.forEach(btn => btn.style.display = 'inline-flex');
    } else {
        bulkButtons.forEach(btn => btn.style.display = 'none');
    }
}

// Document preview
document.querySelectorAll('.preview-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        
        document.getElementById('preview-title').textContent = name;
        document.getElementById('preview-content').innerHTML = `
            <div class="loading-spinner">
                <i data-lucide="loader" class="w-8 h-8 animate-spin mx-auto"></i>
                <div class="mt-2">Loading document...</div>
            </div>
        `;
        
        // Handle tailwind.Modal safely
        if (typeof tailwind !== 'undefined' && tailwind.Modal) {
            const modal = new tailwind.Modal(document.getElementById('document-preview-modal'));
            modal.show();
        } else {
            // Fallback: show modal directly
            document.getElementById('document-preview-modal').style.display = 'block';
        }
        
        // Load preview
        fetch(`/requirements/${id}/preview`)
            .then(response => {
                if (response.ok) {
                    return response.blob();
                }
                throw new Error('Failed to load preview');
            })
            .then(blob => {
                const url = URL.createObjectURL(blob);
                // Get file type from the response headers or determine from URL
                const contentType = blob.type;
                const fileType = contentType.split('/')[1] || 'unknown';
                
                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) {
                    document.getElementById('preview-content').innerHTML = `
                        <img src="${url}" class="max-w-full max-h-96 mx-auto" alt="Document Preview">
                    `;
                } else if (fileType === 'pdf') {
                    document.getElementById('preview-content').innerHTML = `
                        <iframe src="${url}" class="w-full h-96" frameborder="0"></iframe>
                    `;
                } else {
                    document.getElementById('preview-content').innerHTML = `
                        <div class="text-center">
                            <i data-lucide="file-text" class="w-16 h-16 mx-auto text-slate-400"></i>
                            <div class="mt-4 text-slate-500">Preview not available for this file type</div>
                            <a href="${url}" download class="btn btn-primary mt-4">Download to View</a>
                        </div>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('preview-content').innerHTML = `
                    <div class="text-center text-danger">
                        <i data-lucide="alert-circle" class="w-16 h-16 mx-auto"></i>
                        <div class="mt-4">Failed to load document preview</div>
                    </div>
                `;
            });
    });
});

// Approve document
document.querySelectorAll('.approve-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        
        currentRequirementId = id;
        currentAction = 'approve';
        
        document.getElementById('action-title').textContent = 'Approve Document';
        document.getElementById('action-document-name').value = name;
        document.getElementById('confirm-action').className = 'btn btn-success';
        document.getElementById('confirm-action').innerHTML = '<i data-lucide="check" class="w-4 h-4 mr-2"></i>Approve';
        
        // Handle tailwind.Modal safely
        if (typeof tailwind !== 'undefined' && tailwind.Modal) {
            const modal = new tailwind.Modal(document.getElementById('action-modal'));
            modal.show();
        } else {
            // Fallback: show modal directly
            document.getElementById('action-modal').style.display = 'block';
        }
    });
});

// Reject document
document.querySelectorAll('.reject-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        
        currentRequirementId = id;
        currentAction = 'reject';
        
        document.getElementById('action-title').textContent = 'Reject Document';
        document.getElementById('action-document-name').value = name;
        document.getElementById('confirm-action').className = 'btn btn-danger';
        document.getElementById('confirm-action').innerHTML = '<i data-lucide="x" class="w-4 h-4 mr-2"></i>Reject';
        
        // Handle tailwind.Modal safely
        if (typeof tailwind !== 'undefined' && tailwind.Modal) {
            const modal = new tailwind.Modal(document.getElementById('action-modal'));
            modal.show();
        } else {
            // Fallback: show modal directly
            document.getElementById('action-modal').style.display = 'block';
        }
    });
});

// Confirm action
document.getElementById('confirm-action').addEventListener('click', function() {
    if (!currentRequirementId || !currentAction) return;
    
    const formData = new FormData(document.getElementById('action-form'));
    
    fetch(`/requirements/${currentRequirementId}/${currentAction}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.error || 'Failed to process the action. Please try again.');
        }
    })
    .catch(error => {
        alert('An error occurred. Please try again.');
    });
});
</script>
@endpush
@endsection