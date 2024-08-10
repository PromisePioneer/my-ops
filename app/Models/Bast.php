<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Contact $contact
 * @property-read \App\Models\Fab|null $fab
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Bast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bast query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bast whereBastNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bast whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bast whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bast whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bast whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bast whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bast whereFabId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bast whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bast whereFirstPartyIdentityName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bast whereFirstPartyPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bast whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bast whereObjective($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bast whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bast whereUpdatedAt($value)
 *
 * @mixin \Eloquent
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getDataWithPagination(Request $request, int $perPage): LengthAwarePaginator
    {
        return self::with('contact', 'user')
            ->where('branch_id', $request->user()->branch_id)
            ->paginate($perPage);
    }

    public function searchDataBasedOnUserBranch(Request $request)
    {
        $search = $request->input('search');

        return self::where('bast_number', 'like', '%'.$search.'%')
            ->where('branch_id', $request->user()->branch_id)
            ->orWhereHas('contact', function ($query) use ($search) {
                $query->where('full_name', 'like', '%'.$search.'%');
                $query->orWhere('company_name', 'like', '%'.$search.'%');
            })
            ->orWhere('date', 'like', '%'.$search.'%')
            ->orWhere('first_party_identity_name', 'like', '%'.$search.'%')
            ->orWhere('first_party_position', 'like', '%'.$search.'%')
            ->orWhere('objective', 'like', '%'.$search.'%')
            ->orWhere('file', 'like', '%'.$search.'%')
            ->orWhere('status', 'like', '%'.$search.'%')
            ->get();
    }

    public function filterDataBasedOnBranch(int $branchId, int $perPage): LengthAwarePaginator
    {
        return self::with('contact', 'user')->where('branch_id', $branchId)->paginate($perPage);
    }
}
