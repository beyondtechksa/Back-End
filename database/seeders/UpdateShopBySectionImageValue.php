<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Settings;

class UpdateShopBySectionImageValue extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting=Settings::findOrFail(6);
        $date=$setting->value;
        $updated_data=[];
        foreach($date as $single){
            $updated_data[]=[
                'name'=>$single['name'],
                'image'=>[
                    'en'=>$single['image'],'ar'=>$single['image']
                ]
            ];
        }
        $setting->update([
            'value'=>$updated_data
        ]);
    }
}
