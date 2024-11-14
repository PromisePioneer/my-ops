<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fab extends Model
{
    protected $table = 'fab';

    protected $fillable = [
        'offering_letter_id',
        'fab_number',
        'contract_number',
        'date',
        'contact_id',
        'pic',
        'created_by',
        'contact_id',
        'created_by'
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function fabPic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic');
    }


}
