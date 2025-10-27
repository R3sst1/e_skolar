@extends('layouts.app')
@section('title', 'Feedback Review')
@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Feedback Review</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('feedback.admin.index') }}" class="btn btn-outline-secondary shadow-md">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
        </a>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-8">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Feedback Information</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-slate-500 text-xs">Scholar</div>
                        <div class="font-medium">{{ $feedback->scholar->user->first_name }} {{ $feedback->scholar->user->last_name }}</div>
                        <div class="text-slate-500 text-xs">{{ $feedback->scholar->institution }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Semester</div>
                        <div class="font-medium">{{ $feedback->semester }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">School Year</div>
                        <div class="font-medium">{{ $feedback->school_year }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Rating</div>
                        <div class="font-medium">
                            @if($feedback->rating)
                                @for($i = 1; $i <= 5; $i++)
                                    <i data-lucide="star" class="w-4 h-4 {{ $i <= $feedback->rating ? 'text-warning' : 'text-slate-400' }} inline"></i>
                                @endfor
                            @else
                                <span class="text-slate-400">N/A</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs">Submitted At</div>
                        <div class="font-medium">{{ $feedback->submitted_at ? $feedback->submitted_at->format('M d, Y g:i A') : '-' }}</div>
                    </div>
                </div>
                <div class="mt-6">
                    <div class="text-slate-500 text-xs mb-1">Feedback</div>
                    <div class="font-medium text-base">{{ $feedback->feedback_text }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="intro-y col-span-12 lg:col-span-4">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Admin Review</h2>
            </div>
            <div class="p-5">
                <form method="POST" action="{{ route('feedback.admin.review', $feedback->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Admin Remarks</label>
                        <textarea name="admin_remarks" rows="4" class="form-control w-full">{{ old('admin_remarks', $feedback->admin_remarks) }}</textarea>
                        @error('admin_remarks')
                            <div class="text-danger text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="text-slate-500 text-xs">Reviewed At</div>
                        <div class="font-medium">{{ $feedback->reviewed_at ? $feedback->reviewed_at->format('M d, Y g:i A') : '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-slate-500 text-xs">Reviewed By</div>
                        <div class="font-medium">{{ $feedback->reviewer ? $feedback->reviewer->first_name . ' ' . $feedback->reviewer->last_name : '-' }}</div>
                    </div>
                    <div class="text-right mt-6">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i> Save Remarks
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 