<?php

namespace App\Models\Master\Common;

use App\Models\OfferingLetter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Laravel\Scout\Searchable;

class Contact extends Model
{
    use HasFactory, Searchable;

    protected $table = 'contacts';

    protected $fillable = [
        'name',
        'code',
        'position',
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
        'type',
        'tax_type'
    ];

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
        ];
    }


    public function offeringLetter(): HasOne
    {
        return $this->HasOne(OfferingLetter::class);
    }
}
