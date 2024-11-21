<?php

namespace App\Http\Requests\Transaction\Bast;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BastRequest extends FormRequest
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
     */
    public function rules(Request $request): array
    {
        return [
            'baa_id' => [
                'required',
                Rule::unique('bast', 'baa_id')->ignore($request->route('bast') === null),
            ],
            'date' => ['required', 'date'],
            'invoice_address' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'baa_id.required' => 'BAA tidak boleh kosong',
            'date.required' => 'Tanggal tidak boleh kosong',
            'date.date' => 'Tanggal tidak valid',
            'invoice_address.required' => 'Alamat Invoice tidak boleh kosong',
        ];
    }
}
