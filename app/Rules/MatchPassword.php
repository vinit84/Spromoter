<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Translation\PotentiallyTranslatedString;

class MatchPassword implements ValidationRule
{
    public function __construct(public $hashedPassword = null)
    {

    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the password matches the current password
        if (! Hash::check($value, $this->hashedPassword ?? Auth::user()->password)) {
            $fail(trans('Password does not match'));
        }
    }
}
