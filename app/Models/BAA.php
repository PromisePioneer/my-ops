<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;

class BAA extends Model
{
    protected $table = 'baa';
    protected $fillable = [
        'fab_id',
        'baa_number',
        'date',
        'work_location',
        'status'
    ];


    public function fab(): BelongsTo
    {
        return $this->belongsTo(Fab::class, 'fab_id');
    }


    public function spk(): HasOne
    {
        return $this->hasOne(SPK::class, 'baa_id');
    }

    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::with('fab')->orderby('baa_number', 'asc');
        if ($search !== '') {
            $query->where('baa_number', 'like', '%' . $search . '%')
                ->where('baa_number', 'like', '%' . $search . '%');
        }
        $baa = $query->get();

        return $baa->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->baa_number . ' - ' . $item->fab->po->contact->company_name,
            ];
        })->toArray();
    }

    public function getSelectedData(int $baaId): array
    {
        $baa = self::with('fab')->where('id', $baaId)->first();

        return [
            'id' => $baa->id,
            'name' => $baa->baa_number . ' - ' . $baa->fab->po->contact->company_name,
        ];
    }

}
