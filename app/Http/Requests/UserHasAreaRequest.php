<?php

namespace App\Http\Requests;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserHasAreaRequest extends FormRequest
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
            'user_id' => [
                'required', 'integer', Rule::exists('users', 'id'),
                $this->uniqueEngineerRole($request)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'karyawan tidak boleh kosong',
            'user_id.integer' => 'karyawan harus berupa integer',
            'user_id.exists' => 'karyawan tidak valid',
        ];
    }

    public function uniqueEngineerRole(Request $request): Closure
    {
        return function ($attribute, $value, $fail) use ($request) {

            dd($this->getAssociatedUser($request)->hasRole('Head Engineer'));

            if ($this->getAssociatedUser($request)?->hasRole('Head Engineer')) {
                return $fail('KCA sudah ada');
            };
            return true;
        };
    }

    public function getAssociatedUser($request)
    {
        return User::with('roles')->whereHas('userHasArea', function ($query) use ($request) {
            $query->where('area_id', $request->route('area')->id);
        })->first();
    }
}
