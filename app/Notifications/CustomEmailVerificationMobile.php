<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomEmailVerificationMobile extends VerifyEmail
{
    public $verificationCode;

    public function __construct($verificationCode)
    {
        $this->verificationCode = $verificationCode;
    }

    /**
     * Get the notification's mail representation.
     *
     * @param mixed $notifiable
     */

    public function toMail($notifiable): MailMessage
    {

        return (new MailMessage)
            ->subject('Hello!')
            ->line('Please use the following code to verify your email address:')
            ->line('Verification Code: ' . $this->verificationCode)
            ->line('If you did not create an account, no further action is required.')
            ->salutation('Regards, Riya Store');
    }
}
