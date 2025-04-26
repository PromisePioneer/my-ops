<?php

namespace App\Http\Requests\Master\Operational\Item;

use App\Models\ItemCategory;
use App\Models\Master\Common\UnitType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemCollectionRequest extends FormRequest
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

        $category = ItemCategory::where('id', $request->category_id)->first();

        return [
            'name' => [
                'required',
                'string',
                Rule::unique('item_collections', 'name')
                    ->ignore($request->route('itemCollection')),
                $this->getRulesForCategory1($request),
                $this->getRulesForCategory3($request)
            ],
            'code' => [Rule::requiredIf($request->must_have_code === 'on' && !$request->is_code_listed)],
            'unit_type_id' => ['required', 'string'],
            'category_id' => ['required', 'string'],
            'material' => [
                'required',
                Rule::in(['Besi', 'Non Besi'])
            ],
            'asset_account_id' => [
                Rule::requiredIf($request->type === "ASET")
            ],
            'type' => ['required', Rule::in('ASET', 'JUAL')],
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'Nama barang tidak boleh kosong',
            'name.unique' => 'Nama barang sudah terdaftar',
            'unit_type_id.required' => 'Tipe satuan tidak boleh kosong',
            'category_id.required' => 'Kategori tidak boleh kosong',
            'category_id.exists' => 'Kategori tidak ditemukan',
            'unit_type_id.exists' => 'Tipe satuan tidak ditemukan',
            'asset_account_id.required' => 'Akun aset tidak boleh kosong jika tipe yang dipilih aset',
        ];
    }


    private static function getRulesForCategory1(Request $request): Closure
    {
        return static function ($attribute, $value, $fail) use ($request) {
            $unitType = UnitType::find($request->unit_type_id);
            $category = ItemCategory::find($request->category_id);

            if ($category->name === 'Kategori 1' && $unitType->name !== 'Meter') {
                return $fail('Tipe satuan harus Meter jika kategori yang dipilih Kategori 1');
            }

            return null;
        };
    }


    private static function getRulesForCategory3(Request $request): Closure
    {
        return static function ($attribute, $value, $fail) use ($request) {
            $category = ItemCategory::find($request->category_id);

            if ($category->name === 'Kategori 3' && $request->type !== 'ASET') {
                return $fail('Kategori 3 harus bertipe ASET');
            }

            return null;
        };
    }
}
