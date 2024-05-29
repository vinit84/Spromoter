<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected $message, protected $url, protected $icon = 'ti ti-bell', protected string $type = 'success', protected bool $isMail = false, protected $mailBody = null)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if ($this->isMail) {
            return ['mail', 'database'];
        }else{
            return ['database'];
        }
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('You have a new notification!')
            ->greeting("Hello! {$notifiable->name}")
            ->when($this->mailBody, function (MailMessage $mail) {
                if (view()->exists($this->mailBody)) {
                    $mail->view($this->mailBody);
                }else{
                    $mail->line($this->mailBody);
                }
            })
            ->unless($this->mailBody, function (MailMessage $mail) {
                $mail->line($this->message);
            })
            ->when($this->url, function (MailMessage $mail) {
                $mail->action('Visit', $this->url);
            })
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'url' => $this->url,
            'icon' => $this->icon,
            'type' => $this->type,
        ];
    }
}
