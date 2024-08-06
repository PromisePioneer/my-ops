<?php

namespace App\Http\Requests\JournalAdjustment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdjustmentRequest extends FormRequest
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
            'payment_date' => ['required', 'date'],
            'initial_journal_id' => [
                'required',
                Rule::unique('initial_journal', 'description'),
            ],
            'total_payment_per_month' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_date.required' => 'tanggal tidak boleh kosong.',
            'payment_date.date' => 'tanggal tidak valid.',
            'initial_journal_id.required' => 'jurnal awal tidak boleh kosong.',
            'total_payment_per_month.required' => 'total payment periode tidak boleh kosong.',
        ];
    }
}
