<?php
namespace App\Facades\Contracts;
use Illuminate\Support\Facades\Log;


interface CompanyDriverInterface
{

    /**
     * Execute the driver logic.
     *
     */
    public function extract($category);

}
