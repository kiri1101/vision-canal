<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\PaymentMethod as Method;

class PaymentMethod implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $methodExist = Method::searchByUUID($value)->exists();

        if (!$methodExist) {
            $fail('The :attribute value is invalid');
        }
    }
}
