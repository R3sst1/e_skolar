@extends('layouts.app')

@section('title', 'Submit Feedback')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Submit Feedback</h2>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 lg:col-span-8">
        <div class="intro-y">
            <div class="box">
                <div class="p-5 border-b border-slate-200/60">
                    <h2 class="font-medium text-base">Share Your Thoughts</h2>
                    <p class="text-slate-500 text-sm mt-1">Help us improve the scholarship program by sharing your feedback</p>
                </div>
                <div class="p-5">
                    <form action="{{ route('feedback.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12">
                                <label for="title" class="form-label">Feedback Title <span class="text-danger">*</span></label>
                                <input type="text" id="title" name="title" class="form-control @error('title') border-danger @enderror" 
                                       value="{{ old('title') }}" placeholder="Brief title for your feedback" required>
                                @error('title')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-12 lg:col-span-6">
                                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                <select id="category" name="category" class="form-select @error('category') border-danger @enderror" required>
                                    <option value="">Select Category</option>
                                    <option value="academic" {{ old('category') === 'academic' ? 'selected' : '' }}>Academic Support</option>
                                    <option value="support" {{ old('category') === 'support' ? 'selected' : '' }}>General Support</option>
                                    <option value="financial" {{ old('category') === 'financial' ? 'selected' : '' }}>Financial Matters</option>
                                    <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>General Feedback</option>
                                </select>
                                @error('category')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-12 lg:col-span-6">
                                <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                                <div class="flex items-center space-x-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" id="rating_{{ $i }}" name="rating" value="{{ $i }}" 
                                               class="form-check-input" {{ old('rating') == $i ? 'checked' : '' }} required>
                                        <label for="rating_{{ $i }}" class="form-check-label">
                                            <i data-lucide="star" class="w-5 h-5 text-warning"></i>
                                        </label>
                                    @endfor
                                </div>
                                @error('rating')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-12">
                                <label for="message" class="form-label">Your Feedback <span class="text-danger">*</span></label>
                                <textarea id="message" name="message" rows="6" 
                                          class="form-control @error('message') border-danger @enderror" 
                                          placeholder="Please share your detailed feedback, suggestions, or concerns..." required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-12">
                                <div class="flex items-center">
                                    <input type="checkbox" id="anonymous" name="anonymous" value="1" 
                                           class="form-check-input" {{ old('anonymous') ? 'checked' : '' }}>
                                    <label for="anonymous" class="form-check-label ml-2">
                                        Submit anonymously (your name will not be shown to administrators)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-8">
                            <a href="{{ route('feedback.index') }}" class="btn btn-outline-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="send" class="w-4 h-4 mr-2"></i> Submit Feedback
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-4">
        <div class="intro-y">
            <div class="box p-5">
                <div class="flex items-center mb-4">
                    <i data-lucide="info" class="w-5 h-5 text-primary mr-2"></i>
                    <h3 class="font-medium">Feedback Guidelines</h3>
                </div>
                <ul class="text-sm text-slate-600 space-y-2">
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-4 h-4 text-success mr-2 mt-0.5"></i>
                        Be specific and constructive
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-4 h-4 text-success mr-2 mt-0.5"></i>
                        Include both positive and improvement areas
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-4 h-4 text-success mr-2 mt-0.5"></i>
                        Provide actionable suggestions
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-4 h-4 text-success mr-2 mt-0.5"></i>
                        Your feedback helps improve the program
                    </li>
                </ul>
            </div>
        </div>

        <div class="intro-y mt-5">
            <div class="box p-5">
                <div class="flex items-center mb-4">
                    <i data-lucide="shield" class="w-5 h-5 text-success mr-2"></i>
                    <h3 class="font-medium">Privacy & Security</h3>
                </div>
                <div class="text-sm text-slate-600 space-y-2">
                    <p>Your feedback is confidential and will only be shared with authorized administrators.</p>
                    <p>You can choose to submit anonymously if you prefer.</p>
                    <p>All feedback is reviewed and used to improve the scholarship program.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Star rating functionality
    const ratingInputs = document.querySelectorAll('input[name="rating"]');
    const starLabels = document.querySelectorAll('label[for^="rating_"] i');
    
    ratingInputs.forEach((input, index) => {
        input.addEventListener('change', function() {
            starLabels.forEach((star, starIndex) => {
                if (starIndex <= index) {
                    star.classList.add('text-warning');
                } else {
                    star.classList.remove('text-warning');
                }
            });
        });
    });
});
</script>
@endsection 