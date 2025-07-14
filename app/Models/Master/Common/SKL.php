<?php

namespace App\Models\Master\Common;

use App\Models\Fab;
use App\Models\FabHasSKL;
use App\Models\OfferingLetterSKL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Laravel\Scout\Searchable;

class SKL extends Model
{
    use Searchable, SoftDeletes;

    protected $table = 'skl';
    protected $fillable = [
        'name'
    ];


    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

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
