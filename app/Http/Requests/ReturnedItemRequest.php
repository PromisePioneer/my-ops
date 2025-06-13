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
            'attachment' => ['required', 'image', 'max:2048'],
        ];
    }


    public function messages(): array
    {
        return [
            'remaining_qty.required' => 'Jumlah sisa tidak boleh kosong',
            'remaining_qty.numeric' => 'Jumlah sisa harus berupa angka',
            'remaining_qty.min' => 'Jumlah sisa harus lebih dari 0',
            'attachment.required' => 'File tidak boleh kosong',
            'attachment.image' => 'File harus berupa gambar',
            'attachment.max' => 'File tidak boleh lebih dari 2MB',
        ];
    }


    public static function maxRemainingQty(Request $request): \Closure
    {
        return static function ($attribute, $value, $fail) use ($request) {
            ItemCatalog::where('code', $request->route('stockWithdrawalItem')->code)->first()->qty < $value
                ? $fail('Jumlah sisa tidak boleh lebih besar dari jumlah barang')
                : null;
        };
    }
}
