<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Scholar Limits
            [
                'key' => 'max_scholars_per_institution',
                'value' => 50,
                'type' => 'integer',
                'category' => 'limits',
                'description' => 'Maximum number of active scholars per institution'
            ],
            [
                'key' => 'max_scholars_per_barangay',
                'value' => 20,
                'type' => 'integer',
                'category' => 'limits',
                'description' => 'Maximum number of active scholars per barangay'
            ],
            [
                'key' => 'max_scholars_per_course',
                'value' => 15,
                'type' => 'integer',
                'category' => 'limits',
                'description' => 'Maximum number of active scholars per course'
            ],

            // Disbursement Settings
            [
                'key' => 'disbursement_amount_per_semester',
                'value' => 15000,
                'type' => 'integer',
                'category' => 'disbursement',
                'description' => 'Standard disbursement amount per semester in pesos'
            ],
            [
                'key' => 'disbursement_schedule',
                'value' => json_encode(['First Week of September', 'First Week of February']),
                'type' => 'json',
                'category' => 'disbursement',
                'description' => 'Standard disbursement schedule'
            ],
            [
                'key' => 'max_disbursement_per_year',
                'value' => 30000,
                'type' => 'integer',
                'category' => 'disbursement',
                'description' => 'Maximum disbursement amount per year in pesos'
            ],
            [
                'key' => 'max_disbursement_amount',
                'value' => 50000,
                'type' => 'integer',
                'category' => 'disbursement',
                'description' => 'Maximum disbursement amount per transaction in pesos'
            ],

            // Email Notification Settings
            [
                'key' => 'email_notifications_enabled',
                'value' => true,
                'type' => 'boolean',
                'category' => 'notifications',
                'description' => 'Enable or disable email notifications'
            ],
            [
                'key' => 'notification_email_from',
                'value' => 'scholarship@example.com',
                'type' => 'string',
                'category' => 'notifications',
                'description' => 'From email address for notifications'
            ],
            [
                'key' => 'notification_email_name',
                'value' => 'Scholarship System',
                'type' => 'string',
                'category' => 'notifications',
                'description' => 'From name for email notifications'
            ],
            [
                'key' => 'renewal_reminder_days',
                'value' => 30,
                'type' => 'integer',
                'category' => 'notifications',
                'description' => 'Days before deadline to send renewal reminders'
            ],

            // Retention Requirements
            [
                'key' => 'minimum_gwa_for_retention',
                'value' => 2.5,
                'type' => 'decimal',
                'category' => 'retention',
                'description' => 'Minimum Grade Weighted Average required for retention'
            ],
            [
                'key' => 'minimum_units_per_semester',
                'value' => 12,
                'type' => 'integer',
                'category' => 'retention',
                'description' => 'Minimum units required per semester'
            ],
            [
                'key' => 'maximum_failed_subjects',
                'value' => 2,
                'type' => 'integer',
                'category' => 'retention',
                'description' => 'Maximum number of failed subjects allowed per semester'
            ],

            // Renewal Criteria
            [
                'key' => 'renewal_deadline_days',
                'value' => 30,
                'type' => 'integer',
                'category' => 'renewal',
                'description' => 'Number of days before semester start to submit renewal'
            ],
            [
                'key' => 'required_documents_for_renewal',
                'value' => json_encode(['Grades', 'Enrollment Form', 'Certificate of Good Moral']),
                'type' => 'json',
                'category' => 'renewal',
                'description' => 'Required documents for renewal application'
            ],
            [
                'key' => 'renewal_approval_required',
                'value' => true,
                'type' => 'boolean',
                'category' => 'renewal',
                'description' => 'Whether renewal requires admin approval'
            ],

            // General Settings
            [
                'key' => 'system_maintenance_mode',
                'value' => false,
                'type' => 'boolean',
                'category' => 'general',
                'description' => 'Enable/disable system maintenance mode'
            ],
            [
                'key' => 'notification_email_enabled',
                'value' => true,
                'type' => 'boolean',
                'category' => 'general',
                'description' => 'Enable/disable email notifications'
            ]
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
