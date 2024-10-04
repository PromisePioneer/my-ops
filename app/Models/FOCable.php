<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FOCable extends Model
{
    use HasFactory;

    protected $table = 'fo_cables';
    protected $fillable = [
        'segment_id',
        'classification',
        'cable_placement',
        'cable_address',
        'total_core',
        'starting_point_lat',
        'starting_point_long',
        'ending_point_lat',
        'ending_point_long',
        'length',
        'cut_off_date',
    ];
}
