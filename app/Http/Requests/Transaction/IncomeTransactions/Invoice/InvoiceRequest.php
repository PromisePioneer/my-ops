<?php

namespace App\Http\Requests\Transaction\IncomeTransactions\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceRequest extends FormRequest
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

            'contact_id' => ['required', Rule::exists('contacts', 'id')],
            'due_date' => ['required', 'date', 'after:today'],
            'account_id' => ['required', Rule::exists('accounts', 'id')],
            'description' => ['nullable'],
            'baa_file' => [
                'mimes:pdf',
                'max:2048',
                Rule::requiredIf(static function () {
                    return request()->route('invoice') === null;
                }),
            ],
            'cooperative_contract_file' => [
                'mimes:pdf',
                'max:2048',
                Rule::requiredIf(static function () {
                    return request()->route('invoice') === null;
                }),
            ],
            'data.*.description' => ['required', 'string'],
            'data.*.qty' => ['required', 'numeric'],
            'data.*.unit_price' => ['required', 'numeric'],
            'data.*.total_price' => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'account_id.required' => 'Akun harus dipilih',
            'account_id.exists' => 'Akun tidak valid',
            'invoice_number.unique' => 'No. Invoice sudah terdaftar',
            'due_date.required' => 'Tgl. Jatuh Tempo tidak boleh kosong.',
            'due_date.date' => 'Tgl. Jatuh Tempo harus berupa tanggal.',
            'due_date.after' => 'Tgl. Jatuh Tempo harus setelah hari ini',
            'baa_file.required' => 'File BAA tidak boleh kosong.',
            'baa_file.mimes' => 'File BAA harus berupa PDF',
            'baa_file.max' => 'File BAA harus berukuran maksimal 2MB',
            'cooperative_contract_file.required' => 'File Kontrak Kerjasama tidak boleh kosong.',
            'cooperative_contract_file.mimes' => 'File Kontrak Kerjasama  harus berupa PDF',
            'cooperative_contract_file.max' => 'File Kontrak Kerjasama  harus berukuran maksimal 2MB',
            'data.*.description.required' => 'Deskripsi tidak boleh kosong.',
            'data.*.qty.required' => 'Jumlah tidak boleh kosong.',
            'data.*.qty.numeric' => 'Jumlah harus berupa angka.',
            'data.*.unit_price.required' => 'Harga satuan tidak boleh kosong.',
            'data.*.unit_price.numeric' => 'Harga satuan harus berupa angka.',
            'data.*.total_price.required' => 'Total tidak boleh kosong.',
            'data.*.total_price.numeric' => 'Total harus berupa angka.',
        ];
    }
}
