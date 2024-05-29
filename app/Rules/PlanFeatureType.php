<?php

namespace App\Rules;

use App\Models\Plan;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PlanFeatureType implements ValidationRule
{
    public function __construct(protected array $feature)
    {

    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->feature['type'] === 'number') {
            if (!is_numeric($value)) {
                $fail('The ' . str($this->feature['title'])->lower()->value() . ' must be a number.');
            }
        } elseif ($this->feature['type'] === 'boolean') {
            $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            if (!is_bool($value)) {
                $fail('The ' . str($this->feature['title'])->lower()->value() . ' must be a boolean.');
            }
        }else{
            $fail('The ' . str($this->feature['title'])->lower()->value() . ' invalid type.');
        }
    }
}
