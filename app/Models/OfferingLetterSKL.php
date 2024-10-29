<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class OfferingLetterSKL extends Model
{
    protected $table = 'offering_letter_skl';
    protected $fillable = [
        'name'
    ];


    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::orderby('name', 'asc');
        if ($search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $offeringLetterSKL = $query->get(['id', 'name']);

        return $offeringLetterSKL->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        })->toArray();
    }

    public function getSelectedData(int $sklId): array
    {
        $skl = self::where('id', $sklId)->first();

        return [
            'id' => $skl->id,
            'name' => $skl->name,
        ];
    }
}
