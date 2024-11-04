<?php

namespace App\Http\Requests\Transaction\Fab;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FabRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'service_category_id' => ['required', Rule::exists('services_categories', 'id')],
            'subscription_period' => ['required', Rule::in(['1 Tahun', '2 Tahun', 'Sesuai Kontrak'])],
            'contact_id' => ['required', Rule::exists('contacts', 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'Tanggal harus diisi.',
            'date.date' => 'Tanggal tidak valid.',
            'service_category_id.required' => 'Kategori Layanan harus diisi.',
            'service_category_id.exists' => 'Kategori Layanan tidak valid.',
            'subscription_period.required' => 'Jangka waktu berlangganan tidak boleh kosong',
        ];
    }
}
