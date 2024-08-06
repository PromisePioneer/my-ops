<?php

namespace App\Http\Requests\Transaction\Expenditure;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpenditureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'description' => ['required'],
            'amount' => ['required'],
            'debit_account_id' => ['required'],
            'credit_account_id' => ['required'],
            'file' => ['mimes:jpg,png,jpeg', 'max:2048', Rule::requiredIf(static function () {
                request()->route('expenditure') === null;
            })],
        ];
    }
}
