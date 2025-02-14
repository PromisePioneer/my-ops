<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InitialJournal extends Model
{
    use HasFactory;

    protected $table = 'initial_journal';

    protected $fillable = [
        'description',
        'sub_account_debit',
        'sub_account_credit',
        'initial_payment',
    ];
}
