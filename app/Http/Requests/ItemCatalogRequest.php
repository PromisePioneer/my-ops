<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemCatalogRequest extends FormRequest
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
    public function rules(): array
    {
        $validateDraftStock = $this->ifDraftStockQtyLessThanOrEqualThan0();
        return [
            'code' => ['required', $validateDraftStock],
            'condition' => ['required', Rule::in('Rusak', 'Baik', 'Diperbaiki')]
        ];
    }


    public function messages(): array
    {
        return [
            'code.required' => 'Kode / SN Barang tidak boleh kosong',
            'code.unique' => 'Kode / SN Barang sudah terdaftar',
            'condition.required' => 'Kondisi Barang tidak boleh kosong',
            'condition.in' => 'Kondisi Barang tidak valid',
        ];
    }


    public function ifDraftStockQtyLessThanOrEqualThan0(): Closure
    {

        $draftStock = $this->route('draftStock') ?? null;

        return static function ($attribute, $value, $fail) use ($draftStock) {

            if ($draftStock?->qty <= 0 && !empty($draftStock)) {
                return $fail('Semua barang telah diberikan kode.');
            }
            return null;
        };
    }
}
