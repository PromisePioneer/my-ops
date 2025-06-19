<?php

namespace App\Http\Requests;

use App\Models\Master\Common\Branch;
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
        $ifMutationItemNotExists = $this->ifMutationItemNotExists($request);
        $ifMutationOnSameBranch = $this->ifMutationOnSameBranch($request);
        return [
            'from_branch' => ['required', Rule::exists('branches', 'id')],
            'to_branch' => ['required', Rule::exists('branches', 'id')],
            'item_category_id' => ['required', Rule::exists('item_categories', 'id'), $ifMutationItemNotExists],
            'description' => ['required', $ifMutationOnSameBranch],
            'receiver_id' => ['required', Rule::exists('users', 'id')],
        ];
    }


    public function messages(): array
    {
        return [
            'from_branch.required' => 'Cabang asal tidak boleh kosong',
            'to_branch.required' => 'Cabang tujuan tidak boleh kosong',
            'description.required' => 'Keterangan tidak boleh kosong',
            'from_branch.exists' => 'Cabang asal tidak valid',
            'to_branch.exists' => 'Cabang tujuan tidak valid',
            'item_category_id.required' => 'Cabang kategori tidak boleh kosong',
            'item_category_id.exists' => 'Cabang kategori tidak valid',
            'receiver_id.required' => 'Penerima tidak boleh kosong',
            'receiver_id.exists' => 'Penerima tidak valid',
        ];
    }

    private function ifMutationItemNotExists(Request $request): Closure
    {
        return function ($attribute, $value, $fail) use ($request) {
            if (empty($request->session()->get('stock_mutation_items'))) {
                $fail('Barang masih kosong, silahkan isi barang terlebih dahulu');
            }
        };
    }


    private function ifMutationOnSameBranch(Request $request): Closure
    {
        return function ($attribute, $value, $fail) use ($request) {
            $fromBranch = Branch::with('parent')->find($request->from_branch);
            $toBranch = Branch::with('parent')->find($request->to_branch);
            if ($fromBranch->id == $toBranch->id) {
                $fail('tidak bisa mutasi ke sub cabang yg sama silahkan pilih sub cabang / cabang yang berbeda');
            }
        };
    }
}
