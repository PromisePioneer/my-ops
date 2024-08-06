<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterHead extends Model
{
    protected $table = 'letter_head';

    protected $fillable = [
        'header',
        'footer',
    ];
}
