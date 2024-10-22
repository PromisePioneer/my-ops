<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoqTimelineProject extends Model
{
    use HasFactory;

    protected $table = 'boq_timeline_project';
    protected $fillable = [
        'boq_id',
        'name',
        'qty',
        'unit_type_id',
        'start_date',
        'end_date',
        'pic',
        'technician',
    ];


    public function boq(): BelongsTo
    {
        return $this->belongsTo(Boq::class, 'boq_id');
    }


    public function picName(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic', 'id');
    }


    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }
}
