<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        // Check if email notifications are enabled in system settings
        $emailEnabled = \App\Models\SystemSetting::getValue('email_notifications_enabled', true);
        
        return $emailEnabled ? ['database', 'mail'] : ['database'];
    }

    public function toMail($notifiable)
    {
        $status = ucfirst($this->application->status);
        $message = match($this->application->status) {
            'approved' => 'Congratulations! Your scholarship application has been approved.',
            'rejected' => 'We regret to inform you that your scholarship application has been rejected.',
            'under_review' => 'Your scholarship application is now under review.',
            default => 'There has been an update to your scholarship application.'
        };

        $mailMessage = (new MailMessage)
            ->subject("Scholarship Application {$status}")
            ->greeting("Hello {$notifiable->first_name},")
            ->line($message)
            ->line("Application Number: {$this->application->application_number}")
            ->line("School: {$this->application->school}")
            ->line("Course: {$this->application->course}");

        if ($this->application->admin_remarks) {
            $mailMessage->line("Remarks: {$this->application->admin_remarks}");
        }

        if ($this->application->status === 'approved') {
            $mailMessage->line('You will receive further instructions regarding your scholarship benefits.')
                ->line('Please ensure all required documents are submitted for processing.');
        } elseif ($this->application->status === 'rejected') {
            $mailMessage->line('If you have any questions about this decision, please contact our office.')
                ->line('You may reapply in the next application period.');
        } elseif ($this->application->status === 'under_review') {
            $mailMessage->line('Our team is currently reviewing your application and supporting documents.')
                ->line('You will be notified once the review is complete.');
        }

        $mailMessage->action('View Application Status', route('applications.status', $this->application->id))
            ->line('Thank you for your interest in our scholarship program.');

        return $mailMessage;
    }

    public function toArray($notifiable)
    {
        return [
            'application_id' => $this->application->id,
            'status' => $this->application->status,
            'title' => 'Application ' . ucfirst($this->application->status),
            'message' => match($this->application->status) {
                'approved' => 'Your scholarship application has been approved! 🎉',
                'rejected' => 'Your scholarship application was not approved.',
                'under_review' => 'Your application is now being reviewed.',
                default => 'Your application status has been updated.'
            },
            'application_number' => $this->application->application_number,
            'school' => $this->application->school,
            'course' => $this->application->course,
        ];
    }
} 