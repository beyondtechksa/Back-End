<?php

namespace App\Jobs;

use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;

class ExportProductsToExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $admin;
    // public function maxAttempts()
    // {
    //     return 5; // or any number that suits your needs
    // }
    public $timeout = 1200; // 20 minutes
    public function __construct($admin)
    {
        $this->admin = $admin;
    }

    public function handle()
    {

        $totalProducts = Product::count();
        $batchSize = 10000; // Set batch size
        for ($i = 0; $i < $totalProducts; $i += $batchSize) {
            ExportProductsBatch::dispatch($this->admin, $i, $batchSize);
        }




    }
}
