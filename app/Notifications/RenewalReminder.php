<?php

namespace App\Notifications;

use App\Models\Scholar;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RenewalReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $scholar;
    protected $deadline;
    protected $semester;
    protected $schoolYear;

    public function __construct(Scholar $scholar, $deadline, $semester, $schoolYear)
    {
        $this->scholar = $scholar;
        $this->deadline = $deadline;
        $this->semester = $semester;
        $this->schoolYear = $schoolYear;
    }

    public function via($notifiable)
    {
        $emailEnabled = \App\Models\SystemSetting::getValue('email_notifications_enabled', true);
        
        return $emailEnabled ? ['database', 'mail'] : ['database'];
    }

    public function toMail($notifiable)
    {
        $deadlineDays = $this->deadline->diffInDays(now());
        $deadlineText = $deadlineDays === 1 ? 'tomorrow' : "in {$deadlineDays} days";

        $mailMessage = (new MailMessage)
            ->subject("Renewal Reminder - {$this->semester} {$this->schoolYear}")
            ->greeting("Hello {$notifiable->first_name},")
            ->line("This is a friendly reminder that your scholarship renewal for {$this->semester} {$this->schoolYear} is due {$deadlineText}.")
            ->line("Renewal Deadline: {$this->deadline->format('M d, Y')}")
            ->line("Current GWA Requirement: " . \App\Models\SystemSetting::getValue('minimum_gwa_for_retention', 2.5))
            ->line("Minimum Units Required: " . \App\Models\SystemSetting::getValue('minimum_units_per_semester', 12));

        if ($deadlineDays <= 7) {
            $mailMessage->line('⚠️ URGENT: Your renewal deadline is approaching! Please submit your renewal application as soon as possible.');
        } elseif ($deadlineDays <= 14) {
            $mailMessage->line('Please ensure you have all required documents ready for submission.');
        }

        $mailMessage->line('Required documents for renewal:')
            ->line('• Updated grades/transcript')
            ->line('• Certificate of enrollment')
            ->line('• Certificate of good moral character')
            ->line('• Other requirements as specified');

        $mailMessage->action('Submit Renewal Application', route('renewals.create'))
            ->line('If you have any questions, please contact our office immediately.')
            ->line('Thank you for your continued participation in our scholarship program.');

        return $mailMessage;
    }

    public function toArray($notifiable)
    {
        $deadlineDays = $this->deadline->diffInDays(now());
        
        return [
            'scholar_id' => $this->scholar->id,
            'type' => 'renewal_reminder',
            'title' => 'Renewal Reminder',
            'message' => $deadlineDays <= 7 
                ? "URGENT: Your renewal deadline is in {$deadlineDays} days! ⚠️"
                : "Renewal reminder: Deadline in {$deadlineDays} days",
            'deadline' => $this->deadline->format('Y-m-d'),
            'semester' => $this->semester,
            'school_year' => $this->schoolYear,
            'days_remaining' => $deadlineDays,
            'is_urgent' => $deadlineDays <= 7,
        ];
    }
} 