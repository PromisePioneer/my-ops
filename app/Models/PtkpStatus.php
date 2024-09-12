<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtkpStatus extends Model
{
    use HasFactory;

    protected $table = 'ptkp_status';
    protected $fillable = [
        'name',
        'amount',
        'rate',
    ];
}
