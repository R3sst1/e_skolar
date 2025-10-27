@extends('layouts.app')
@section('title', 'Renewal Status')
@section('content')
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <div class="intro-y box">
                <div class="flex items-center p-5 border-b border-slate-200/60">
                    <h2 class="font-medium text-base mr-auto">My Renewal Applications</h2>
                    <a href="{{ route('renewals.create') }}" class="btn btn-primary">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Apply for Renewal
                    </a>
                </div>
                <div class="p-5">
                    @if($renewals->count() > 0)
                        <div class="grid grid-cols-12 gap-6">
                            @foreach($renewals as $renewal)
                            <div class="col-span-12 lg:col-span-6">
                                <div class="box p-5">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <h3 class="font-medium text-lg">{{ $renewal->renewal_number }}</h3>
                                            <div class="text-slate-500 text-sm">{{ $renewal->semester }} Semester, {{ $renewal->school_year }}</div>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            @if($renewal->status === 'pending') bg-pending text-white
                                            @elseif($renewal->status === 'under_review') bg-warning text-white
                                            @elseif($renewal->status === 'approved') bg-success text-white
                                            @else bg-danger text-white @endif">
                                            {{ ucfirst(str_replace('_', ' ', $renewal->status)) }}
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="form-label text-slate-500 text-xs">GWA</label>
                                            <div class="font-medium text-lg text-primary">{{ $renewal->gwa }}</div>
                                        </div>
                                        <div>
                                            <label class="form-label text-slate-500 text-xs">Academic Status</label>
                                            <div class="font-medium">{{ $renewal->academic_status }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label text-slate-500 text-xs">Submitted</label>
                                        <div class="font-medium">{{ $renewal->submitted_at ? $renewal->submitted_at->format('M d, Y g:i A') : 'N/A' }}</div>
                                    </div>
                                    
                                    @if($renewal->admin_remarks)
                                    <div class="mb-4 p-3 bg-slate-50 rounded">
                                        <label class="form-label text-slate-500 text-xs">Admin Remarks</label>
                                        <div class="text-sm">{{ $renewal->admin_remarks }}</div>
                                    </div>
                                    @endif
                                    
                                    @if($renewal->status === 'approved' || $renewal->status === 'rejected')
                                    <div class="mb-4">
                                        <label class="form-label text-slate-500 text-xs">
                                            {{ $renewal->status === 'approved' ? 'Approved' : 'Rejected' }}
                                        </label>
                                        <div class="font-medium">
                                            {{ $renewal->status === 'approved' ? $renewal->approved_at->format('M d, Y g:i A') : $renewal->rejected_at->format('M d, Y g:i A') }}
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <!-- Documents -->
                                    @if($renewal->documents->count() > 0)
                                    <div class="border-t pt-4">
                                        <label class="form-label text-slate-500 text-xs mb-2">Uploaded Documents</label>
                                        <div class="space-y-2">
                                            @foreach($renewal->documents as $document)
                                            <div class="flex items-center justify-between p-2 bg-slate-50 rounded">
                                                <div class="flex items-center">
                                                    <i data-lucide="file-text" class="w-4 h-4 mr-2 text-slate-500"></i>
                                                    <div>
                                                        <div class="text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</div>
                                                        <div class="text-xs text-slate-500">{{ $document->file_name }}</div>
                                                    </div>
                                                </div>
                                                <span class="px-2 py-1 rounded-full text-xs 
                                                    @if($document->status === 'pending') bg-pending text-white
                                                    @elseif($document->status === 'approved') bg-success text-white
                                                    @else bg-danger text-white @endif">
                                                    {{ ucfirst($document->status) }}
                                                </span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i data-lucide="file-text" class="w-16 h-16 mx-auto text-slate-300 mb-4"></i>
                            <h3 class="text-lg font-medium mb-2">No Renewal Applications</h3>
                            <p class="text-slate-500 mb-4">You haven't submitted any renewal applications yet.</p>
                            <a href="{{ route('renewals.create') }}" class="btn btn-primary">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Apply for Renewal
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection 