<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Exports\CompanyRequestExport;
use Maatwebsite\Excel\Facades\Excel;

class CompanyEmail extends Notification
{
    use Queueable;
    public $company_name;

    /**
     * Create a new notification instance.
     */
    public function __construct($company_name)
    {
        $this->company_name=$company_name;
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
        $file_name='excel/companies_'.$this->company_name.rand(0,1000).'.xlsx';
        $filePath = storage_path('app/'.$file_name);

        // Export the file
        Excel::store(new CompanyRequestExport($this->company_name),$file_name , 'local');

        return (new MailMessage)
                    ->subject('Products Arrange')
                    ->line('Hello '.$this->company_name)
                    ->line('we kindly ask you for arranging these products for shipping')
                    ->line('we attach the products details on an excel file below')
                    ->action('Visit site', url('/'))
                    ->line('Thank you for using our application!')
                    ->attach($filePath, [
                        'as' => 'exported_file.xlsx',
                        'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ]);
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
}
