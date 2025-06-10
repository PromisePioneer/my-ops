<?php

namespace App\Http\Requests;

use App\Models\ItemCatalog;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReturnedItemRequest extends FormRequest
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


        $maxRemainingQty = static::maxRemainingQty($request);
        return [
            'remaining_qty' => [
                Rule::requiredIf($request->status == 'Sisa'),
                'numeric',
                'min:0',
                $maxRemainingQty

            ],
//            'broken_qty' => [
//                Rule::requiredIf($request->item_condition === 'Rusak' && ItemCatalog::where('code', $request->route('stockWithdrawalItem')->code)->first()->qty_in_meter !== null)
//            ],
        ];
    }


    public static function maxRemainingQty(Request $request): \Closure
    {
        return static function ($attribute, $value, $fail) use ($request) {
            ItemCatalog::where('code', $request->route('stockWithdrawalItem')->code)->first()->qty_in_meter < $value
                ? $fail('Jumlah sisa tidak boleh lebih besar dari jumlah barang')
                : null;
        };
    }
}
