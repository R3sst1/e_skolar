@extends('layouts.app')
@section('title', 'Add Performance Record')
@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Add Performance Record</h2>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Performance Information</h2>
            </div>
            <div class="p-5">
                <form method="POST" action="{{ route('scholar-performance.store') }}" id="performance-form">
                    @csrf
                    
                    <!-- Scholar Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">Scholar <span class="text-danger">*</span></label>
                            <select name="scholar_id" class="form-select w-full" required>
                                <option value="">Select Scholar</option>
                                @foreach($scholars as $scholar)
                                    <option value="{{ $scholar->id }}" {{ old('scholar_id') == $scholar->id ? 'selected' : '' }}>
                                        {{ $scholar->user->first_name }} {{ $scholar->user->last_name }} 
                                        ({{ $scholar->institution }})
                                    </option>
                                @endforeach
                            </select>
                            @error('scholar_id')
                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Semester <span class="text-danger">*</span></label>
                            <select name="semester" class="form-select w-full" required>
                                <option value="">Select Semester</option>
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester }}" {{ old('semester') == $semester ? 'selected' : '' }}>
                                        {{ $semester }}
                                    </option>
                                @endforeach
                            </select>
                            @error('semester')
                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="form-label">School Year <span class="text-danger">*</span></label>
                            <select name="school_year" class="form-select w-full" required>
                                <option value="">Select School Year</option>
                                @foreach($schoolYears as $year)
                                    <option value="{{ $year }}" {{ old('school_year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                            @error('school_year')
                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Grade Weighted Average (GWA) <span class="text-danger">*</span></label>
                            <input type="number" name="gwa" step="0.01" min="1.00" max="5.00" 
                                   class="form-control w-full" value="{{ old('gwa') }}" 
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
                                       class="form-control w-full" value="{{ old('units_enrolled') }}" required>
                                @error('units_enrolled')
                                    <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Units Completed <span class="text-danger">*</span></label>
                                <input type="number" name="units_completed" min="0" 
                                       class="form-control w-full" value="{{ old('units_completed') }}" required>
                                @error('units_completed')
                                    <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Units Failed <span class="text-danger">*</span></label>
                                <input type="number" name="units_failed" min="0" 
                                       class="form-control w-full" value="{{ old('units_failed') }}" required>
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
                                       class="form-control w-full" value="{{ old('subjects_enrolled') }}" required>
                                @error('subjects_enrolled')
                                    <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Subjects Passed <span class="text-danger">*</span></label>
                                <input type="number" name="subjects_passed" min="0" 
                                       class="form-control w-full" value="{{ old('subjects_passed') }}" required>
                                @error('subjects_passed')
                                    <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Subjects Failed <span class="text-danger">*</span></label>
                                <input type="number" name="subjects_failed" min="0" 
                                       class="form-control w-full" value="{{ old('subjects_failed') }}" required>
                                @error('subjects_failed')
                                    <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Subjects Dropped <span class="text-danger">*</span></label>
                                <input type="number" name="subjects_dropped" min="0" 
                                       class="form-control w-full" value="{{ old('subjects_dropped') }}" required>
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
                                  placeholder="Additional remarks about the scholar's performance...">{{ old('academic_remarks') }}</textarea>
                        @error('academic_remarks')
                            <div class="text-danger text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Requirements Check -->
                    <div class="mt-6 p-4 bg-slate-50 rounded-md">
                        <h3 class="font-medium text-base mb-3">Retention Requirements Check</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-slate-200 mr-3">
                                    <i data-lucide="check" class="w-4 h-4 text-slate-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-sm">GWA Requirement</div>
                                    <div class="text-slate-500 text-xs">≤ {{ \App\Models\SystemSetting::getValue('minimum_gwa_for_retention', 2.5) }}</div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-slate-200 mr-3">
                                    <i data-lucide="check" class="w-4 h-4 text-slate-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-sm">Units Requirement</div>
                                    <div class="text-slate-500 text-xs">≥ {{ \App\Models\SystemSetting::getValue('minimum_units_per_semester', 12) }}</div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-slate-200 mr-3">
                                    <i data-lucide="check" class="w-4 h-4 text-slate-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-sm">No Failed Subjects</div>
                                    <div class="text-slate-500 text-xs">0 failed subjects</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="text-right mt-6">
                        <a href="{{ route('scholar-performance.index') }}" class="btn btn-outline-secondary mr-2">
                            <i data-lucide="x" class="w-4 h-4 mr-2"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i> Save Performance Record
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