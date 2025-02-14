<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractManagement extends Model
{
    use HasFactory;

    protected $table = 'contract_management';
    protected $fillable = [
        'contract_number',
        'user_id',
        'start_date',
        'end_date',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
