<?php
// app/Notifications/NewGrievanceNotification.php

namespace App\Notifications;

use App\Models\Grievance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewGrievanceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $grievance;

    public function __construct(Grievance $grievance)
    {
        $this->grievance = $grievance;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Grievance Submitted - ' . $this->grievance->reference_number)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new grievance has been submitted to the portal.')
            ->line('Reference Number: ' . $this->grievance->reference_number)
            ->line('Submitted At: ' . $this->grievance->submitted_at->format('d M Y, h:i A'))
            ->line('Description: ' . \Str::limit($this->grievance->description, 100))
            ->action('View Grievance', route('admin.grievances.show', $this->grievance->id))
            ->line('Please review and take necessary action.');
    }
}