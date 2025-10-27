@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12">
       
        
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12">
                <div class="intro-y box">
                    <div class="flex items-center p-5 border-b border-slate-200/60">
                        <h2 class="font-medium text-base mr-auto">Configuration Management</h2>
                    </div>
                    <div class="p-5">
                        <form id="systemSettingsForm">
                            @csrf
                            
                            <!-- Scholar Limits -->
                            <!-- <div class="settings-category mb-6">
                                <h5 class="text-primary mb-3 font-medium">
                                    <i data-lucide="users" class="w-4 h-4 inline mr-2"></i>Scholar Limits
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($settings['limits'] ?? [] as $setting)
                                    <div class="form-group">
                                        <label for="{{ $setting->key }}" class="form-label">{{ $setting->description }}</label>
                                        <input type="number" 
                                               class="form-control" 
                                               id="{{ $setting->key }}" 
                                               name="settings[{{ $setting->key }}][value]" 
                                               value="{{ $setting->value }}"
                                               min="1">
                                        <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                        <div class="form-text text-xs text-slate-500">Key: {{ $setting->key }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div> -->

                            <!-- Disbursement Settings -->
                            <div class="settings-category mb-6">
                                <h5 class="text-success mb-3 font-medium">
                                    <i data-lucide="credit-card" class="w-4 h-4 inline mr-2"></i>Disbursement Settings
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($settings['disbursement'] ?? [] as $setting)
                                    <div class="form-group">
                                        <label for="{{ $setting->key }}" class="form-label">{{ $setting->description }}</label>
                                        @if($setting->type === 'json')
                                            <textarea class="form-control" 
                                                      id="{{ $setting->key }}" 
                                                      name="settings[{{ $setting->key }}][value]" 
                                                      rows="3">{{ is_array($setting->value) ? implode("\n", $setting->value) : $setting->value }}</textarea>
                                        @else
                                            <input type="{{ $setting->type === 'integer' ? 'number' : 'text' }}" 
                                                   class="form-control" 
                                                   id="{{ $setting->key }}" 
                                                   name="settings[{{ $setting->key }}][value]" 
                                                   value="{{ $setting->value }}"
                                                   @if($setting->type === 'integer') min="0" @endif>
                                        @endif
                                        <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                        <div class="form-text text-xs text-slate-500">Key: {{ $setting->key }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Email Notifications -->
                            <div class="settings-category mb-6">
                                <h5 class="text-info mb-3 font-medium">
                                    <i data-lucide="mail" class="w-4 h-4 inline mr-2"></i>Email Notifications
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($settings['notifications'] ?? [] as $setting)
                                    <div class="form-group">
                                        <label for="{{ $setting->key }}" class="form-label">{{ $setting->description }}</label>
                                        @if($setting->type === 'boolean')
                                            <select class="form-select" 
                                                    id="{{ $setting->key }}" 
                                                    name="settings[{{ $setting->key }}][value]">
                                                <option value="1" {{ $setting->value ? 'selected' : '' }}>Enabled</option>
                                                <option value="0" {{ !$setting->value ? 'selected' : '' }}>Disabled</option>
                                            </select>
                                        @else
                                            <input type="{{ $setting->type === 'integer' ? 'number' : 'text' }}" 
                                                   class="form-control" 
                                                   id="{{ $setting->key }}" 
                                                   name="settings[{{ $setting->key }}][value]" 
                                                   value="{{ $setting->value }}"
                                                   @if($setting->type === 'integer') min="1" @endif>
                                        @endif
                                        <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                        <div class="form-text text-xs text-slate-500">Key: {{ $setting->key }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Retention Requirements -->
                            <div class="settings-category mb-6">
                                <h5 class="text-warning mb-3 font-medium">
                                    <i data-lucide="trending-up" class="w-4 h-4 inline mr-2"></i>Retention Requirements
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($settings['retention'] ?? [] as $setting)
                                    <div class="form-group">
                                        <label for="{{ $setting->key }}" class="form-label">{{ $setting->description }}</label>
                                        <input type="{{ $setting->type === 'integer' ? 'number' : 'number' }}" 
                                               class="form-control" 
                                               id="{{ $setting->key }}" 
                                               name="settings[{{ $setting->key }}][value]" 
                                               value="{{ $setting->value }}"
                                               step="{{ $setting->type === 'decimal' ? '0.1' : '1' }}"
                                               min="0">
                                        <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                        <div class="form-text text-xs text-slate-500">Key: {{ $setting->key }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Renewal Criteria -->
                            <div class="settings-category mb-6">
                                <h5 class="text-info mb-3 font-medium">
                                    <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>Renewal Criteria
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($settings['renewal'] ?? [] as $setting)
                                    <div class="form-group">
                                        <label for="{{ $setting->key }}" class="form-label">{{ $setting->description }}</label>
                                        @if($setting->type === 'boolean')
                                            <select class="form-select" 
                                                    id="{{ $setting->key }}" 
                                                    name="settings[{{ $setting->key }}][value]">
                                                <option value="1" {{ $setting->value ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ !$setting->value ? 'selected' : '' }}>No</option>
                                            </select>
                                        @elseif($setting->type === 'json')
                                            <textarea class="form-control" 
                                                      id="{{ $setting->key }}" 
                                                      name="settings[{{ $setting->key }}][value]" 
                                                      rows="3">{{ is_array($setting->value) ? implode("\n", $setting->value) : $setting->value }}</textarea>
                                        @else
                                            <input type="{{ $setting->type === 'integer' ? 'number' : 'text' }}" 
                                                   class="form-control" 
                                                   id="{{ $setting->key }}" 
                                                   name="settings[{{ $setting->key }}][value]" 
                                                   value="{{ $setting->value }}"
                                                   @if($setting->type === 'integer') min="1" @endif>
                                        @endif
                                        <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                        <div class="form-text text-xs text-slate-500">Key: {{ $setting->key }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- General Settings -->
                            <div class="settings-category mb-6">
                                <h5 class="text-secondary mb-3 font-medium">
                                    <i data-lucide="settings" class="w-4 h-4 inline mr-2"></i>General Settings
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($settings['general'] ?? [] as $setting)
                                    @if($setting->key !== 'application_deadline_days')
                                    <div class="form-group">
                                        <label for="{{ $setting->key }}" class="form-label">{{ $setting->description }}</label>
                                        @if($setting->type === 'boolean')
                                            <select class="form-select" 
                                                    id="{{ $setting->key }}" 
                                                    name="settings[{{ $setting->key }}][value]">
                                                <option value="1" {{ $setting->value ? 'selected' : '' }}>Enabled</option>
                                                <option value="0" {{ !$setting->value ? 'selected' : '' }}>Disabled</option>
                                            </select>
                                        @else
                                            <input type="{{ $setting->type === 'integer' ? 'number' : 'text' }}" 
                                                   class="form-control" 
                                                   id="{{ $setting->key }}" 
                                                   name="settings[{{ $setting->key }}][value]" 
                                                   value="{{ $setting->value }}"
                                                   @if($setting->type === 'integer') min="1" @endif>
                                        @endif
                                        <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                        <div class="form-text text-xs text-slate-500">Key: {{ $setting->key }}</div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="button" class="btn btn-outline-secondary mr-2" onclick="resetForm()">Reset</button>
                                <button type="submit" class="btn btn-primary">
                                    <i data-lucide="save" class="w-4 h-4 mr-2"></i>Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal" id="successModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="successMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal" id="errorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="errorMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('systemSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const settings = {};
    
    // Convert form data to the expected format
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('settings[')) {
            const matches = key.match(/settings\[([^\]]+)\]\[([^\]]+)\]/);
            if (matches) {
                const settingKey = matches[1];
                const field = matches[2];
                
                if (!settings[settingKey]) {
                    settings[settingKey] = {};
                }
                settings[settingKey][field] = value;
            }
        }
    }
    
    // Convert to array format
    const settingsArray = Object.values(settings);
    
    fetch('{{ route("system-settings.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            settings: settingsArray
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('successMessage').textContent = data.message;
            new bootstrap.Modal(document.getElementById('successModal')).show();
        } else {
            document.getElementById('errorMessage').textContent = data.message;
            new bootstrap.Modal(document.getElementById('errorModal')).show();
        }
    })
    .catch(error => {
        document.getElementById('errorMessage').textContent = 'An error occurred while saving settings.';
        new bootstrap.Modal(document.getElementById('errorModal')).show();
    });
});

function resetForm() {
    if (confirm('Are you sure you want to reset all settings to their current values?')) {
        location.reload();
    }
}
</script>
@endpush 