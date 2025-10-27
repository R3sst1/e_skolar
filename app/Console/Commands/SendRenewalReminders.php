<?php

namespace App\Console\Commands;

use App\Models\Scholar;
use App\Models\SystemSetting;
use App\Notifications\RenewalReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendRenewalReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarship:send-renewal-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send renewal reminders to active scholars';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting renewal reminder process...');

        // Check if email notifications are enabled
        $emailEnabled = SystemSetting::getValue('email_notifications_enabled', true);
        if (!$emailEnabled) {
            $this->warn('Email notifications are disabled. Skipping reminder emails.');
            return 0;
        }

        // Get reminder days setting
        $reminderDays = SystemSetting::getValue('renewal_reminder_days', 30);
        
        // Calculate deadline dates for upcoming semesters
        $currentYear = Carbon::now()->year;
        $semesters = [
            'First' => Carbon::create($currentYear, 6, 1), // June 1
            'Second' => Carbon::create($currentYear, 11, 1), // November 1
        ];

        $remindersSent = 0;

        foreach ($semesters as $semester => $startDate) {
            $deadline = $startDate->copy()->subDays($reminderDays);
            $schoolYear = $currentYear . '-' . ($currentYear + 1);

            // Check if we're within the reminder window
            if (Carbon::now()->between($deadline->copy()->subDays(7), $deadline)) {
                $this->info("Processing reminders for {$semester} semester {$schoolYear}...");

                // Get active scholars who haven't submitted renewals for this semester
                $scholars = Scholar::with('user')
                    ->where('status', 'active')
                    ->whereDoesntHave('renewals', function ($query) use ($semester, $schoolYear) {
                        $query->where('semester', $semester)
                              ->where('school_year', $schoolYear);
                    })
                    ->get();

                foreach ($scholars as $scholar) {
                    try {
                        $scholar->user->notify(new RenewalReminder($scholar, $deadline, $semester, $schoolYear));
                        $remindersSent++;
                        $this->line("Sent reminder to {$scholar->user->first_name} {$scholar->user->last_name}");
                    } catch (\Exception $e) {
                        $this->error("Failed to send reminder to {$scholar->user->first_name} {$scholar->user->last_name}: {$e->getMessage()}");
                    }
                }
            }
        }

        $this->info("Renewal reminder process completed. Sent {$remindersSent} reminders.");
        return 0;
    }
}
