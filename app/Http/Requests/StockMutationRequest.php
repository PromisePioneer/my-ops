<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StockMutationRequest extends FormRequest
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
            'from_branch' => ['required', $this->ifNotSelectAnyItemOption($request)],
            'itemWithCodeFields' => [Rule::requiredIf($request->has('item_with_code_option')), 'array'],
            'to_branch' => ['required'],
        ];
    }


    public function messages(): array
    {
        return [
            'from_branch.required' => 'Cabang asal tidak boleh kosong',
            'to_branch.required' => 'Cabang tujuan tidak boleh kosong',
        ];
    }


    public function ifNotSelectAnyItemOption(Request $request): Closure
    {
        $itemWithCodeOption = $request->has('item_with_code_option');
        $itemWithoutCodeOption = $request->has('item_without_code_option');


        return static function ($attribute, $value, $fail) use ($itemWithCodeOption, $itemWithoutCodeOption) {
            if (!$itemWithCodeOption && !$itemWithoutCodeOption) {
                return $fail('Pilih salah satu opsi barang');
            }

            return null;
        };
    }
}
