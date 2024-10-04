<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pole extends Model
{
    use HasFactory;

    protected $table = 'poles';
    protected $fillable = [
        'diameter',
        'length',
        'region',
        'code',
        'lat',
        'long',
        'cut_off_date',
    ];

}
