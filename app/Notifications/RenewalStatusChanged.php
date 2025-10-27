<?php

namespace App\Notifications;

use App\Models\ScholarRenewal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RenewalStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $renewal;

    public function __construct(ScholarRenewal $renewal)
    {
        $this->renewal = $renewal;
    }

    public function via($notifiable)
    {
        $emailEnabled = \App\Models\SystemSetting::getValue('email_notifications_enabled', true);
        
        return $emailEnabled ? ['database', 'mail'] : ['database'];
    }

    public function toMail($notifiable)
    {
        $status = ucfirst($this->renewal->status);
        $scholar = $this->renewal->scholar;
        $user = $scholar->user;

        $message = match($this->renewal->status) {
            'approved' => 'Congratulations! Your scholarship renewal has been approved.',
            'rejected' => 'We regret to inform you that your scholarship renewal has been rejected.',
            'under_review' => 'Your scholarship renewal is now under review.',
            default => 'There has been an update to your scholarship renewal.'
        };

        $mailMessage = (new MailMessage)
            ->subject("Renewal {$status} - {$this->renewal->semester} {$this->renewal->school_year}")
            ->greeting("Hello {$user->first_name},")
            ->line($message)
            ->line("Renewal Number: {$this->renewal->renewal_number}")
            ->line("Semester: {$this->renewal->semester}")
            ->line("School Year: {$this->renewal->school_year}")
            ->line("GWA: {$this->renewal->gwa}");

        if ($this->renewal->admin_remarks) {
            $mailMessage->line("Remarks: {$this->renewal->admin_remarks}");
        }

        if ($this->renewal->status === 'approved') {
            $mailMessage->line('Your scholarship will continue for this semester.')
                ->line('Please maintain your academic performance to remain eligible.');
        } elseif ($this->renewal->status === 'rejected') {
            $mailMessage->line('If you have any questions about this decision, please contact our office.')
                ->line('You may appeal this decision within 30 days.');
        } elseif ($this->renewal->status === 'under_review') {
            $mailMessage->line('Our team is currently reviewing your renewal application.')
                ->line('You will be notified once the review is complete.');
        }

        $mailMessage->action('View Renewal Status', route('renewals.status'))
            ->line('Thank you for your continued participation in our scholarship program.');

        return $mailMessage;
    }

    public function toArray($notifiable)
    {
        return [
            'renewal_id' => $this->renewal->id,
            'scholar_id' => $this->renewal->scholar_id,
            'status' => $this->renewal->status,
            'title' => 'Renewal ' . ucfirst($this->renewal->status),
            'message' => match($this->renewal->status) {
                'approved' => 'Your scholarship renewal has been approved! 🎉',
                'rejected' => 'Your scholarship renewal was not approved.',
                'under_review' => 'Your renewal is now being reviewed.',
                default => 'Your renewal status has been updated.'
            },
            'renewal_number' => $this->renewal->renewal_number,
            'semester' => $this->renewal->semester,
            'school_year' => $this->renewal->school_year,
            'gwa' => $this->renewal->gwa,
            'remarks' => $this->renewal->admin_remarks,
        ];
    }
} 