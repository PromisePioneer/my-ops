<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';
    protected $fillable = [
        'code',
        'name',
        'address',
        'city',
        'province',
        'country',
        'postal_code',
        'fax',
        'email',
        'phone_number',
        'bank_account_number',
        'bank_account_name',
        'bank_name',
        'npwp',
        'description',
        'tax_type',
    ];


    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::orderby('name', 'asc');
        if ($search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $contact = $query->get(['id', 'name']);

        return $contact->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name
            ];
        })->toArray();
    }

    public function getSelectedData(int $supplierId): array
    {
        $supplier = self::where('id', $supplierId)->first();

        return [
            'id' => $supplier->id,
            'name' => $supplier->name,
        ];
    }
}
