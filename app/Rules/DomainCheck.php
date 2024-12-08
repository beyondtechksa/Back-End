<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class DomainCheck implements Rule
{
   

    protected $allowedDomains;

    public function __construct(array $allowedDomains)
    {
        $this->allowedDomains = $allowedDomains;
    }

    public function passes($attribte,$value){       
        if (is_null($value)) {
            return true;
        }

        $url = parse_url($value, PHP_URL_HOST);

        return $url && in_array($url, $this->allowedDomains);
    }

    public function message()
    {
        return 'The :attribute domain is not allowed.';
    }

}
