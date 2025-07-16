<?php

namespace App\Http\Requests\Master\Operational\Item;

use App\Models\InitialInventoryBalance;
use App\Models\ItemCategory;
use App\Models\Master\Common\UnitType;
use App\Models\Transaction;
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
        $ifHasTransaction = $this->ifHasTransaction($request);

        return [
            'name' => [
                'required',
                'string',
                Rule::unique('item_collections', 'name')
                    ->ignore($request->route('itemCollection')),
                $this->getRulesForCategory1($request),
                $this->getRulesForCategory3($request),
                $ifHasTransaction
            ],
            'code' => [Rule::requiredIf($request->must_have_code === 'on' && !$request->is_code_listed)],
            'unit_type_id' => ['required', 'string'],
            'tangible_assets_type' => [
                Rule::requiredIf($request->type === 'ASET'),
            ],
            'category_id' => [Rule::requiredIf($request->tangible_assets_type === 'Bukan Bangunan' && !$request->is_vehicle)],
            'building_type' => [
                Rule::requiredIf($request->tangible_assets_type === 'Bangunan' && !$request->is_land),
            ],
            'reorder_level' => [
                Rule::requiredIf(
                    $request->tangible_assets_type === 'Bukan Bangunan' && !$request->is_vehicle
                )
            ],
            'asset_account_id' => [
                Rule::requiredIf($request->type === "ASET" && $request->tangible_assets_type !== 'Tanah' && !$request->is_vehicle)
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
            'type.required' => 'Tipe barang tidak boleh kosong',
            'type.in' => 'Tipe barang tidak valid',
            'reorder_level' => 'Reorder level tidak boleh kosong',
            'building_type.required' => 'Tipe bangunan tidak boleh kosong',
            'asset_account_id.required' => 'Akun aset tidak boleh kosong jika tipe yang dipilih aset',
            'tangible_assets_type.in' => 'Kelompok harta berwujud  tidak valid',
            'building_type.in' => 'Tipe bangunan tidak valid',
        ];
    }


    private static function getRulesForCategory1(Request $request): Closure
    {
        return static function ($attribute, $value, $fail) use ($request) {
            $unitType = UnitType::find($request->unit_type_id);
            $category = ItemCategory::find($request->category_id);


            return null;
        };
    }


    private static function getRulesForCategory3(Request $request): Closure
    {
        return static function ($attribute, $value, $fail) use ($request) {
            $category = ItemCategory::find($request->category_id);

            if ($category?->name === 'Kategori 3' && $request?->type !== 'ASET') {
                return $fail('Kategori 3 harus bertipe ASET');
            }

            return null;
        };
    }


    public function ifHasTransaction(Request $request): Closure
    {
        return static function ($attribute, $value, $fail) use ($request) {
            $transaction = Transaction::where('item_id', $request->route('itemCollection')?->id)
                ->where('status', 'Diterima')
                ->first();

            if ($transaction) {
                return $fail('Item ini sudah memiliki transaksi, tidak bisa di ubah!');
            }

            return null;
        };
    }
}
