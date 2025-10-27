@extends('layouts.app')
@section('title', 'Notification Settings')
@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Notification Settings</h2>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Email Notifications</h2>
            </div>
            <div class="p-5">
                <form method="POST" action="{{ route('system-settings.update') }}" id="notification-settings-form">
                    @csrf
                    <input type="hidden" name="category" value="notifications">
                    
                    <!-- Email Notifications Enabled -->
                    <div class="mt-4">
                        <label class="form-label">Enable Email Notifications</label>
                        <div class="mt-2">
                            <div class="form-check">
                                <input type="checkbox" name="settings[email_notifications_enabled]" 
                                       value="1" class="form-check-input" 
                                       {{ \App\Models\SystemSetting::getValue('email_notifications_enabled', true) ? 'checked' : '' }}>
                                <label class="form-check-label">Send email notifications for status changes</label>
                            </div>
                        </div>
                        <div class="text-slate-500 text-xs mt-1">
                            When enabled, users will receive email notifications for application status changes, document approvals, and other important updates.
                        </div>
                    </div>

                    <!-- From Email Address -->
                    <div class="mt-4">
                        <label class="form-label">From Email Address</label>
                        <input type="email" name="settings[notification_email_from]" 
                               class="form-control w-full" 
                               value="{{ \App\Models\SystemSetting::getValue('notification_email_from', 'scholarship@example.com') }}"
                               placeholder="scholarship@example.com">
                        <div class="text-slate-500 text-xs mt-1">
                            Email address that will appear as the sender for all notifications.
                        </div>
                    </div>

                    <!-- From Name -->
                    <div class="mt-4">
                        <label class="form-label">From Name</label>
                        <input type="text" name="settings[notification_email_name]" 
                               class="form-control w-full" 
                               value="{{ \App\Models\SystemSetting::getValue('notification_email_name', 'Scholarship System') }}"
                               placeholder="Scholarship System">
                        <div class="text-slate-500 text-xs mt-1">
                            Name that will appear as the sender for all notifications.
                        </div>
                    </div>

                    <!-- Renewal Reminder Days -->
                    <div class="mt-4">
                        <label class="form-label">Renewal Reminder Days</label>
                        <input type="number" name="settings[renewal_reminder_days]" 
                               class="form-control w-full" 
                               value="{{ \App\Models\SystemSetting::getValue('renewal_reminder_days', 30) }}"
                               min="1" max="90">
                        <div class="text-slate-500 text-xs mt-1">
                            Number of days before the renewal deadline to send reminder emails.
                        </div>
                    </div>

                    <!-- Notification Types -->
                    <div class="mt-6">
                        <h3 class="font-medium text-base mb-3">Notification Types</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 border rounded-md">
                                <div class="flex items-center mb-2">
                                    <i data-lucide="file-text" class="w-5 h-5 text-primary mr-2"></i>
                                    <span class="font-medium">Application Status</span>
                                </div>
                                <div class="text-slate-500 text-sm">
                                    Notify applicants when their application status changes (pending, under review, approved, rejected).
                                </div>
                            </div>
                            
                            <div class="p-4 border rounded-md">
                                <div class="flex items-center mb-2">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-success mr-2"></i>
                                    <span class="font-medium">Document Review</span>
                                </div>
                                <div class="text-slate-500 text-sm">
                                    Notify applicants when their documents are approved or rejected during review.
                                </div>
                            </div>
                            
                            <div class="p-4 border rounded-md">
                                <div class="flex items-center mb-2">
                                    <i data-lucide="refresh-cw" class="w-5 h-5 text-warning mr-2"></i>
                                    <span class="font-medium">Renewal Status</span>
                                </div>
                                <div class="text-slate-500 text-sm">
                                    Notify scholars when their renewal application status changes.
                                </div>
                            </div>
                            
                            <div class="p-4 border rounded-md">
                                <div class="flex items-center mb-2">
                                    <i data-lucide="credit-card" class="w-5 h-5 text-primary mr-2"></i>
                                    <span class="font-medium">Disbursement Status</span>
                                </div>
                                <div class="text-slate-500 text-sm">
                                    Notify scholars when their disbursement status changes (approved, released, received).
                                </div>
                            </div>
                            
                            <div class="p-4 border rounded-md">
                                <div class="flex items-center mb-2">
                                    <i data-lucide="clock" class="w-5 h-5 text-danger mr-2"></i>
                                    <span class="font-medium">Renewal Reminders</span>
                                </div>
                                <div class="text-slate-500 text-sm">
                                    Automatically send reminder emails to scholars before renewal deadlines.
                                </div>
                            </div>
                            
                            <div class="p-4 border rounded-md">
                                <div class="flex items-center mb-2">
                                    <i data-lucide="alert-circle" class="w-5 h-5 text-warning mr-2"></i>
                                    <span class="font-medium">System Alerts</span>
                                </div>
                                <div class="text-slate-500 text-sm">
                                    Send notifications for system maintenance, updates, and important announcements.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Test Email Section -->
                    <div class="mt-6 p-4 bg-slate-50 rounded-md">
                        <h3 class="font-medium text-base mb-3">Test Email Notifications</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Test Email Address</label>
                                <input type="email" id="test-email" class="form-control w-full" 
                                       placeholder="test@example.com">
                            </div>
                            <div class="flex items-end">
                                <button type="button" id="send-test-email" class="btn btn-outline-primary">
                                    <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                                    Send Test Email
                                </button>
                            </div>
                        </div>
                        <div class="text-slate-500 text-xs mt-2">
                            Send a test email to verify your email configuration is working correctly.
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="text-right mt-6">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Handle form submission
document.getElementById('notification-settings-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("system-settings.update") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Notification settings saved successfully!');
        } else {
            alert(data.error || 'Failed to save settings');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to save settings');
    });
});

// Handle test email
document.getElementById('send-test-email').addEventListener('click', function() {
    const testEmail = document.getElementById('test-email').value;
    
    if (!testEmail) {
        alert('Please enter a test email address');
        return;
    }
    
    this.disabled = true;
    this.innerHTML = '<i data-lucide="loader" class="w-4 h-4 mr-2 animate-spin"></i>Sending...';
    
    fetch('{{ route("system-settings.test-email") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email: testEmail })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Test email sent successfully!');
        } else {
            alert(data.error || 'Failed to send test email');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to send test email');
    })
    .finally(() => {
        this.disabled = false;
        this.innerHTML = '<i data-lucide="send" class="w-4 h-4 mr-2"></i>Send Test Email';
    });
});
</script>
@endpush
@endsection 