<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use App\Models\Master\Common\Contact;
use App\Models\Master\Common\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferingLetter extends Model
{
    use HasFactory;

    protected $table = 'offering_letters';

    protected $fillable = [
        'branch_id',
        'contact_id',
        'offering_number',
        'date',
        'regarding',
        'status',
        'pic',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'offering_letter_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic');
    }
}
