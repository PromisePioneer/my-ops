<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FingerLog extends Model
{
    use HasFactory;

    protected $table = 'finger_logs';
    protected $fillable = [
        'data',
        'url'
    ];
}
