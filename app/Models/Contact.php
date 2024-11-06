<?php

namespace App\Models;

use Eloquent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = [
        'pic_name',
        'company_name',
        'company_code',
        'email',
        'phone_number',
        'identity_type',
        'identity_number',
        'fax',
        'npwp',
        'complete_address',
        'other_info',
    ];


    public function offeringLetter(): HasOne
    {
        return $this->HasOne(OfferingLetter::class);
    }

    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::orderby('pic_name', 'asc');
        if ($search !== '') {
            $query->where('pic_name', 'like', '%' . $request->search . '%')
                ->where('pic_name', 'like', '%' . $request->search . '%');
        }
        $contact = $query->get(['id', 'pic_name', 'company_name']);

        return $contact->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->pic_name . ' - ' . $item->company_name,
            ];
        })->toArray();
    }

    public function getSelectedData(int $contactId): array
    {
        $contact = self::where('id', $contactId)->first();

        return [
            'id' => $contact->id,
            'name' => $contact->pic_name . ' - ' . $contact->company_name,
        ];
    }
}
