<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'branch_id' => [Rule::exists('branches', 'id')],
            'date' => ['required', 'date'],
            'detail' => ['required'],
            'qty' => ['required', 'numeric'],
            'unit_type_id' => ['required', Rule::exists('unit_types', 'id')],
            'unit_price' => ['required', 'numeric'],
            'debit_account_id' => ['required', Rule::exists('accounts', 'id')],
            'credit_account_id' => ['required', Rule::exists('accounts', 'id')],
        ];
    }

}
