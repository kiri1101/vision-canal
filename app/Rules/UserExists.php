<?php

namespace App\Rules;

use App\Http\Traits\Helpers;
use Closure;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class UserExists implements ValidationRule
{
    use Helpers;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phone = $this->removeSpaceBetweenStringChar($value['tel']);

        $isUserWithPhone = User::userWithPhone($phone)->exists();

        if (!$isUserWithPhone) {
            $fail('No user with phone in system');
        }
    }
}
