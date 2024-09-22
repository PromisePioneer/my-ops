<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $branch_id
 * @property int|null $fab_id
 * @property string $bast_number
 * @property int $contact_id
 * @property string $date
 * @property string $first_party_identity_name
 * @property string $first_party_position
 * @property string $objective
 * @property string|null $file
 * @property int $status
 * @property int $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Contact $contact
 * @property-read Fab|null $fab
 * @property-read User $user
 *
 * @method static Builder|Bast newModelQuery()
 * @method static Builder|Bast newQuery()
 * @method static Builder|Bast query()
 * @method static Builder|Bast whereBastNumber($value)
 * @method static Builder|Bast whereBranchId($value)
 * @method static Builder|Bast whereContactId($value)
 * @method static Builder|Bast whereCreatedAt($value)
 * @method static Builder|Bast whereCreatedBy($value)
 * @method static Builder|Bast whereDate($value)
 * @method static Builder|Bast whereFabId($value)
 * @method static Builder|Bast whereFile($value)
 * @method static Builder|Bast whereFirstPartyIdentityName($value)
 * @method static Builder|Bast whereFirstPartyPosition($value)
 * @method static Builder|Bast whereId($value)
 * @method static Builder|Bast whereObjective($value)
 * @method static Builder|Bast whereStatus($value)
 * @method static Builder|Bast whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Bast extends Model
{
    protected $table = 'bast';

    protected $fillable = [
        'branch_id',
        'fab_id',
        'bast_number',
        'contact_id',
        'date',
        'first_party_identity_name',
        'first_party_position',
        'objective',
        'file',
        'status',
        'created_by',
    ];

    protected $with = [
        'contact',
        'fab',
        'user',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function fab(): BelongsTo
    {
        return $this->belongsTo(Fab::class, 'fab_id');
    }

    public function getDataWithPagination(Request $request, int $perPage): LengthAwarePaginator
    {
        return self::with('contact', 'user')
            ->where('branch_id', $request->user()->branch_id)
            ->paginate($perPage);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function filterDataBasedOnBranch(int $branchId, int $perPage): LengthAwarePaginator
    {
        return self::with('contact', 'user')->where('branch_id', $branchId)->paginate($perPage);
    }
}
