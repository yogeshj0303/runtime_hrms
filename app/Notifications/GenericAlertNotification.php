<?php

namespace App\Notifications;

use App\Models\Alert;
use App\Models\AlertRule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GenericAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $alert;
    public $rule;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Alert $alert, AlertRule $rule)
    {
        $this->alert = $alert;
        $this->rule = $rule;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // By default send to database, check rule channels for email
        $channels = ['database'];
        if (is_array($this->rule->channels) && in_array('email', $this->rule->channels)) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->rule->name . ' - Alert Notification')
                    ->greeting('Hello ' . ($notifiable->name ?? 'User') . ',')
                    ->line($this->alert->message)
                    ->action('View Alerts', url('/alerts/dashboard'))
                    ->line('Thank you for using our HRMS!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'alert_id' => $this->alert->id,
            'rule_name' => $this->rule->name,
            'message' => $this->alert->message,
            'employee_id' => $this->alert->employee_id ?? null,
            'reference_type' => $this->alert->reference_type,
            'reference_id' => $this->alert->reference_id,
        ];
    }
}
