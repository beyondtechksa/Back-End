<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{





    public function collection()
    {
        $checked=session('checked');
        session()->forget('checked');
        return Product::with('category', 'brand', 'collection', 'admin')
        ->whereIn('id',$checked)->get();
    }

    public function map($product): array
    {
        return [
        // $product->sizes_ids, // Convert this to a readable format if necessary
        $product->price,
        $product->discount_percentage,
        $product->discount_price,
        $product->sale_price,
        $product->name_tr,
        $product->name_en,
        $product->name_ar,
        $product->sku,
        $product->model_number,
        substr($product->desc_tr, 40),
        $product->desc_en,
        $product->desc_ar,
        $product->admin->name ?? '',
        $product->quantity,
        $product->discount_percentage_selling_price,
        $product->packaging_shipping_fees,
        $product->management_fees,
        $product->profit_percentage,
        $product->tax_percentage,
        $product->commercial_percentage,
        $product->manual_price_adjustment,
        $product->final_price,
        $product->final_selling_price,
        exchange_price($product->final_selling_price, 'USD'), // Convert to USD
        exchange_price($product->final_selling_price, 'SAR'), // Convert to SAR
        $product->category->translated_name ?? '',
        $product->brand->translated_name ?? '',
        $product->collection->translated_name ?? '',
        $product->company_name,
        $product->company_product_id,
        $product->featured ? 'yes' : 'no',
        $product->related ? 'yes' : 'no',
        $product->most_sold ? 'yes' : 'no',
        $product->trending ? 'yes' : 'no',
        $product->highlighted ? 'yes' : 'no',
        $product->new_arrival ? 'yes' : 'no',
        $product->ontop ? 'yes' : 'no',
        $product->status ? __('active') : __('not active'),
        $product->created_at->format('Y-m-d H:i:s'),
        $product->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            // __('sizes'),
            __('original price'),
            __('source discount percentage'),
            __('source discount price'),
            __('sale price'),
            'product original name',
            'product name (English)',
            'product name (العربية)',
            'Product ID (SKU)',
            'Product Model Number',
            'product original desc',
            'product desc (English)',
            'product desc (العربية)',
            __('username'),
            __('quantity'),
            __('discount percentage selling price'),
            __('packaging and shipping fees'),
            __('management fees'),
            __('profit percentage'),
            __('tax percentage'),
            __('commercial percentage'),
            __('manual price adjustment'),
            __('final price'),
            __('final selling price (TRY)'),
            __('final selling price (USD)'),
            __('final selling price (SAR)'),
            __('category'),
            __('brand'),
            __('collection'),
            'company name',
            'company product id',
            'featured',
            'related',
            'most sold',
            'trending',
            'highlighted',
            'new arrival',
            'on top',
            __('status'),
            'created date',
            'edited date',
        ];
    }
}
