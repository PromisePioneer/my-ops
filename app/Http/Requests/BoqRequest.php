<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BoqRequest extends FormRequest
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
            'title' => ['required'],
            'date' => ['required', 'date', 'after:now'],
            'data.*.item_id' => ['required'],
            'data.*.qty' => ['required'],
            'data.*.unit_type_id' => ['required', Rule::exists('unit_types', 'id')],
            'data.*.unit_price' => ['required'],
            'data.*.used_estimation' => ['required', 'date'],
            'data.*.description' => ['required'],
            'projectTimeline.*.name' => ['required'],
            'projectTimeline.*.qty' => ['nullable'],
            'projectTimeline.*.unit_type_id' => ['nullable'],
            'projectTimeline.*.start_date' => ['required', 'date', 'after:date'],
            'projectTimeline.*.end_date' => ['required', 'date', 'after:start_date'],
            'projectTimeline.*.technician' => ['required'],
            'attachment' => [Rule::requiredIf($request->route('boq') === null), 'max:2048', 'mimes:pdf'],
        ];
    }


    public function messages()
    {
        return [
            'title.required' => 'Judul tidak boleh kosong',
            'date.required' => 'Tanggal tidak boleh kosong',
            'date.date' => 'Tanggal tidak valid.',
            'data.*.item_id.required' => 'Nama barang tidak boleh kosong',
            'data.*.qty.required' => 'Kuantitas barang tidak boleh kosong',
            'data.*.unit_type_id.required' => 'UOM barang tidak boleh kosong',
            'data.*.unit_price.required' => 'Harga satuan tidak boleh kosong',
            'data.*.used_estimation.required' => 'Estimasi barang dipakai tidak boleh kosong',
            'data.*.used_estimation.date' => 'Estimasi barang dipakai harus berupa tanggal',
            'data.*.description.required' => 'Deskripsi barang tidak boleh kosong',
            'projectTimeline.*.name.required' => 'Nama rencana waktu pekerjaan tidak boleh kosong',
            'projectTimeline.*.start_date.required' => 'Tanggal awal waktu pekerjaan tidak boleh kosong',
            'projectTimeline.*.start_date.date' => 'Tanggal awal waktu pekerjaan tidak valid',
            'projectTimeline.*.end_date.required' => 'Tanggal akhir waktu pekerjaan tidak boleh kosong',
            'projectTimeline.*.end_date.date' => 'Tanggal akhir waktu pekerjaan tidak valid',
            'projectTimeline.*.technician' => ['required'],
            'attachment' => ['required', 'max:2048', 'mimes:pdf'],
        ];
    }
}
