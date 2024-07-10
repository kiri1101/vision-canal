<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Account implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $accountExist = collect(config('constants.account'))->contains(function (array $account, int $key) use ($value) {
            return $account['id'] === $value['id'];
        });

        if (!$accountExist) {
            $fail('The :attribute value is incorrect');
        }
    }
}
