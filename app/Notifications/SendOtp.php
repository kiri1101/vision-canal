<?php

namespace App\Notifications;

use Tzsk\Otp\Facades\Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;

class SendOtp extends Notification
{
    use Queueable;

    public $email;

    /**
     * Create a new notification instance.
     */
    public function __construct(String $email)
    {
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $otp = Otp::generate($this->email);

        $message = Lang::get('messages.miscellaneous.otp_message', [], 'fr') . ' ' . $otp;

        return (new MailMessage)
            ->greeting("Hello!")
            ->line($message);
    }
}
