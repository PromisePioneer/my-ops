<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $fillable = [
        'name',
        'code',
        'address',
    ];


    public function accountTransaction(): HasMany
    {
        return $this->hasMany(AccountTransaction::class, 'branch_id');
    }

    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::when(!empty($search), function ($query) use ($search) {
            $query->where('name', 'like', '%'.$search.'%');
        })->orderby('name')->select('id', 'name', 'code')->get();

        return $query->map(function ($c) {
            return [
                'id' => $c->id,
                'text' => $c->name,
            ];
        })->toArray();
    }


    public function getSelectedData(?int $branchId = null): ?array
    {
        $branch = self::where('id', $branchId)->first();

        if ($branchId === null) {
            return null;
        }

        return [
            'id' => $branch->id,
            'code' => $branch->code,
            'name' => $branch->name,
        ];
    }
}
