<?php
namespace App\Notifications;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Support\Arrayable;

class EmailNotifiable implements Arrayable
{
    protected $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function routeNotificationForMail()
    {
        return $this->email;
    }

    public function toArray()
    {
        return [];
    }
}