@extends('layouts.app')
@section('title', 'Renewal Details')
@section('content')
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <div class="intro-y box">
                <div class="flex items-center p-5 border-b border-slate-200/60">
                    <h2 class="font-medium text-base mr-auto">Renewal Application Details</h2>
                    <a href="{{ route('renewals.index') }}" class="btn btn-outline-secondary">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to List
                    </a>
                </div>
                <div class="p-5">
                    <!-- Renewal Header -->
                    <div class="grid grid-cols-12 gap-6 mb-6">
                        <div class="col-span-12 sm:col-span-6">
                            <h3 class="text-lg font-medium mb-4">Renewal Information</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label text-slate-500">Renewal Number</label>
                                    <div class="font-medium">{{ $renewal->renewal_number }}</div>
                                </div>
                                <div>
                                    <label class="form-label text-slate-500">Status</label>
                                    <div>
                                        <span class="px-2 py-1 rounded-full text-xs 
                                            @if($renewal->status === 'pending') bg-pending text-white
                                            @elseif($renewal->status === 'under_review') bg-warning text-white
                                            @elseif($renewal->status === 'approved') bg-success text-white
                                            @else bg-danger text-white @endif">
                                            {{ ucfirst(str_replace('_', ' ', $renewal->status)) }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label text-slate-500">Semester</label>
                                    <div class="font-medium">{{ $renewal->semester }}</div>
                                </div>
                                <div>
                                    <label class="form-label text-slate-500">School Year</label>
                                    <div class="font-medium">{{ $renewal->school_year }}</div>
                                </div>
                                <div>
                                    <label class="form-label text-slate-500">Submitted</label>
                                    <div class="font-medium">{{ $renewal->submitted_at ? $renewal->submitted_at->format('M d, Y g:i A') : 'N/A' }}</div>
                                </div>
                                @if($renewal->reviewed_at)
                                <div>
                                    <label class="form-label text-slate-500">Reviewed</label>
                                    <div class="font-medium">{{ $renewal->reviewed_at->format('M d, Y g:i A') }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-span-12 sm:col-span-6">
                            <h3 class="text-lg font-medium mb-4">Scholar Information</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label text-slate-500">Name</label>
                                    <div class="font-medium">
                                        <a href="{{ route('view.profile.other', $renewal->scholar->user_id) }}" class="text-primary">
                                            {{ $renewal->scholar->user->first_name }} {{ $renewal->scholar->user->last_name }}
                                        </a>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label text-slate-500">Institution</label>
                                    <div class="font-medium">{{ $renewal->scholar->institution }}</div>
                                </div>
                                <div>
                                    <label class="form-label text-slate-500">Course</label>
                                    <div class="font-medium">{{ $renewal->scholar->course }}</div>
                                </div>
                                <div>
                                    <label class="form-label text-slate-500">Year Level</label>
                                    <div class="font-medium">{{ $renewal->scholar->year_level }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="grid grid-cols-12 gap-6 mb-6">
                        <div class="col-span-12">
                            <h3 class="text-lg font-medium mb-4">Academic Information</h3>
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12 sm:col-span-4">
                                    <label class="form-label text-slate-500">Grade Weighted Average (GWA)</label>
                                    <div class="text-2xl font-bold text-primary">{{ $renewal->gwa }}</div>
                                </div>
                                <div class="col-span-12 sm:col-span-4">
                                    <label class="form-label text-slate-500">Academic Status</label>
                                    <div class="font-medium">{{ $renewal->academic_status }}</div>
                                </div>
                                <div class="col-span-12 sm:col-span-4">
                                    <label class="form-label text-slate-500">Academic Remarks</label>
                                    <div class="font-medium">{{ $renewal->academic_remarks ?: 'No remarks' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="grid grid-cols-12 gap-6 mb-6">
                        <div class="col-span-12">
                            <h3 class="text-lg font-medium mb-4">Submitted Documents</h3>
                            <div class="grid grid-cols-12 gap-4">
                                @forelse($renewal->documents as $document)
                                <div class="col-span-12 sm:col-span-6 lg:col-span-4">
                                    <div class="box p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="font-medium">{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</div>
                                            <span class="px-2 py-1 rounded-full text-xs 
                                                @if($document->status === 'pending') bg-pending text-white
                                                @elseif($document->status === 'approved') bg-success text-white
                                                @else bg-danger text-white @endif">
                                                {{ ucfirst($document->status) }}
                                            </span>
                                        </div>
                                        <div class="text-slate-500 text-sm mb-2">{{ $document->file_name }}</div>
                                        <div class="text-slate-500 text-xs mb-3">{{ $document->getFileSize() }}</div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('renewals.download', $document) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i data-lucide="download" class="w-4 h-4 mr-1"></i> Download
                                            </a>
                                        </div>
                                        @if($document->admin_remarks)
                                        <div class="mt-2 p-2 bg-slate-100 rounded text-xs">
                                            <strong>Admin Remarks:</strong> {{ $document->admin_remarks }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @empty
                                <div class="col-span-12 text-center py-4">
                                    <p class="text-slate-500">No documents uploaded.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Admin Review Section -->
                    @if($renewal->status === 'pending' || $renewal->status === 'under_review')
                    <div class="grid grid-cols-12 gap-6 mb-6">
                        <div class="col-span-12">
                            <h3 class="text-lg font-medium mb-4">Review & Decision</h3>
                            <form action="{{ route('renewals.review', $renewal) }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-12 gap-6">
                                    <div class="col-span-12">
                                        <label class="form-label">Admin Remarks</label>
                                        <textarea name="admin_remarks" rows="3" class="form-control" 
                                                  placeholder="Enter your remarks about this renewal application...">{{ $renewal->admin_remarks }}</textarea>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <button type="submit" name="status" value="approved" 
                                                class="btn btn-success w-full" 
                                                onclick="return confirm('Are you sure you want to approve this renewal?')">
                                            <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i> Approve Renewal
                                        </button>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <button type="submit" name="status" value="rejected" 
                                                class="btn btn-danger w-full"
                                                onclick="return confirm('Are you sure you want to reject this renewal?')">
                                            <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i> Reject Renewal
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @else
                    <!-- Decision Display -->
                    <div class="grid grid-cols-12 gap-6 mb-6">
                        <div class="col-span-12">
                            <h3 class="text-lg font-medium mb-4">Decision</h3>
                            <div class="box p-4">
                                <div class="flex items-center mb-2">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium
                                        @if($renewal->status === 'approved') bg-success text-white
                                        @else bg-danger text-white @endif">
                                        {{ ucfirst($renewal->status) }}
                                    </span>
                                    <span class="ml-2 text-slate-500">
                                        {{ $renewal->status === 'approved' ? $renewal->approved_at->format('M d, Y g:i A') : $renewal->rejected_at->format('M d, Y g:i A') }}
                                    </span>
                                </div>
                                @if($renewal->admin_remarks)
                                <div class="text-slate-600">
                                    <strong>Admin Remarks:</strong> {{ $renewal->admin_remarks }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection 