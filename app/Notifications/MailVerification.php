<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class MailVerification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $verificationCode,
        public ?string $verificationUrl = null
    ) {}

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
        if (!empty($this->verificationUrl)) {
            $this->verificationUrl = $this->manageVerificationUrl($this->verificationUrl, $this->verificationCode);
        }
        
        return (new MailMessage)
            ->line('Please verify you email addres, here is your verification code')
            ->line("## $this->verificationCode")
            ->when($this->verificationUrl !== null, function ($message) {
                $message->action('Confirm mail', "$this->verificationUrl");
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
            //
        ];
    }

    private function manageVerificationUrl(string $verificationUrl, string $verificationCode)
    {
        if (Str::is('*:code*', $verificationUrl)) {
            return strtr($verificationUrl, [
                ':code' => $verificationCode
            ]);
        } 

        return "$verificationUrl/$verificationCode";
    }
}
