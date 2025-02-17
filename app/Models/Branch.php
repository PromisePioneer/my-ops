<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Branch extends Model
{
    use HasFactory, LogsActivity, Searchable;

    protected $table = 'branches';

    protected $fillable = [
        'name',
        'code',
        'address',
        'parent_id',
    ];


    //relations
    public function accountTransaction(): HasMany
    {
        return $this->hasMany(AccountTransaction::class, 'branch_id');
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
        ];
    }


    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }


    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }


    //aggregates
    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::whereNull('parent_id')->when(!empty($search), function ($query) use ($search) {
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


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
        // Chain fluent methods for configuration options
    }
}
