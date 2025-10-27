@extends('layouts.app')
@section('title', 'Edit Institution')
@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
         
            <a href="{{ route('institutions.index') }}" class="btn btn-outline-secondary">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Institutions
            </a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger mb-4">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('institutions.update', $institution) }}" method="POST" onsubmit="console.log('Form submitted');">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Institution Name *</label>
                            <input type="text" name="name" class="form-control @error('name') border-danger @enderror" value="{{ old('name', $institution->name) }}" required>
                            @error('name')
                                <div class="text-danger text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Type *</label>
                            <select name="type" class="form-select @error('type') border-danger @enderror" required>
                                <option value="">Select Type</option>
                                <option value="university" {{ old('type', $institution->type) === 'university' ? 'selected' : '' }}>University</option>
                                <option value="college" {{ old('type', $institution->type) === 'college' ? 'selected' : '' }}>College</option>
                                <option value="school" {{ old('type', $institution->type) === 'school' ? 'selected' : '' }}>School</option>
                            </select>
                            @error('type')
                                <div class="text-danger text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control @error('contact_person') border-danger @enderror" value="{{ old('contact_person', $institution->contact_person) }}">
                            @error('contact_person')
                                <div class="text-danger text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Contact Email</label>
                            <input type="email" name="contact_email" class="form-control @error('contact_email') border-danger @enderror" value="{{ old('contact_email', $institution->contact_email) }}">
                            @error('contact_email')
                                <div class="text-danger text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Contact Phone</label>
                            <input type="text" name="contact_phone" class="form-control @error('contact_phone') border-danger @enderror" value="{{ old('contact_phone', $institution->contact_phone) }}">
                            @error('contact_phone')
                                <div class="text-danger text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <div class="flex items-center mt-2">
                                <input type="checkbox" name="is_active" class="form-check-input" {{ old('is_active', $institution->is_active) ? 'checked' : '' }}>
                                <label class="ml-2">Active</label>
                            </div>
                        </div>

                        <div class="form-group md:col-span-2">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control @error('address') border-danger @enderror" value="{{ old('address', $institution->address) }}">
                            @error('address')
                                <div class="text-danger text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group md:col-span-2">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') border-danger @enderror" rows="3">{{ old('description', $institution->description) }}</textarea>
                            @error('description')
                                <div class="text-danger text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <a href="{{ route('institutions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary" onclick="console.log('Update button clicked');">Update Institution</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 