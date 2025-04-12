<?php

namespace Modules\Plans\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TiersHasInf implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $keys = explode('.', $attribute);
        $is_valid = false;
        foreach (request()->prices[$keys[1]]['tiers'] as $key => $tier) {
            if($tier['up_to'] == 'inf') {
                $is_valid = true;
                break;
            }
        }
        if (!$is_valid) {
            $fail("The {$attribute} is invalid.");
        }
    }
}
