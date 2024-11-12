<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Vendor;
class VendorNotification extends Notification
{
    use Queueable;
    public $vendor;
    public $text;
    public $link;

    /**
     * Create a new notification instance.
     */
    public function __construct(Vendor $vendor,$text,$link)
    {
        $this->vendor=$vendor;
        $this->text=$text;
        $this->link=$link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line($this->vendor->name)
                    ->line($this->text)
                    ->action('view',$this->link)
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
            'user'=>$this->vendor,
            'text'=>$this->text,
            'link'=>$this->link,
        ];
    }
}
