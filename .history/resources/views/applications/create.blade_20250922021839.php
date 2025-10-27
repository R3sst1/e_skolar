@extends('layouts.app')

@section('title', 'Apply for Scholarship')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Scholarship Application Form</h2>
</div>

<!-- Progress Indicator -->
<div class="intro-y box p-5 mt-5">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm font-medium">1</div>
            <div class="ml-3">
                <div class="font-medium">Personal Information</div>
                <div class="text-slate-500 text-xs">Basic details and contact info</div>
            </div>
        </div>
        <div class="flex items-center">
            <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-medium">2</div>
            <div class="ml-3">
                <div class="font-medium">Educational Details</div>
                <div class="text-slate-500 text-xs">School and academic information</div>
            </div>
        </div>
        <div class="flex items-center">
            <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-medium">3</div>
            <div class="ml-3">
                <div class="font-medium">Documents</div>
                <div class="text-slate-500 text-xs">Upload required documents</div>
            </div>
        </div>
        <div class="flex items-center">
            <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-medium">4</div>
            <div class="ml-3">
                <div class="font-medium">Review & Submit</div>
                <div class="text-slate-500 text-xs">Final review and submission</div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12">
        <form method="POST" action="{{ route('applications.store') }}" enctype="multipart/form-data" class="intro-y box p-5" id="applicationForm">
            @csrf
            
            <!-- Scholarship Program Selection -->
            <div class="mt-3">
                <label class="form-label">Scholarship Program <span class="text-danger">*</span></label>
                <select name="scholarship_id" class="form-select w-full" required>
                    <option value="">Select Program</option>
                    @foreach($scholarships as $scholarship)
                        <option value="{{ $scholarship->id }}" {{ old('scholarship_id') == $scholarship->id ? 'selected' : '' }}>
                            {{ $scholarship->name }}
                            @if($scholarship->description) - {{ $scholarship->description }} @endif
                        </option>
                    @endforeach
                </select>
                @error('scholarship_id')
                    <div class="text-danger text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Educational Information -->
            <div class="mt-5">
                <h2 class="font-medium text-base mr-auto">Educational Information</h2>
                
                <div class="mt-3">
                    <label class="form-label">School <span class="text-danger">*</span></label>
                    <input type="text" name="school" class="form-control w-full" value="{{ old('school', Auth::user()->school) }}" required>
                    @error('school')
                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <label class="form-label">Course <span class="text-danger">*</span></label>
                    <input type="text" name="course" class="form-control w-full" value="{{ old('course') }}" required>
                    @error('course')
                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Year Level <span class="text-danger">*</span></label>
                        <select name="year_level" class="form-select w-full" required>
                            <option value="">Select Year Level</option>
                            <option value="First Year" {{ old('year_level') == 'First Year' ? 'selected' : '' }}>First Year</option>
                            <option value="Second Year" {{ old('year_level') == 'Second Year' ? 'selected' : '' }}>Second Year</option>
                            <option value="Third Year" {{ old('year_level') == 'Third Year' ? 'selected' : '' }}>Third Year</option>
                            <option value="Fourth Year" {{ old('year_level') == 'Fourth Year' ? 'selected' : '' }}>Fourth Year</option>
                            <option value="Fifth Year" {{ old('year_level') == 'Fifth Year' ? 'selected' : '' }}>Fifth Year</option>
                        </select>
                        @error('year_level')
                            <div class="text-danger text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Semester <span class="text-danger">*</span></label>
                        <select name="semester" class="form-select w-full" required>
                            <option value="">Select Semester</option>
                            <option value="First" {{ old('semester') == 'First' ? 'selected' : '' }}>First Semester</option>
                            <option value="Second" {{ old('semester') == 'Second' ? 'selected' : '' }}>Second Semester</option>
                            <option value="Summer" {{ old('semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                        </select>
                        @error('semester')
                            <div class="text-danger text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">School Year <span class="text-danger">*</span></label>
                    <input type="text" name="school_year" class="form-control w-full" placeholder="e.g. 2023-2024" value="{{ old('school_year') }}" required>
                    @error('school_year')
                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Academic Information -->
            <div class="mt-5">
                <h2 class="font-medium text-base mr-auto">Academic Information</h2>
                
                <div class="mt-3">
                    <label class="form-label">Grade Weighted Average (GWA) <span class="text-danger">*</span></label>
                    <input type="number" name="gwa" class="form-control w-full" step="0.01" min="1.00" max="5.00" value="{{ old('gwa') }}" required>
                    <div class="text-slate-500 text-xs mt-1">Enter your GWA (1.00 is the highest, 5.00 is the lowest)</div>
                    @error('gwa')
                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <label class="form-label">Monthly Family Income <span class="text-danger">*</span></label>
                    <input type="number" name="family_income" class="form-control w-full" step="0.01" min="0" value="{{ old('family_income') }}" required>
                    <div class="text-slate-500 text-xs mt-1">Enter the total monthly income of your family</div>
                    @error('family_income')
                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <label class="form-label">Reason for Application <span class="text-danger">*</span></label>
                    <textarea name="reason_for_application" class="form-control w-full" rows="4" required placeholder="Please explain why you are applying for this scholarship...">{{ old('reason_for_application') }}</textarea>
                    @error('reason_for_application')
                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Grade Photo Upload -->
            <div class="mt-5">
                <h2 class="font-medium text-base mr-auto">Grade Photo Upload</h2>
                <div class="text-xs text-slate-500 mt-1 mb-3">Please upload a clear photo of your grades (JPG, PNG only, max 2MB)</div>
                
                <div class="mt-3">
                    <label class="form-label">Grade Photo <span class="text-danger">*</span></label>
                    <div class="file-upload-container">
                        <input type="file" name="grade_photo" id="grade_photo" class="form-control w-full" accept=".jpg,.jpeg,.png" required>
                        <div class="upload-status mt-1" id="uploadStatus"></div>
                        
                        <!-- Image Preview -->
                        <div class="mt-3" id="imagePreview" style="display: none;">
                            <img id="previewImg" src="" alt="Grade Photo Preview" class="max-w-full h-auto max-h-64 rounded-lg border border-slate-200">
                        </div>
                    </div>
                    @error('grade_photo')
                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end mt-5">
                <button type="button" onclick="window.history.back()" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                <button type="submit" class="btn btn-primary w-24" id="submitBtn">Submit</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const gradePhotoInput = document.getElementById('grade_photo');
    const uploadStatus = document.getElementById('uploadStatus');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const submitBtn = document.getElementById('submitBtn');

    // Handle grade photo upload
    gradePhotoInput.addEventListener('change', function() {
        const file = this.files[0];
        
        if (file) {
            // Check file size (2MB limit)
            if (file.size > 2 * 1024 * 1024) {
                uploadStatus.innerHTML = '<span class="text-danger text-xs">File too large (max 2MB)</span>';
                this.value = '';
                imagePreview.style.display = 'none';
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50');
                return;
            }

            // Check file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                uploadStatus.innerHTML = '<span class="text-danger text-xs">Invalid file type. Please upload JPG or PNG only.</span>';
                this.value = '';
                imagePreview.style.display = 'none';
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50');
                return;
            }

            // Show success message
            uploadStatus.innerHTML = '<span class="text-success text-xs">✓ ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)</span>';
            
            // Show image preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);

            // Enable submit button
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50');
        } else {
            uploadStatus.innerHTML = '';
            imagePreview.style.display = 'none';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50');
        }
    });

    // Form validation
    const form = document.getElementById('applicationForm');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-danger');
            } else {
                field.classList.remove('border-danger');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields and upload your grade photo.');
        }
    });
});
</script>
@endsection 