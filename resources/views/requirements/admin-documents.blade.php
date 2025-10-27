@extends('layouts.app')
@section('title', 'Uploaded Documents')
@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Uploaded Documents</h2>
</div>

<!-- Filters -->
<div class="intro-y box mt-5">
    <div class="flex items-center p-5 border-b border-slate-200/60">
        <h2 class="font-medium text-base mr-auto">Filters</h2>
    </div>
    <div class="p-5">
        <form method="GET" action="{{ route('admin.documents') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Applicant</label>
                <input type="text" name="applicant" value="{{ request('applicant') }}" class="form-control w-full" placeholder="Name or username">
            </div>
            <div>
                <label class="form-label">Document Type</label>
                <select name="type" class="form-select w-full">
                    <option value="">All Types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-select w-full">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-1 flex items-end">
                <button type="submit" class="btn btn-primary mr-2">
                    <i data-lucide="search" class="w-4 h-4 mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.documents') }}" class="btn btn-outline-secondary">
                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Documents Table -->
<div class="intro-y box mt-5">
    <div class="flex items-center p-5 border-b border-slate-200/60">
        <h2 class="font-medium text-base mr-auto">All Uploaded Documents</h2>
    </div>
    <div class="p-5">
        <div class="overflow-x-auto">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Document</th>
                        <th>Status</th>
                        <th>Uploaded At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                        <tr class="intro-x">
                            <td>
                                <div class="font-medium">{{ $doc->application->user->first_name }} {{ $doc->application->user->last_name }}</div>
                                <div class="text-slate-500 text-xs">{{ $doc->application->user->username }}</div>
                            </td>
                            <td>
                                <div class="font-medium">{{ $doc->name }}</div>
                                <div class="text-slate-500 text-xs">{{ $doc->file_type ?? '' }}</div>
                            </td>
                            <td>
                                @if($doc->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($doc->status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                            <td>{{ $doc->created_at ? $doc->created_at->format('M d, Y') : '-' }}</td>
                            <td>
                                <a href="{{ route('requirements.show', $doc->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i> View
                                </a>
                                <a href="{{ route('requirements.download', $doc->id) }}" class="btn btn-outline-secondary btn-sm">
                                    <i data-lucide="download" class="w-4 h-4 mr-1"></i> Download
                                </a>
                                <a href="{{ route('requirements.preview', $doc->id) }}" target="_blank" class="btn btn-outline-info btn-sm">
                                    <i data-lucide="file" class="w-4 h-4 mr-1"></i> Preview
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-slate-500">
                                No documents found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-5">
            {{ $documents->links() }}
        </div>
    </div>
</div>
@endsection 