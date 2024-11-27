<?php

namespace App\Services;
use App\Models\Currency;

class CurrencyService
{


    public function convertPrice($product,$amount)
    {
        $currency = Currency::where('id',$product->currency_id)->first()??Currency::where('prefix','TRY')->first();
        $toCurrencyPrefix=user_currency();
        $baseCurrency=$currency;
        $toCurrency = Currency::where('prefix', $toCurrencyPrefix)->first();
        if (!$toCurrency || $toCurrency->prefix == $baseCurrency->prefix) {
            return $amount;
        }
        $usd_amount = $amount / $baseCurrency->exchange_rate;
        return number_format($usd_amount * $toCurrency->exchange_rate,2);
    }


}
