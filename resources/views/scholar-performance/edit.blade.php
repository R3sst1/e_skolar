@extends('layouts.app')
@section('title', 'Edit Performance Record')
@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Edit Performance Record</h2>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Performance Information</h2>
            </div>
            <div class="p-5">
                <form method="POST" action="{{ route('scholar-performance.update', $performance->id) }}" id="performance-form">
                    @csrf
                    @method('PUT')
                    
                    <!-- Scholar Information (Read-only) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">Scholar</label>
                            <input type="text" class="form-control w-full" 
                                   value="{{ $performance->scholar->user->first_name }} {{ $performance->scholar->user->last_name }} ({{ $performance->scholar->institution }})" 
                                   readonly>
                        </div>

                        <div>
                            <label class="form-label">Semester & School Year</label>
                            <input type="text" class="form-control w-full" 
                                   value="{{ $performance->semester }} {{ $performance->school_year }}" 
                                   readonly>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="form-label">Grade Weighted Average (GWA) <span class="text-danger">*</span></label>
                            <input type="number" name="gwa" step="0.01" min="1.00" max="5.00" 
                                   class="form-control w-full" value="{{ old('gwa', $performance->gwa) }}" 
                                   placeholder="1.00 - 5.00" required>
                            @error('gwa')
                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Units Information -->
                    <div class="mt-6">
                        <h3 class="font-medium text-base mb-3">Units Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="form-label">Units Enrolled <span class="text-danger">*</span></label>
                                <input type="number" name="units_enrolled" min="0" 
                                       class="form-control w-full" value="{{ old('units_enrolled', $performance->units_enrolled) }}" required>
                                @error('units_enrolled')
                                    <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Units Completed <span class="text-danger">*</span></label>
                                <input type="number" name="units_completed" min="0" 
                                       class="form-control w-full" value="{{ old('units_completed', $performance->units_completed) }}" required>
                                @error('units_completed')
                                    <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Units Failed <span class="text-danger">*</span></label>
                                <input type="number" name="units_failed" min="0" 
                                       class="form-control w-full" value="{{ old('units_failed', $performance->units_failed) }}" required>
                                @error('units_failed')
                                    <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Subjects Information -->
                    <div class="mt-6">
                        <h3 class="font-medium text-base mb-3">Subjects Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div>
                                <label class="form-label">Subjects Enrolled <span class="text-danger">*</span></label>
                                <input type="number" name="subjects_enrolled" min="0" 
                                       class="form-control w-full" value="{{ old('subjects_enrolled', $performance->subjects_enrolled) }}" required>
                                @error('subjects_enrolled')
                                    <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Subjects Passed <span class="text-danger">*</span></label>
                                <input type="number" name="subjects_passed" min="0" 
                                       class="form-control w-full" value="{{ old('subjects_passed', $performance->subjects_passed) }}" required>
                                @error('subjects_passed')
                                    <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Subjects Failed <span class="text-danger">*</span></label>
                                <input type="number" name="subjects_failed" min="0" 
                                       class="form-control w-full" value="{{ old('subjects_failed', $performance->subjects_failed) }}" required>
                                @error('subjects_failed')
                                    <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Subjects Dropped <span class="text-danger">*</span></label>
                                <input type="number" name="subjects_dropped" min="0" 
                                       class="form-control w-full" value="{{ old('subjects_dropped', $performance->subjects_dropped) }}" required>
                                @error('subjects_dropped')
                                    <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Academic Remarks -->
                    <div class="mt-6">
                        <label class="form-label">Academic Remarks</label>
                        <textarea name="academic_remarks" rows="4" 
                                  class="form-control w-full" 
                                  placeholder="Additional remarks about the scholar's performance...">{{ old('academic_remarks', $performance->academic_remarks) }}</textarea>
                        @error('academic_remarks')
                            <div class="text-danger text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Current Status Preview -->
                    <div class="mt-6 p-4 bg-slate-50 rounded-md">
                        <h3 class="font-medium text-base mb-3">Current Status</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $performance->meetsGWARequirement() ? 'bg-success' : 'bg-danger' }} mr-3">
                                    <i data-lucide="{{ $performance->meetsGWARequirement() ? 'check' : 'x' }}" class="w-4 h-4 text-white"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-sm">GWA: {{ number_format($performance->gwa, 2) }}</div>
                                    <div class="text-slate-500 text-xs">
                                        {{ $performance->meetsGWARequirement() ? 'Meets' : 'Doesn\'t meet' }} requirement (≤{{ \App\Models\SystemSetting::getValue('minimum_gwa_for_retention', 2.5) }})
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $performance->meetsUnitsRequirement() ? 'bg-success' : 'bg-danger' }} mr-3">
                                    <i data-lucide="{{ $performance->meetsUnitsRequirement() ? 'check' : 'x' }}" class="w-4 h-4 text-white"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-sm">Units: {{ $performance->units_completed }}/{{ $performance->units_enrolled }}</div>
                                    <div class="text-slate-500 text-xs">
                                        {{ $performance->meetsUnitsRequirement() ? 'Meets' : 'Doesn\'t meet' }} requirement (≥{{ \App\Models\SystemSetting::getValue('minimum_units_per_semester', 12) }})
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $performance->meetsNoFailedSubjectsRequirement() ? 'bg-success' : 'bg-danger' }} mr-3">
                                    <i data-lucide="{{ $performance->meetsNoFailedSubjectsRequirement() ? 'check' : 'x' }}" class="w-4 h-4 text-white"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-sm">Failed: {{ $performance->subjects_failed }}</div>
                                    <div class="text-slate-500 text-xs">
                                        {{ $performance->meetsNoFailedSubjectsRequirement() ? 'Meets' : 'Doesn\'t meet' }} requirement (0 failed)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="text-right mt-6">
                        <a href="{{ route('scholar-performance.show', $performance->id) }}" class="btn btn-outline-secondary mr-2">
                            <i data-lucide="x" class="w-4 h-4 mr-2"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i> Update Performance Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Form validation
document.getElementById('performance-form').addEventListener('submit', function(e) {
    const unitsEnrolled = parseInt(document.querySelector('input[name="units_enrolled"]').value);
    const unitsCompleted = parseInt(document.querySelector('input[name="units_completed"]').value);
    const unitsFailed = parseInt(document.querySelector('input[name="units_failed"]').value);
    const subjectsEnrolled = parseInt(document.querySelector('input[name="subjects_enrolled"]').value);
    const subjectsPassed = parseInt(document.querySelector('input[name="subjects_passed"]').value);
    const subjectsFailed = parseInt(document.querySelector('input[name="subjects_failed"]').value);
    const subjectsDropped = parseInt(document.querySelector('input[name="subjects_dropped"]').value);

    // Validate units
    if (unitsCompleted + unitsFailed > unitsEnrolled) {
        e.preventDefault();
        alert('Units completed + units failed cannot exceed units enrolled.');
        return;
    }

    // Validate subjects
    if (subjectsPassed + subjectsFailed + subjectsDropped > subjectsEnrolled) {
        e.preventDefault();
        alert('Subjects passed + failed + dropped cannot exceed subjects enrolled.');
        return;
    }
});
</script>
@endpush
@endsection 