<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\ScholarController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\InstitutionController;


// Public Routes
Route::get('/', function () {
    return view('auth.login');
});

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    // Dashboard
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user}', [ProfileController::class, 'showOther'])->name('view.profile.other');

    // Scholarship Application Routes
    Route::get('/apply', [ApplicationController::class, 'create'])->name('scholarship.apply');
    Route::post('/apply', [ApplicationController::class, 'store'])->name('scholarship.store');
    Route::get('/application/status', [ApplicationController::class, 'status'])->name('scholarship.status');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    // Super Admin Routes
    Route::post('/accounts/{id}/promote', [AccountController::class, 'promoteToAdmin'])->name('accounts.promote');
    Route::post('/accounts/{id}/demote', [AccountController::class, 'demoteToApplicant'])->name('accounts.demote');
    Route::post('/accounts/{id}/promote-super', [AccountController::class, 'promoteToSuperAdmin'])->name('accounts.promote-super');
    
    // Super Admin Dashboard & Stats
    Route::get('/super-admin', [SuperAdminController::class, 'dashboard'])->name('super-admin.dashboard');
    Route::get('/super-admin/analytics', [SuperAdminController::class, 'analyticsOverview'])->name('super-admin.analytics');
    Route::get('/super-admin/scholar-stats', [SuperAdminController::class, 'scholarStats'])->name('super-admin.scholar-stats');
    Route::get('/super-admin/disbursement-stats', [SuperAdminController::class, 'disbursementStats'])->name('super-admin.disbursement-stats');
    Route::get('/super-admin/retention-stats', [SuperAdminController::class, 'retentionStats'])->name('super-admin.retention-stats');

    // Super Admin Report Generation Routes
    Route::get('/super-admin/reports/active-scholars', [SuperAdminController::class, 'generateActiveScholarsReport'])->name('super-admin.reports.active-scholars');
    Route::get('/super-admin/reports/disbursement', [SuperAdminController::class, 'generateDisbursementReport'])->name('super-admin.reports.disbursement');
    Route::get('/super-admin/reports/retention', [SuperAdminController::class, 'generateRetentionReport'])->name('super-admin.reports.retention');
    Route::get('/super-admin/reports/comprehensive', [SuperAdminController::class, 'generateComprehensiveReport'])->name('super-admin.reports.comprehensive');
    
    // Super Admin Reports Page
    Route::get('/super-admin/reports', [\App\Http\Controllers\SuperAdminController::class, 'reports'])->name('super-admin.reports');

    // System Settings Routes (Super Admin Only)
    Route::get('/system-settings', [SystemSettingsController::class, 'index'])->name('system-settings.index');
    Route::post('/system-settings/update', [SystemSettingsController::class, 'update'])->name('system-settings.update');
    Route::get('/system-settings/{category}', [SystemSettingsController::class, 'getByCategory'])->name('system-settings.category');
    Route::get('/system-settings/value/{key}', [SystemSettingsController::class, 'getValue'])->name('system-settings.value');
    Route::post('/system-settings/test-email', [SystemSettingsController::class, 'testEmail'])->name('system-settings.test-email');

    // Institution Management Routes (Super Admin Only)
    Route::resource('institutions', InstitutionController::class);
    Route::get('/institutions-dropdown', [InstitutionController::class, 'getInstitutions'])->name('institutions.dropdown');
    
    // Temporary test route
    Route::get('/test-institution/{id}', function($id) {
        $institution = \App\Models\Institution::find($id);
        return response()->json($institution);
    });
    
    // Test route for institution data
    Route::get('/test-institution-data/{id}', function($id) {
        $institution = \App\Models\Institution::find($id);
        if (!$institution) {
            return response()->json(['error' => 'Institution not found'], 404);
        }
        
        $data = [
            'id' => $institution->id,
            'name' => $institution->name,
            'type' => $institution->type,
            'address' => $institution->address,
            'contact_person' => $institution->contact_person,
            'contact_email' => $institution->contact_email,
            'contact_phone' => $institution->contact_phone,
            'description' => $institution->description,
            'is_active' => $institution->is_active
        ];
        
        return response()->json($data);
    });

    // Scholar Management Routes
    Route::get('/scholars', [ScholarController::class, 'index'])->name('scholars');
    Route::patch('/scholars/{scholar}/drop', [ScholarController::class, 'drop'])->name('scholars.drop');
    Route::get('/scholarship-management', [\App\Http\Controllers\ScholarshipProgramController::class, 'index'])->name('scholarship.management');
    Route::post('/scholarship-management', [\App\Http\Controllers\ScholarshipProgramController::class, 'store'])->name('scholarship.management.store');
    Route::put('/scholarship-management/{id}', [\App\Http\Controllers\ScholarshipProgramController::class, 'update'])->name('scholarship.management.update');
    Route::delete('/scholarship-management/{id}', [\App\Http\Controllers\ScholarshipProgramController::class, 'destroy'])->name('scholarship.management.destroy');

    // Disbursement Management Routes
    Route::get('/disbursements', [\App\Http\Controllers\DisbursementController::class, 'index'])->name('disbursements.index');
    Route::get('/disbursements/create', [\App\Http\Controllers\DisbursementController::class, 'create'])->name('disbursements.create');
    Route::post('/disbursements/batches', [\App\Http\Controllers\DisbursementController::class, 'storeBatch'])->name('disbursements.batches.store');
    Route::get('/disbursements/scholars-by-program', [\App\Http\Controllers\DisbursementController::class, 'getScholarsByProgram'])->name('disbursements.scholars-by-program');
    Route::get('/disbursements/allocation-logs', [\App\Http\Controllers\DisbursementController::class, 'allocationLogs'])->name('disbursements.allocation-logs');
    Route::get('/disbursements/{id}', [\App\Http\Controllers\DisbursementController::class, 'show'])->name('disbursements.show');
    Route::post('/disbursements/{id}/mark-received', [\App\Http\Controllers\DisbursementController::class, 'markAsReceived'])->name('disbursements.mark-received');
    Route::post('/disbursements/bulk-action', [\App\Http\Controllers\DisbursementController::class, 'bulkAction'])->name('disbursements.bulk-action');

    // Scholar Disbursement Routes
    Route::get('/my-disbursements', [\App\Http\Controllers\DisbursementController::class, 'scholarDisbursements'])->name('disbursements.scholar');
    Route::get('/my-disbursements/{id}', [\App\Http\Controllers\DisbursementController::class, 'scholarDisbursementShow'])->name('disbursements.scholar.show');

    // Budget Requests Routes
    Route::resource('budget-requests', \App\Http\Controllers\BudgetRequestController::class);

    // Scholar Release Management Routes
    Route::get('/scholar-release', [\App\Http\Controllers\ScholarReleaseController::class, 'index'])->name('scholar-release.index');
    Route::get('/scholar-release/{id}', [\App\Http\Controllers\ScholarReleaseController::class, 'show'])->name('scholar-release.show');
    Route::post('/scholar-release/{id}/release', [\App\Http\Controllers\ScholarReleaseController::class, 'release'])->name('scholar-release.release');
    Route::post('/scholar-release/{id}/unrelease', [\App\Http\Controllers\ScholarReleaseController::class, 'unrelease'])->name('scholar-release.unrelease');
    Route::post('/scholar-release/bulk-release', [\App\Http\Controllers\ScholarReleaseController::class, 'bulkRelease'])->name('scholar-release.bulk-release');
    Route::get('/scholar-release/unreleased', [\App\Http\Controllers\ScholarReleaseController::class, 'getUnreleased'])->name('scholar-release.unreleased');

    // Renewal Management Routes
    Route::get('/renewals', [\App\Http\Controllers\RenewalController::class, 'index'])->name('renewals.index');
    Route::get('/renewals/create', [\App\Http\Controllers\RenewalController::class, 'create'])->name('renewals.create');
    Route::post('/renewals', [\App\Http\Controllers\RenewalController::class, 'store'])->name('renewals.store');
    Route::get('/renewals/{renewal}', [\App\Http\Controllers\RenewalController::class, 'show'])->name('renewals.show');
    Route::post('/renewals/{renewal}/review', [\App\Http\Controllers\RenewalController::class, 'review'])->name('renewals.review');
    Route::get('/renewals/status', [\App\Http\Controllers\RenewalController::class, 'status'])->name('renewals.status');
    Route::get('/renewals/documents/{document}/download', [\App\Http\Controllers\RenewalController::class, 'downloadDocument'])->name('renewals.download');
    // Barangay Management Routes
    Route::resource('barangays', BarangayController::class)->except(['show']);
    Route::patch('barangays/{barangay}/toggle-funded', [BarangayController::class, 'toggleFunded'])->name('barangays.toggleFunded');
    Route::get('/barangay-data', [BarangayController::class, 'index'])->name('barangay.data');

    // Residence Data Management Routes
    Route::get('/residence-data', [\App\Http\Controllers\ResidenceDataController::class, 'index'])->name('residence-data.index');
    Route::get('/residence-data/{id}', [\App\Http\Controllers\ResidenceDataController::class, 'show'])->name('residence-data.show');
    Route::get('/residence-data/{id}/create-account', [\App\Http\Controllers\ResidenceDataController::class, 'createAccount'])->name('residence-data.create-account');
    Route::get('/create-account/{id}', \App\Livewire\CreateAccount::class)->name('create-account');
    Route::get('/create-account-demographic/{demographicId}', \App\Livewire\CreateAccount::class)->name('create-account-demographic');

    // Ekalinga Requests Integration
    Route::get('/ekalinga/requests', [\App\Http\Controllers\Ekalinga\RequestIntegrationController::class, 'index'])->name('ekalinga.requests.index');
    Route::post('/ekalinga/requests/{id}/approve', [\App\Http\Controllers\Ekalinga\RequestIntegrationController::class, 'approve'])->name('ekalinga.requests.approve');
    Route::post('/ekalinga/requests/{id}/reject', [\App\Http\Controllers\Ekalinga\RequestIntegrationController::class, 'reject'])->name('ekalinga.requests.reject');
    Route::post('/ekalinga/requests/{id}/disburse', [\App\Http\Controllers\Ekalinga\RequestIntegrationController::class, 'disburse'])->name('ekalinga.requests.disburse');

    // Profile Routes
    Route::get('/profiles/{id}', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profiles.show');
    Route::get('/profiles/search', [\App\Http\Controllers\ProfileController::class, 'search'])->name('profiles.search');

    // Account Management Routes
    Route::resource('accounts', AccountController::class);

    // Application Routes
    // Route::get('/applicants', function () {
    //     return view('dashboardcontent.applicants');
    // })->name('applicants');

    // Application Management Routes (for applicants to manage their own applications)
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/create', [ApplicationController::class, 'create'])->name('applications.create');
        Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
        Route::get('/applications/{id}/status', [ApplicationController::class, 'status'])->name('applications.status');
        Route::get('/applications/requirements/{id}/download', [ApplicationController::class, 'downloadRequirement'])->name('applications.download-requirement');
        
        // Enhanced Document Review Routes
        Route::get('/applications/{application}/requirements', [\App\Http\Controllers\RequirementController::class, 'index'])->name('requirements.index');
        Route::get('/requirements/{id}', [\App\Http\Controllers\RequirementController::class, 'show'])->name('requirements.show');
        Route::post('/requirements/{id}/approve', [\App\Http\Controllers\RequirementController::class, 'approve'])->name('requirements.approve');
        Route::post('/requirements/{id}/reject', [\App\Http\Controllers\RequirementController::class, 'reject'])->name('requirements.reject');
        Route::get('/requirements/{id}/download', [\App\Http\Controllers\RequirementController::class, 'download'])->name('requirements.download');
        Route::get('/requirements/{id}/preview', [\App\Http\Controllers\RequirementController::class, 'preview'])->name('requirements.preview');
        Route::post('/requirements/bulk-action', [\App\Http\Controllers\RequirementController::class, 'bulkAction'])->name('requirements.bulk-action');

        // Scholar Performance Tracking Routes
        Route::get('/scholar-performance', [\App\Http\Controllers\ScholarPerformanceController::class, 'index'])->name('scholar-performance.index');
        Route::get('/scholar-performance/create', [\App\Http\Controllers\ScholarPerformanceController::class, 'create'])->name('scholar-performance.create');
        Route::post('/scholar-performance', [\App\Http\Controllers\ScholarPerformanceController::class, 'store'])->name('scholar-performance.store');
        Route::get('/scholar-performance/{id}', [\App\Http\Controllers\ScholarPerformanceController::class, 'show'])->name('scholar-performance.show');
        Route::get('/scholar-performance/{id}/edit', [\App\Http\Controllers\ScholarPerformanceController::class, 'edit'])->name('scholar-performance.edit');
        Route::put('/scholar-performance/{id}', [\App\Http\Controllers\ScholarPerformanceController::class, 'update'])->name('scholar-performance.update');
        Route::delete('/scholar-performance/{id}', [\App\Http\Controllers\ScholarPerformanceController::class, 'destroy'])->name('scholar-performance.destroy');
        Route::get('/scholar-performance/analytics', [\App\Http\Controllers\ScholarPerformanceController::class, 'analytics'])->name('scholar-performance.analytics');
    });

    // Admin Application Management Routes (no email verification required)
    Route::middleware(['auth'])->group(function () {
        // Applicants review (admin/superadmin)
        Route::get('/applicants', [ApplicationController::class, 'applicants'])->name('applications.applicants');
        Route::post('/applications/{id}/approve', [ApplicationController::class, 'approve'])->name('applications.approve');
        Route::post('/applications/{id}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');
        Route::post('/applications/{id}/request-additional', [\App\Http\Controllers\ApplicationController::class, 'requestAdditionalRequirements'])->name('applications.request-additional');
    });

    // Action History
    Route::get('/action-history', function () {
        return view('dashboardcontent.action-history');
    })->name('action.history');
});

