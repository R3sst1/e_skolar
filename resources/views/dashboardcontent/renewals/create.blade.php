@extends('layouts.app')
@section('title', 'Apply for Renewal')
@section('content')
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <div class="intro-y box">
                <div class="flex items-center p-5 border-b border-slate-200/60">
                    <h2 class="font-medium text-base mr-auto">Renewal Application Form</h2>
                </div>
                <div class="p-5">
                    <form action="{{ route('renewals.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Scholar Information -->
                        <div class="grid grid-cols-12 gap-6 mb-6">
                            <div class="col-span-12">
                                <h3 class="text-lg font-medium mb-4">Scholar Information</h3>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" value="{{ $scholar->user->first_name }} {{ $scholar->user->last_name }}" readonly>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">Institution</label>
                                <input type="text" class="form-control" value="{{ $scholar->institution }}" readonly>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">Course</label>
                                <input type="text" class="form-control" value="{{ $scholar->course }}" readonly>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">Year Level</label>
                                <input type="text" class="form-control" value="{{ $scholar->year_level }}" readonly>
                            </div>
                        </div>

                        <!-- Renewal Details -->
                        <div class="grid grid-cols-12 gap-6 mb-6">
                            <div class="col-span-12">
                                <h3 class="text-lg font-medium mb-4">Renewal Details</h3>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">Semester <span class="text-danger">*</span></label>
                                <select name="semester" class="form-select @error('semester') border-danger @enderror" required>
                                    <option value="">Select Semester</option>
                                    <option value="First" {{ old('semester') == 'First' ? 'selected' : '' }}>First Semester</option>
                                    <option value="Second" {{ old('semester') == 'Second' ? 'selected' : '' }}>Second Semester</option>
                                    <option value="Summer" {{ old('semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                                </select>
                                @error('semester')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">School Year <span class="text-danger">*</span></label>
                                <input type="text" name="school_year" class="form-control @error('school_year') border-danger @enderror" 
                                       value="{{ old('school_year', '2024-2025') }}" placeholder="e.g., 2024-2025" required>
                                @error('school_year')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="grid grid-cols-12 gap-6 mb-6">
                            <div class="col-span-12">
                                <h3 class="text-lg font-medium mb-4">Academic Information</h3>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">Grade Weighted Average (GWA) <span class="text-danger">*</span></label>
                                <input type="number" name="gwa" step="0.01" min="1.0" max="4.0" 
                                       class="form-control @error('gwa') border-danger @enderror" 
                                       value="{{ old('gwa') }}" placeholder="e.g., 1.75" required>
                                <div class="text-slate-500 text-xs mt-1">Enter your GWA for the previous semester (1.0 - 4.0)</div>
                                @error('gwa')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">Academic Status <span class="text-danger">*</span></label>
                                <select name="academic_status" class="form-select @error('academic_status') border-danger @enderror" required>
                                    <option value="">Select Status</option>
                                    <option value="Good Standing" {{ old('academic_status') == 'Good Standing' ? 'selected' : '' }}>Good Standing</option>
                                    <option value="Probation" {{ old('academic_status') == 'Probation' ? 'selected' : '' }}>Probation</option>
                                    <option value="Warning" {{ old('academic_status') == 'Warning' ? 'selected' : '' }}>Warning</option>
                                    <option value="Conditional" {{ old('academic_status') == 'Conditional' ? 'selected' : '' }}>Conditional</option>
                                </select>
                                @error('academic_status')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-span-12">
                                <label class="form-label">Academic Remarks</label>
                                <textarea name="academic_remarks" rows="3" class="form-control @error('academic_remarks') border-danger @enderror" 
                                          placeholder="Any additional remarks about your academic performance...">{{ old('academic_remarks') }}</textarea>
                                @error('academic_remarks')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Document Upload -->
                        <div class="grid grid-cols-12 gap-6 mb-6">
                            <div class="col-span-12">
                                <h3 class="text-lg font-medium mb-4">Required Documents</h3>
                                <div class="text-slate-500 text-sm mb-4">Please upload the following documents (PDF, JPG, PNG, max 2MB each)</div>
                            </div>
                            
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">Grades/Transcript <span class="text-danger">*</span></label>
                                <input type="file" name="documents[]" class="form-control @error('documents.0') border-danger @enderror" 
                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                <input type="hidden" name="document_types[]" value="grades">
                                @error('documents.0')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">Certificate of Enrollment</label>
                                <input type="file" name="documents[]" class="form-control @error('documents.1') border-danger @enderror" 
                                       accept=".pdf,.jpg,.jpeg,.png">
                                <input type="hidden" name="document_types[]" value="enrollment">
                                @error('documents.1')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">Certificate of Good Moral Character</label>
                                <input type="file" name="documents[]" class="form-control @error('documents.2') border-danger @enderror" 
                                       accept=".pdf,.jpg,.jpeg,.png">
                                <input type="hidden" name="document_types[]" value="good_moral">
                                @error('documents.2')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label">Other Supporting Documents</label>
                                <input type="file" name="documents[]" class="form-control @error('documents.3') border-danger @enderror" 
                                       accept=".pdf,.jpg,.jpeg,.png">
                                <input type="hidden" name="document_types[]" value="other">
                                @error('documents.3')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end mt-6">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Renewal Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 