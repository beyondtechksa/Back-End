<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $file;
    /**
     * Create a new job instance.
     */
    public function __construct($file)
    {
        $this->file=$file;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Excel::import(new ProductsImport, $this->file);
    }
}
