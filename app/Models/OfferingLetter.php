<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $branch_id
 * @property int $contact_id
 * @property string $offering_number
 * @property string $date
 * @property string $attachment
 * @property string $foreword
 * @property string $notes
 * @property string $marketing_agent_name
 * @property string $marketing_agent_contact
 * @property string $file
 * @property int $status
 * @property int $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Branch|null $branch
 * @property-read Contact $contact
 * @property-read ServiceCategory|null $serviceCategory
 * @property-read User $user
 *
 * @method static Builder|OfferingLetter newModelQuery()
 * @method static Builder|OfferingLetter newQuery()
 * @method static Builder|OfferingLetter query()
 * @method static Builder|OfferingLetter whereAttachment($value)
 * @method static Builder|OfferingLetter whereBranchId($value)
 * @method static Builder|OfferingLetter whereContactId($value)
 * @method static Builder|OfferingLetter whereCreatedAt($value)
 * @method static Builder|OfferingLetter whereCreatedBy($value)
 * @method static Builder|OfferingLetter whereDate($value)
 * @method static Builder|OfferingLetter whereFile($value)
 * @method static Builder|OfferingLetter whereForeword($value)
 * @method static Builder|OfferingLetter whereId($value)
 * @method static Builder|OfferingLetter whereMarketingAgentContact($value)
 * @method static Builder|OfferingLetter whereMarketingAgentName($value)
 * @method static Builder|OfferingLetter whereNotes($value)
 * @method static Builder|OfferingLetter whereOfferingNumber($value)
 * @method static Builder|OfferingLetter whereStatus($value)
 * @method static Builder|OfferingLetter whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class OfferingLetter extends Model
{
    use HasFactory;

    protected $table = 'offering_letters';

    protected $fillable = [
        'branch_id',
        'contact_id',
        'offering_number',
        'date',
        'attachment',
        'foreword',
        'notes',
        'marketing_agent_name',
        'marketing_agent_contact',
        'status',
        'file',
        'created_by',
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
        return $this->belongsTo(User::class, 'created_by');
    }
}
