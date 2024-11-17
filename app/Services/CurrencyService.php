<?php

namespace App\Services;
use App\Models\Currency;

class CurrencyService
{


    public function convertPrice($product,$amount)
    {
        $toCurrencyPerfix=user_currency();
        $baseCurrency=$product->currency??Currency::where('prefix','TRY')->first();
        $toCurrency = Currency::where('prefix', $toCurrencyPerfix)->first();
        if (!$toCurrency || $toCurrency->prefix == $baseCurrency->prefix) {
            return $amount;
        }
        $usd_amount = $amount / $baseCurrency->exchange_rate;
        return number_format($usd_amount * $toCurrency->exchange_rate,2);
    }


}
