<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JointClosure extends Model
{
    use HasFactory;

    protected $table = 'joint_closures';

    protected $fillable = [
        'code_id',
        'region',
        'fo_cable_id',
        'lat',
        'long',
        'cut_off_date',
    ];


    public function code(): BelongsTo
    {
        return $this->belongsTo(JointClosureCode::class, 'code_id');
    }


    public function foCable(): BelongsTo
    {
        return $this->belongsTo(FOCable::class, 'fo_cable_id');
    }


}
