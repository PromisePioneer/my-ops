<?php

namespace App\Models\Master\Common;

use AllowDynamicProperties;
use App\Models\AccountTransaction;
use App\Models\BranchDefaultWorkTime;
use App\Models\Company;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[AllowDynamicProperties] class Branch extends Model
{
    use HasFactory, Searchable, LogsActivity;

    protected $table = 'branches';

    protected $fillable = [
        'company_id',
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


    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }


    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }


    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }


    public function stock(): HasMany
    {
        return $this->hasMany(Stock::class, 'branch_id');
    }


    public function defaultWorkTime(): HasOne
    {
        return $this->hasOne(BranchDefaultWorkTime::class, 'branch_id');
    }


    public function tapActivity(Activity $activity, $eventName): void
    {
        $properties = $activity->properties->toArray();

        $renameKeys = [
            'name' => 'Nama',
            'code' => 'Kode',
            'address' => 'Alamat',
            'parent_id' => 'Cabang Induk',
        ];


        if ($eventName == 'restored') {
            $eventName = 'Pulihkan Data';
        }


        if ($eventName === 'updated') {
            $eventName = 'Mengubah Data';
        }


        if ($eventName === 'created') {
            $eventName = 'Membuat Data';
        }


        if ($eventName === 'deleted') {
            $eventName = 'Hapus Data';
        }


        $transformKeys = function ($data) use ($renameKeys, $eventName) {
            return collect($data)
                ->mapWithKeys(function ($value, $key) use ($renameKeys) {
                    $newKey = $renameKeys[$key] ?? $key;
                    return [$newKey => $value];
                });
        };


        $activity->event = $eventName;
        $activity->description = "$eventName Cabang";
        $activity->properties = collect([
            'attributes' => isset($properties['attributes']) ? $transformKeys($properties['attributes']) : null,
            'old' => isset($properties['old']) ? $transformKeys($properties['old']) : null,
        ]);
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->logExcept(['password', 'profile_pic'])
            ->dontSubmitEmptyLogs();
    }

}
