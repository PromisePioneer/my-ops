<?php

namespace App\Models\Master\Common;

use App\Models\InitialInventoryBalance;
use App\Models\OfferingLetter;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Laravel\Scout\Searchable;

class Contact extends Model
{
    use Searchable, SoftDeletes;

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


    public function offeringLetter(): HasMany
    {
        return $this->hasMany(OfferingLetter::class, 'contact_id');
    }


    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class, 'contact_id');
    }


    public function initialInventoryBalance(): HasMany
    {
        return $this->hasMany(InitialInventoryBalance::class, 'contact_id');
    }

    public function ifHasRelatedData($query): bool
    {
        return $query->offeringLetter()->exists()
            || $query->transaction()->exists()
            || $query->initialInventoryBalance()->exists();
    }
}
