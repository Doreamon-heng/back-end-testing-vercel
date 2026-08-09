<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\VonageMessage;

class OtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $code;
    protected $expiresAt;

    /**
     * @param string $code       the random OTP code to send
     * @param mixed  $expiresAt  Carbon instance / string, when the code expires
     */
    public function __construct(string $code, $expiresAt)
    {
        $this->code = $code;
        $this->expiresAt = $expiresAt;
    }

    /**
     * Choose the channel based on what the notifiable actually has.
     * Falls back to mail if both are present, so email is preferred by default.
     */
    public function via(object $notifiable): array
    {
        if (!empty($notifiable->email)) {
            return ['mail'];
        }

        if (!empty($notifiable->phone)) {
            return ['vonage']; // SMS channel — see setup notes below
        }

        return [];
    }

    /**
     * Email version of the OTP message.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your verification code')
            ->greeting('Hello ' . ($notifiable->name ?? '') . ',')
            ->line('Your one-time verification code is:')
            ->line('**' . $this->code . '**')
            ->line('This code expires at ' . $this->expiresAt . '.')
            ->line('If you did not request this code, you can safely ignore this email.');
    }

    /**
     * SMS version of the OTP message (requires the Vonage notification channel).
     */
    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage())
            ->content('Your verification code is ' . $this->code . '. It expires in a few minutes.');
    }

    /**
     * Stored representation if you also broadcast/store this notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'code'       => $this->code,
            'expires_at' => $this->expiresAt,
        ];
    }
}