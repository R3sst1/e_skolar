@extends('layouts.app')
@section('title', 'Document Details - ' . $requirement->name)
@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Document Details</h2>
    <div class="ml-auto flex">
        <a href="{{ route('requirements.index', $requirement->application_id) }}" class="btn btn-outline-secondary mr-2">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Back to Documents
        </a>
        @if($requirement->status === 'pending')
        <button class="btn btn-success mr-2 approve-btn" 
                data-id="{{ $requirement->id }}"
                data-name="{{ $requirement->name }}">
            <i data-lucide="check" class="w-4 h-4 mr-2"></i>
            Approve
        </button>
        <button class="btn btn-danger reject-btn" 
                data-id="{{ $requirement->id }}"
                data-name="{{ $requirement->name }}">
            <i data-lucide="x" class="w-4 h-4 mr-2"></i>
            Reject
        </button>
        @endif
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- Document Information -->
    <div class="intro-y col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Document Information</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-slate-500 text-xs">Document Name</div>
                        <div class="font-medium">{{ $requirement->name }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">File Type</div>
                        <div class="font-medium uppercase">{{ $requirement->file_type }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">File Size</div>
                        <div class="font-medium">{{ $requirement->getFileSize() }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Status</div>
                        <div class="font-medium">
                            <span class="px-2 py-1 rounded-full text-xs 
                                @if($requirement->status === 'pending') bg-warning text-white
                                @elseif($requirement->status === 'approved') bg-success text-white
                                @else bg-danger text-white @endif">
                                {{ ucfirst($requirement->status) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Uploaded</div>
                        <div class="font-medium">{{ $requirement->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Last Updated</div>
                        <div class="font-medium">{{ $requirement->updated_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>

                @if($requirement->remarks)
                <div class="mt-4 p-3 bg-slate-50 rounded-md">
                    <div class="text-slate-500 text-xs mb-1">Remarks</div>
                    <div class="text-sm">{{ $requirement->remarks }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Application Information -->
    <div class="intro-y col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Application Information</h2>
            </div>
            <div class="p-5">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 image-fit zoom-in">
                        <img alt="Profile" class="tooltip rounded-full" src="{{ asset('Images/normalpicture.png') }}">
                    </div>
                    <div class="ml-4">
                        <div class="font-medium text-lg">
                            {{ $requirement->application->user->first_name }} {{ $requirement->application->user->last_name }}
                        </div>
                        <div class="text-slate-500">{{ $requirement->application->application_number }}</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-slate-500 text-xs">School</div>
                        <div class="font-medium">{{ $requirement->application->school }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Course</div>
                        <div class="font-medium">{{ $requirement->application->course }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">GWA</div>
                        <div class="font-medium">{{ $requirement->application->gwa }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Application Status</div>
                        <div class="font-medium">
                            <span class="px-2 py-1 rounded-full text-xs 
                                @if($requirement->application->status === 'pending') bg-warning text-white
                                @elseif($requirement->application->status === 'under_review') bg-primary text-white
                                @elseif($requirement->application->status === 'approved') bg-success text-white
                                @else bg-danger text-white @endif">
                                {{ ucfirst($requirement->application->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Preview -->
    <div class="intro-y col-span-12">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Document Preview</h2>
                <div class="flex">
                    <a href="{{ route('requirements.download', $requirement->id) }}" 
                       class="btn btn-outline-secondary btn-sm mr-2">
                        <i data-lucide="download" class="w-4 h-4 mr-1"></i>
                        Download
                    </a>
                    <button class="btn btn-outline-primary btn-sm preview-btn" 
                            data-id="{{ $requirement->id }}"
                            data-name="{{ $requirement->name }}">
                        <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                        Preview
                    </button>
                </div>
            </div>
            <div class="p-5">
                <div id="preview-container" class="text-center">
                    <div class="loading-spinner">
                        <i data-lucide="loader" class="w-8 h-8 animate-spin mx-auto"></i>
                        <div class="mt-2">Loading document preview...</div>
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

// Load preview on page load
document.addEventListener('DOMContentLoaded', function() {
    loadPreview();
});

function loadPreview() {
    const requirementId = '{{ $requirement->id }}';
    const fileType = '{{ $requirement->file_type }}';
    
    fetch(`/requirements/${requirementId}/preview`)
        .then(response => {
            if (response.ok) {
                return response.blob();
            }
            throw new Error('Failed to load preview');
        })
        .then(blob => {
            const url = URL.createObjectURL(blob);
            const container = document.getElementById('preview-container');
            
            if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) {
                container.innerHTML = `
                    <img src="${url}" class="max-w-full max-h-96 mx-auto" alt="Document Preview">
                `;
            } else if (fileType === 'pdf') {
                container.innerHTML = `
                    <iframe src="${url}" class="w-full h-96" frameborder="0"></iframe>
                `;
            } else {
                container.innerHTML = `
                    <div class="text-center">
                        <i data-lucide="file-text" class="w-16 h-16 mx-auto text-slate-400"></i>
                        <div class="mt-4 text-slate-500">Preview not available for this file type</div>
                        <a href="${url}" download class="btn btn-primary mt-4">Download to View</a>
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('preview-container').innerHTML = `
                <div class="text-center text-danger">
                    <i data-lucide="alert-circle" class="w-16 h-16 mx-auto"></i>
                    <div class="mt-4">Failed to load document preview</div>
                </div>
            `;
        });
}

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
            alert(data.error || `Failed to ${currentAction} document`);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(`Failed to ${currentAction} document`);
    });
});
</script>
@endpush
@endsection 