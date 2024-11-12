<?php
namespace App\Services;

class SkuGenerator
{
    public static function generateUniqueSku($prefix = 'BY-')
    {
        do {
            $sku = $prefix . mt_rand(10000000, 99999999);
        } while (\App\Models\Product::where('sku', $sku)->exists());

        return $sku;
    }
}
