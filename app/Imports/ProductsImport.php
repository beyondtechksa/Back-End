<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\TempProduct;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation
{
    /**
     * @param Collection $collection
     */
    public $imported_products = [];
    public function model(array $row)
    {
        $product=  new TempProduct([
            'name' => $row['name'],
            // 'name_ar' => $row['name_ar'],
            'desc' => $row['desc'],
            // 'desc_ar' => $row['desc_ar'],
            'price' => $row['price'],
//            'discount_price' => $row['discount_price'],
//            'discount_percentage' => $row['discount_percentage'],
//            'final_price' => $row['final_price'],
//            'link' => $row['link'],
            'images' =>[$row['images']] ,
            'type' => 'excel',
            'admin_id' => admin()->id,
            'sku' => 'BD-' . rand(100000, 999999),
            'discount_price'=>0,
            'discount_percentage'=>0,
            'final_price'=>$row['price'],
            'currency_id'=>mainCurrency()?mainCurrency()->id:null
        ]);
        $product->save();
        $this->imported_products[] = $product;
        return $product;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            // 'name_ar' => ['required', 'string', 'max:255'],
            'desc' => ['required'],
            // 'desc_ar' => ['required'],
            'price' => ['required', 'numeric']
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name' => 'Required English Name',
            // 'name_ar' => 'Required Arabic Name',
            'desc' => 'Required English Description',
            // 'desc_ar' => 'Required Arabic Description',
            'price' => 'Required Price',
        ];
    }
}
