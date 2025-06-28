<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StockWithdrawalRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {

        $ifWithdrawalItemNotExists = $this->ifWithdrawalItemNotExists($request);
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'category_id' => ['required', 'exists:item_categories,id', $ifWithdrawalItemNotExists],
            'user_id' => ['required'],
            'description' => ['required', 'string'],
//            'itemWithoutCodeFields' => ['required'],
        ];
    }


    public function messages(): array
    {
        return [
            'branch_id.required' => 'Cabang tidak boleh kosong',
            'branch_id.exists' => 'Cabang tidak valid',
            'description.required' => 'Deskripsi tidak boleh kosong',
            'category_id.required' => 'Kategori tidak boleh kosong',
            'category_id.exists' => 'Kategori tidak valid',
            'user_id.required' => 'User tidak boleh kosong',
            'itemWithCodeFields.required' => 'Barang tidak boleh kosong apabila memilih barang dengan kode',
        ];
    }


    public function ifWithdrawalItemNotExists(Request $request): Closure
    {
        return static function ($attribute, $value, $fail) use ($request) {
            if (empty($request->session()->get('stock_withdrawal_item'))) {
                $fail('Barang masih kosong, silahkan isi barang terlebih dahulu');
            }
        };
    }
}
