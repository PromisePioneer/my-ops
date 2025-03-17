<?php

namespace App\Http\Requests;

use App\Models\Stock;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsedStockRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(Request $request): array
    {
        return [
            'branch_id' => [
                Rule::requiredIf($request->user()->branch_id === null),
                Rule::exists('branches', 'id'),

            ],
            'debit_account_id' => ['required', Rule::exists('accounts', 'id')],
            'credit_account_id' => ['required', Rule::exists('accounts', 'id')],
            'qty' => ['required', 'numeric', $this->isStockExists($request)]
        ];
    }


    public function messages(): array
    {
        return [
            'branch_id.required' => 'Cabang tidak boleh kosong',
            'branch_id.exists' => 'Cabang tidak ditemukan',
            'debit_account_id.required' => 'Akun debit tidak boleh kosong',
            'debit_account_id.exists' => 'Akun debit tidak ditemukan',
            'credit_account_id.required' => 'Akun kredit tidak boleh kosong',
            'credit_account_id.exists' => 'Akun kredit tidak ditemukan'
        ];
    }


    public function isStockExists(Request $request): Closure
    {
        return static function ($attribute, $value, $fail) use ($request) {
            $stock = Stock::where('branch_id', $request->branch_id)->where('item_id', $request->goods_id)->first();

            if ($stock->qty < $request->qty) {
                return $fail('Stok barang tidak mencukupi');
            }

            return null;
        };
    }
}
