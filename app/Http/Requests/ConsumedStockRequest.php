<?php

namespace App\Http\Requests;

use App\Models\Stock;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConsumedStockRequest extends FormRequest
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
            'destination_branch_id' => [
                Rule::requiredIf($request->type === 'Mutasi'),
            ],
            'qty' => ['required', 'numeric', $this->isStockExists($request)]
        ];
    }


    public function messages(): array
    {
        return [
            'branch_id.required' => 'Cabang tidak boleh kosong',
            'branch_id.exists' => 'Cabang tidak ditemukan',
            'destination_branch_id.required' => 'Cabang tujuan tidak boleh kosong',
        ];
    }


    public function isStockExists(Request $request): Closure
    {
        return static function ($attribute, $value, $fail) use ($request) {
            $branchId = $request->user()->branch_id ?? $request->branch_id;
            $stock = Stock::where('branch_id', $branchId)->where('item_id', $request->goods_id)->first();

            if (empty($stock) || $stock?->qty < $request?->qty) {
                return $fail('Stok barang tidak mencukupi');
            }

            return null;
        };
    }
}
