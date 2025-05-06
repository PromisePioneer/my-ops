<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laravel\Scout\Searchable;

class Supplier extends Model
{
    use Searchable, HasFactory;

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


    public function toSearchableArray()
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'province' => $this->province,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'fax' => $this->fax,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'bank_account_number' => $this->bank_account_number,
            'bank_account_name' => $this->bank_account_name,
            'bank_name' => $this->bank_name,
            'npwp' => $this->npwp,
            'description' => $this->description,
            'tax_type' => $this->tax_type,
        ];
    }


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
