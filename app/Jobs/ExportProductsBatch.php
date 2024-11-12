<?php

namespace App\Jobs;

use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminNotification;
use App\Models\Product;

class ExportProductsBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $admin;
    protected $offset;
    protected $batchSize;
    protected $fileName;

    public function __construct($admin, $offset, $batchSize, $fileName)
    {
        $this->admin = $admin;
        $this->offset = $offset;
        $this->batchSize = $batchSize;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $filePath = 'public/' . $this->fileName;

        // If it's the first batch, create the file; otherwise, append to it
        if ($this->offset == 0) {
            Excel::store(new ProductsExport($this->offset, $this->batchSize), $this->fileName, 'public');
        } else {
            Excel::append(new ProductsExport($this->offset, $this->batchSize), $filePath, 'public');
        }

        // After the last batch, send the notification with the download link
        if (Product::count() <= $this->offset + $this->batchSize) {
            $downloadLink = Storage::url($this->fileName);
            $text = 'The export is ready';

            Notification::send($this->admin, new AdminNotification($text, $downloadLink, $this->admin));
        }
    }
}
