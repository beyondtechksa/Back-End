<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScrapeProductsCompany implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $products;

    /**
     * Create a new job instance.
     */
    public function __construct(array $products)
    {
        $this->products = $products;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $chunkSize = 50; 
        $chunks = array_chunk($this->products, $chunkSize);
        foreach ($chunks as $chunk) {
            try {
                DB::table('temp_products')->insert($chunk);
            } catch (\Exception $e) {
                Log::error('Error inserting chunk: ' . $e->getMessage());
            }
        }
        DB::statement("UPDATE temp_products SET created_at = NOW(), updated_at = NOW()");
    }
}
