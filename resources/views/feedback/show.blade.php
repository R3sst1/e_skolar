@extends('layouts.app')

@section('title', 'Feedback Details')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Feedback Details</h2>
    <a href="{{ route('feedback.index') }}" class="btn btn-outline-secondary">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Feedback
    </a>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 lg:col-span-8">
        <div class="intro-y">
            <div class="box">
                <div class="p-5 border-b border-slate-200/60">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-medium text-base">{{ $feedback->title }}</h2>
                            <p class="text-slate-500 text-sm mt-1">Submitted on {{ $feedback->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 rounded-full text-xs
                                @if($feedback->category === 'academic') bg-primary text-white
                                @elseif($feedback->category === 'support') bg-success text-white
                                @elseif($feedback->category === 'financial') bg-warning text-white
                                @else bg-slate-200 text-slate-600 @endif">
                                {{ $feedback->category_label }}
                            </span>
                            @if($feedback->anonymous)
                                <span class="px-2 py-1 rounded-full text-xs bg-slate-200 text-slate-600">
                                    Anonymous
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12">
                            <label class="form-label">Rating</label>
                            <div class="flex items-center">
                                <span class="text-warning text-lg">{{ $feedback->rating_stars }}</span>
                                <span class="ml-2 text-sm text-slate-500">({{ $feedback->rating }}/5)</span>
                            </div>
                        </div>

                        <div class="col-span-12">
                            <label class="form-label">Your Feedback</label>
                            <div class="bg-slate-50 p-4 rounded-lg">
                                <p class="text-slate-700 whitespace-pre-wrap">{{ $feedback->message }}</p>
                            </div>
                        </div>

                        @if($feedback->admin_response)
                        <div class="col-span-12">
                            <label class="form-label">Admin Response</label>
                            <div class="bg-primary/10 p-4 rounded-lg border-l-4 border-primary">
                                <p class="text-slate-700 whitespace-pre-wrap">{{ $feedback->admin_response }}</p>
                                @if($feedback->reviewed_at)
                                    <p class="text-slate-500 text-sm mt-2">
                                        Responded on {{ $feedback->reviewed_at->format('M d, Y h:i A') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="col-span-12">
                            <label class="form-label">Status</label>
                            <div class="flex items-center">
                                <span class="px-2 py-1 rounded-full text-xs
                                    @if($feedback->status === 'submitted') bg-warning text-white
                                    @elseif($feedback->status === 'reviewed') bg-primary text-white
                                    @else bg-success text-white @endif">
                                    {{ $feedback->status_label }}
                                </span>
                                @if($feedback->status === 'submitted')
                                    <i data-lucide="clock" class="w-4 h-4 ml-1 text-warning"></i>
                                @elseif($feedback->status === 'reviewed')
                                    <i data-lucide="check-circle" class="w-4 h-4 ml-1 text-primary"></i>
                                @else
                                    <i data-lucide="check-square" class="w-4 h-4 ml-1 text-success"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-4">
        <div class="intro-y">
            <div class="box p-5">
                <div class="flex items-center mb-4">
                    <i data-lucide="info" class="w-5 h-5 text-primary mr-2"></i>
                    <h3 class="font-medium">Feedback Information</h3>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Category:</span>
                        <span class="font-medium">{{ $feedback->category_label }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Rating:</span>
                        <span class="font-medium">{{ $feedback->rating }}/5</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Status:</span>
                        <span class="font-medium">{{ $feedback->status_label }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Submitted:</span>
                        <span class="font-medium">{{ $feedback->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($feedback->reviewed_at)
                    <div class="flex justify-between">
                        <span class="text-slate-500">Reviewed:</span>
                        <span class="font-medium">{{ $feedback->reviewed_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @if($feedback->anonymous)
                    <div class="flex justify-between">
                        <span class="text-slate-500">Privacy:</span>
                        <span class="font-medium text-success">Anonymous</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if($feedback->status === 'submitted')
        <div class="intro-y mt-5">
            <div class="box p-5">
                <div class="flex items-center mb-4">
                    <i data-lucide="clock" class="w-5 h-5 text-warning mr-2"></i>
                    <h3 class="font-medium">Under Review</h3>
                </div>
                <p class="text-sm text-slate-600">
                    Your feedback is currently being reviewed by our administrators. 
                    You will receive a response once it has been processed.
                </p>
            </div>
        </div>
        @endif

        @if($feedback->status === 'reviewed' && !$feedback->admin_response)
        <div class="intro-y mt-5">
            <div class="box p-5">
                <div class="flex items-center mb-4">
                    <i data-lucide="check-circle" class="w-5 h-5 text-primary mr-2"></i>
                    <h3 class="font-medium">Reviewed</h3>
                </div>
                <p class="text-sm text-slate-600">
                    Your feedback has been reviewed by our administrators. 
                    Thank you for your valuable input!
                </p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 