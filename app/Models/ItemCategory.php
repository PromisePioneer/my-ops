<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ItemCategory extends Model
{
    use HasFactory;

    protected $table = 'item_categories';
    protected $fillable = [
        'name',
    ];


    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::orderby('name', 'asc');
        if ($search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->where('name', 'like', '%' . $request->search . '%');
        }
        $contact = $query->get(['id', 'name']);

        return $contact->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        })->toArray();
    }

    public function getSelectedData(int $itemCategory): array
    {
        $contact = self::where('id', $itemCategory)->first();

        return [
            'id' => $contact->id,
            'name' => $contact->name,
        ];
    }
}
