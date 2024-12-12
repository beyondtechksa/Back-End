<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Facades\Services\CompanyFacade;
use App\Http\Enums\CompanyEnums;
use App\Models\Vendor;


class CompanyTrack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:company-track';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $companies=Vendor::get();
        foreach($companies as $key=>$company){
            // if($key==0){
                if($company->name!='trendyol'){
                    // $company='bigdart';
                    \Log::info('trak '.$company->name);
                    $driver = CompanyFacade::driver($company->name);
                    $products = $driver->extract('test','track');
                }
            // }
        }
    }
}
