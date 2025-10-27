@extends('layouts.app')

@section('title', 'My Feedback')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">My Feedback</h2>
    <a href="{{ route('feedback.create') }}" class="btn btn-primary">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Submit New Feedback
    </a>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- Statistics -->
    <div class="col-span-12 lg:col-span-3">
        <div class="intro-y">
            <div class="box p-5">
                <div class="flex items-center">
                    <div class="text-primary">
                        <i data-lucide="message-square" class="w-8 h-8"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-slate-500 text-xs">Total Feedback</div>
                        <div class="text-lg font-medium">{{ $feedbacks->total() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-3">
        <div class="intro-y">
            <div class="box p-5">
                <div class="flex items-center">
                    <div class="text-warning">
                        <i data-lucide="clock" class="w-8 h-8"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-slate-500 text-xs">Pending Review</div>
                        <div class="text-lg font-medium">{{ $feedbacks->where('status', 'submitted')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-3">
        <div class="intro-y">
            <div class="box p-5">
                <div class="flex items-center">
                    <div class="text-success">
                        <i data-lucide="check-circle" class="w-8 h-8"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-slate-500 text-xs">Reviewed</div>
                        <div class="text-lg font-medium">{{ $feedbacks->where('status', 'reviewed')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-3">
        <div class="intro-y">
            <div class="box p-5">
                <div class="flex items-center">
                    <div class="text-info">
                        <i data-lucide="star" class="w-8 h-8"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-slate-500 text-xs">Average Rating</div>
                        <div class="text-lg font-medium">{{ number_format($feedbacks->avg('rating'), 1) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12">
        <div class="intro-y">
            <div class="box">
                <div class="p-5 border-b border-slate-200/60">
                    <h2 class="font-medium text-base">Feedback History</h2>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="table table--sm">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap">TITLE</th>
                                    <th class="whitespace-nowrap">CATEGORY</th>
                                    <th class="whitespace-nowrap">RATING</th>
                                    <th class="whitespace-nowrap">STATUS</th>
                                    <th class="whitespace-nowrap">DATE</th>
                                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($feedbacks as $feedback)
                                <tr class="intro-x">
                                    <td>
                                        <div class="font-medium">{{ $feedback->title }}</div>
                                        @if($feedback->anonymous)
                                            <div class="text-slate-500 text-xs">Anonymous</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <span class="px-2 py-1 rounded-full text-xs
                                                @if($feedback->category === 'academic') bg-primary text-white
                                                @elseif($feedback->category === 'support') bg-success text-white
                                                @elseif($feedback->category === 'financial') bg-warning text-white
                                                @else bg-slate-200 text-slate-600 @endif">
                                                {{ $feedback->category_label }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <span class="text-warning">{{ $feedback->rating_stars }}</span>
                                            <span class="ml-2 text-sm">({{ $feedback->rating }}/5)</span>
                                        </div>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <div class="text-slate-500 text-xs">
                                            {{ $feedback->created_at->format('M d, Y h:i A') }}
                                        </div>
                                    </td>
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">
                                            <a href="{{ route('feedback.show', $feedback->id) }}"
                                               class="flex items-center text-primary whitespace-nowrap">
                                                <i data-lucide="eye" class="w-4 h-4 mr-1"></i> View Details
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8">
                                        <div class="text-slate-500">
                                            <i data-lucide="message-square" class="w-12 h-12 mx-auto mb-3"></i>
                                            <div>No feedback submitted yet</div>
                                            <div class="text-sm">Share your thoughts about the scholarship program</div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="p-5 border-t border-slate-200/60">
                    {{ $feedbacks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 