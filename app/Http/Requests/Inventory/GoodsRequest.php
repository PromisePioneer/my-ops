<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GoodsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'unit_type_id' => [
                'required',
                Rule::exists('unit_types', 'id')
            ],
            'serial_number' => ['required',
                Rule::unique('goods', 'serial_number')->ignore(request()->route('goods'))],
            'qty' => ['required'],
            'name' => ['required'],
            'unit_price' => ['required'],
            'type' => [
                'required',
                Rule::in('aset', 'bukan aset')
            ],
            'file' => [
                'mimes:jpeg,jpg,png',
                Rule::requiredIf(function () {
                    return request()->route('goods') === null;
                })
            ]
        ];
    }


    public function messages(): array
    {
        return [
            'unit_type_id.required' => 'satuan tidak boleh kosong',
            'unit_type_id.exists' => 'satuan tidak valid.',
            'serial_number.required' => 'SN/Kode tidak boleh kosong',
            'serial_number.unique' => 'SN/Kode sudah terdaftar.',
            'qty.required' => 'qty tidak boleh kosong',
            'name.required' => 'nama tidak boleh kosong',
            'unit_price.required' => 'harga tidak boleh kosong',
            'total_price.required' => 'total tidak boleh kosong',
            'status.required' => 'status tidak boleh kosong',
            'status.in' => 'status tidak valid.',
            'type.required' => 'tipe barang tidak boleh kosong',
            'type.in' => 'tipe barang tidak valid.',
            'file.required' => 'file tidak boleh kosong',
        ];
    }
}
