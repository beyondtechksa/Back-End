<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
class OrderNotifications extends Notification
{
    use Queueable;

    private $text;
    private $link;
    private $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($text,$link,User $user)
    {
        $this->text = $text;
        $this->link = $link;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
//    public function toMail(object $notifiable): MailMessage
//    {
//        return (new MailMessage)
//            ->subject('this is new order')
//            ->line('check there is new order');
//    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user'=>$this->user,
            'text'=>$this->text,
            'link'=>$this->link,
        ];
    }
}
