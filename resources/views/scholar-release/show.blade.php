@extends('layouts.app')
@section('title', 'Disbursement Details')
@section('content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <h2 class="intro-y text-lg font-medium mr-auto">Disbursement Details</h2>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <a href="{{ route('scholar-release.index') }}" class="btn btn-outline-secondary shadow-md mr-2">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Scholar Information -->
    <div class="col-span-12 lg:col-span-6">
        <div class="box p-5">
            <h3 class="text-lg font-medium mb-4">Scholar Information</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-slate-600">Name:</span>
                    <span class="font-medium">
                        {{ $disbursement->scholar->user->first_name ?? 'N/A' }} 
                        {{ $disbursement->scholar->user->last_name ?? 'N/A' }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Course:</span>
                    <span class="font-medium">{{ $disbursement->scholar->course ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Year Level:</span>
                    <span class="font-medium">{{ $disbursement->scholar->year_level ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Institution:</span>
                    <span class="font-medium">{{ $disbursement->scholar->institution ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Email:</span>
                    <span class="font-medium">{{ $disbursement->scholar->user->email ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Phone:</span>
                    <span class="font-medium">{{ $disbursement->scholar->user->phone_number ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Disbursement Information -->
    <div class="col-span-12 lg:col-span-6">
        <div class="box p-5">
            <h3 class="text-lg font-medium mb-4">Disbursement Information</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-slate-600">Scholarship Program:</span>
                    <span class="font-medium">{{ $disbursement->disbursementBatch->scholarshipProgram->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Batch Reference:</span>
                    <span class="font-medium">{{ $disbursement->disbursementBatch->reference_number ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Requested Amount:</span>
                    <span class="font-medium text-primary">₱{{ number_format($disbursement->requested_amount ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Actual Amount:</span>
                    <span class="font-medium text-success">
                        @if($disbursement->actual_amount)
                            ₱{{ number_format($disbursement->actual_amount, 2) }}
                        @else
                            <span class="text-slate-400">Not set</span>
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Status:</span>
                    <span class="px-2 py-1 rounded-full text-xs 
                        {{ $disbursement->status === 'pending' ? 'bg-warning text-white' : 
                           ($disbursement->status === 'approved' ? 'bg-primary text-white' : 
                           ($disbursement->status === 'disbursed' ? 'bg-success text-white' : 'bg-danger text-white')) }}">
                        {{ ucfirst($disbursement->status) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Release Status:</span>
                    <span class="px-2 py-1 rounded-full text-xs {{ $disbursement->release_status_badge }}">
                        {{ ucfirst($disbursement->release_status) }}
                    </span>
                </div>
                @if($disbursement->released_at)
                <div class="flex justify-between">
                    <span class="text-slate-600">Released Date:</span>
                    <span class="font-medium">{{ $disbursement->formatted_released_at }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Time Since Release:</span>
                    <span class="font-medium text-slate-500">{{ $disbursement->time_since_release }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Remarks Section -->
    @if($disbursement->remarks || $disbursement->release_remarks)
    <div class="col-span-12">
        <div class="box p-5">
            <h3 class="text-lg font-medium mb-4">Remarks</h3>
            @if($disbursement->remarks)
            <div class="mb-4">
                <h4 class="font-medium text-sm text-slate-600 mb-2">General Remarks:</h4>
                <p class="text-sm">{{ $disbursement->remarks }}</p>
            </div>
            @endif
            @if($disbursement->release_remarks)
            <div class="mb-4">
                <h4 class="font-medium text-sm text-slate-600 mb-2">Release Remarks:</h4>
                <p class="text-sm">{{ $disbursement->release_remarks }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="col-span-12">
        <div class="box p-5">
            <div class="flex justify-center gap-4">
                @if($disbursement->release_status === 'unreleased')
                <button class="btn btn-success" 
                        data-tw-toggle="modal" 
                        data-tw-target="#release-modal">
                    <i data-lucide="send" class="w-4 h-4 mr-2"></i> Release Disbursement
                </button>
                @else
                <form method="POST" action="{{ route('scholar-release.unrelease', $disbursement->id) }}" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to unrelease this disbursement?')">
                        <i data-lucide="undo" class="w-4 h-4 mr-2"></i> Unrelease Disbursement
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Release Modal -->
@if($disbursement->release_status === 'unreleased')
<div id="release-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Release Disbursement</h2>
                <button data-tw-dismiss="modal" class="btn btn-outline-secondary hidden sm:flex">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('scholar-release.release', $disbursement->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">Scholar Name</label>
                        <input type="text" class="form-control" 
                               value="{{ $disbursement->scholar->user->first_name ?? 'N/A' }} {{ $disbursement->scholar->user->last_name ?? 'N/A' }}" 
                               readonly>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Requested Amount</label>
                        <input type="text" class="form-control" 
                               value="₱{{ number_format($disbursement->requested_amount ?? 0, 2) }}" 
                               readonly>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Actual Amount to Release *</label>
                        <input type="number" name="actual_amount" class="form-control" 
                               value="{{ $disbursement->requested_amount ?? 0 }}" 
                               min="0" step="0.01" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Release Remarks (Optional)</label>
                        <textarea name="release_remarks" class="form-control" rows="3" 
                                  placeholder="Add any remarks for this release..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                    <button type="submit" class="btn btn-primary w-20">Release</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
