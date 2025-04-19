<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratePayroll extends Model
{
    use HasFactory;

    protected $table = 'generate_payroll';
    protected $fillable = [
        'user_id',
        'period',
        'payment_schedule',
        'basic_salary',
        'allowances',
        'published',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
