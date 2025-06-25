<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ManageUserLeaveAndPermissionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'confirmation_status' => ['required', Rule::in('Diterima', 'Ditolak')],
            'start_date' => [
                Rule::requiredIf(function () use ($request) {
                    ($this->route('leaveAndPermission')->important_leaves === 'Mendapat Musibah' || $this->route('leaveAndPermission')->important_leaves === 'Memenuhi Panggilan Instansi Pemerintah') && ($request->confirmation_status === 'Diterima');
                }),
                'date'
            ],
            'end_date' => [
                'start_date' => [
                    Rule::requiredIf(function () use ($request) {
                        ($this->route('leaveAndPermission')->important_leaves === 'Mendapat Musibah' || $this->route('leaveAndPermission')->important_leaves === 'Memenuhi Panggilan Instansi Pemerintah') && ($request->confirmation_status === 'Diterima');
                    }),
                ],
                'date',
                'after_or_equal:start_date',
            ],
            'confirmation_reason' => ['required', 'string'],
        ];
    }


    public function messages(): array
    {
        return [
            'confirmation_status.required' => 'Status konfirmasi tidak boleh kosong',
            'confirmation_status.in' => 'Status konfirmasi tidak valid',
            'start_date.required' => 'Tanggal Awal tidak boleh kosong',
            'start_date.date' => 'Tanggal Awal tidak valid',
            'end_date.required' => 'Tanggal akhir tidak boleh kosong',
            'end_date.date' => 'Tanggal akhir tidak valid',
            'end_date.after_or_equal' => 'Tanggal akhir harus sama atau setelah tanggal awal',
            'confirmation_reason.required' => 'Alasan tidak boleh kosong',
            'confirmation_reason.string' => 'Alasan tidak valid',
        ];
    }
}
