<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobExperience extends Model
{
    use HasFactory;

    protected $table = 'job_experiences';

    protected $fillable = [
        'user_id',
        'company_name',
        'position',
        'responsibilities',
        'start_date',
        'end_date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(JobExperience::class, 'user_id');
    }
}