// Notification Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
});

// Authentication Routes
require __DIR__.'/auth.php';

// Scholar Feedback (Applicant)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/feedback', [\App\Http\Controllers\ScholarFeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/feedback/create', [\App\Http\Controllers\ScholarFeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [\App\Http\Controllers\ScholarFeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback/{feedback}', [\App\Http\Controllers\ScholarFeedbackController::class, 'show'])->name('feedback.show');
});

// Admin Feedback Review
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/feedback', [\App\Http\Controllers\ScholarFeedbackController::class, 'adminIndex'])->name('feedback.admin.index');
    Route::get('/admin/feedback/{feedback}', [\App\Http\Controllers\ScholarFeedbackController::class, 'adminShow'])->name('feedback.admin.show');
    Route::post('/admin/feedback/{feedback}/update-status', [\App\Http\Controllers\ScholarFeedbackController::class, 'updateStatus'])->name('feedback.admin.update-status');
    Route::get('/admin/feedback/analytics', [\App\Http\Controllers\ScholarFeedbackController::class, 'analytics'])->name('feedback.admin.analytics');
});

Route::get('/admin/documents', [\App\Http\Controllers\RequirementController::class, 'adminDocuments'])->name('admin.documents');
Route::get('/admin/renewals/eligibility', [\App\Http\Controllers\RenewalController::class, 'adminEligibility'])->name('admin.renewals.eligibility');


// Test route for debugging approve/reject
Route::get('/test-application/{id}', function($id) {
    $application = \App\Models\Application::find($id);
    if (!$application) {
        return response()->json(['error' => 'Application not found']);
    }
    return response()->json([
        'id' => $application->id,
        'status' => $application->status,
        'user' => $application->user->first_name . ' ' . $application->user->last_name,
        'school' => $application->school,
        'course' => $application->course
    ]);
})->middleware('auth');
