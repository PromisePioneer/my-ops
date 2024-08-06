<?php

namespace App\Http\Rule;

use App\Models\UserJobInformation;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;

class LeavesAndPermissionRules implements ValidationRule
{
    public function passes(string $attribute, mixed $value, Closure $fail, Request $request): void
    {

    }

    public function message()
    {
        // TODO: Implement message() method.
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // TODO: Implement validate() method.
    }
}
