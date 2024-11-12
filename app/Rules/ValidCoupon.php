<?php

namespace App\Rules;

use App\Models\Coupon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCoupon implements ValidationRule
{
    protected $coupon;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $this->coupon = Coupon::where('status', 1)
            ->where('code', $value)
            ->whereDate('valid_untill', '>=', now())
            ->first();

        if (!$this->coupon) {
            $fail('The coupon code is invalid or expired.');
        }
    }

    public function getCoupon()
    {
        return $this->coupon;
    }
}
