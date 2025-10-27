<?php

namespace App\Notifications;

use App\Models\Requirement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $requirement;

    public function __construct(Requirement $requirement)
    {
        $this->requirement = $requirement;
    }

    public function via($notifiable)
    {
        $emailEnabled = \App\Models\SystemSetting::getValue('email_notifications_enabled', true);
        
        return $emailEnabled ? ['database', 'mail'] : ['database'];
    }

    public function toMail($notifiable)
    {
        $status = ucfirst($this->requirement->status);
        $documentName = $this->requirement->name;
        $application = $this->requirement->application;

        $message = match($this->requirement->status) {
            'approved' => "Your document '{$documentName}' has been approved.",
            'rejected' => "Your document '{$documentName}' has been rejected and requires attention.",
            default => "There has been an update to your document '{$documentName}'."
        };

        $mailMessage = (new MailMessage)
            ->subject("Document {$status} - Application #{$application->application_number}")
            ->greeting("Hello {$notifiable->first_name},")
            ->line($message)
            ->line("Application Number: {$application->application_number}")
            ->line("Document: {$documentName}");

        if ($this->requirement->remarks) {
            $mailMessage->line("Remarks: {$this->requirement->remarks}");
        }

        if ($this->requirement->status === 'rejected') {
            $mailMessage->line('Please review the remarks above and resubmit the document if necessary.')
                ->line('You can upload a new version of this document through your application portal.');
        } elseif ($this->requirement->status === 'approved') {
            $mailMessage->line('This document has been verified and approved.')
                ->line('No further action is required for this document.');
        }

        $mailMessage->action('View Application Status', route('applications.status', $application->id))
            ->line('Thank you for your cooperation.');

        return $mailMessage;
    }

    public function toArray($notifiable)
    {
        return [
            'requirement_id' => $this->requirement->id,
            'application_id' => $this->requirement->application_id,
            'status' => $this->requirement->status,
            'title' => 'Document ' . ucfirst($this->requirement->status),
            'message' => match($this->requirement->status) {
                'approved' => "Document '{$this->requirement->name}' has been approved! ✅",
                'rejected' => "Document '{$this->requirement->name}' was rejected. ⚠️",
                default => "Document '{$this->requirement->name}' status has been updated."
            },
            'document_name' => $this->requirement->name,
            'application_number' => $this->requirement->application->application_number,
            'remarks' => $this->requirement->remarks,
        ];
    }
} 