<?php

namespace App\Http\Rule;

use App\Service\CalculateUserLeaves;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;

class LeavesAndPermissionRules implements ValidationRule
{
    public function __construct()
    {
        $this->calculateUserLeaves = new CalculateUserLeaves();
    }

    public function passes(string $attribute, mixed $value, Closure $fail, Request $request): bool
    {
        return $value < $this->calculateUserLeaves->calculate($request);
    }

    public function message(): string
    {
        return 'jatah cuti anda habis';
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // TODO: Implement validate() method.
    }
}
