<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DateRangeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $dates = explode(' - ', $value);

        if (count($dates) !== 2) {
            $fail('The date range must be in the format of "YYYY/MM/DD-YYYY/MM/DD"');
        }

        foreach ($dates as $date) {
            if (! $this->validateDate($date)) {
                $fail('The date range must be in the format of "YYYY/MM/DD-YYYY/MM/DD"');
            }
        }
    }

    private function validateDate(string $date): bool
    {
        $date = explode('/', $date);

        if (count($date) !== 3) {
            return false;
        }

        if (! checkdate($date[1], $date[2], $date[0])) {
            return false;
        }

        return true;
    }
}
