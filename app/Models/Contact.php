<?php

namespace App\Models;

use Eloquent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $branch_id
 * @property string $full_name
 * @property string|null $company_name
 * @property string $email
 * @property string $phone_number
 * @property string $identity_type
 * @property string $identity_number
 * @property string|null $fax
 * @property string $npwp
 * @property string $complete_address
 * @property string|null $other_info
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Branch|null $branch
 *
 * @method static Builder|Contact newModelQuery()
 * @method static Builder|Contact newQuery()
 * @method static Builder|Contact query()
 * @method static Builder|Contact whereBranchId($value)
 * @method static Builder|Contact whereCompanyName($value)
 * @method static Builder|Contact whereCompleteAddress($value)
 * @method static Builder|Contact whereCreatedAt($value)
 * @method static Builder|Contact whereEmail($value)
 * @method static Builder|Contact whereFax($value)
 * @method static Builder|Contact whereFullName($value)
 * @method static Builder|Contact whereId($value)
 * @method static Builder|Contact whereIdentityNumber($value)
 * @method static Builder|Contact whereIdentityType($value)
 * @method static Builder|Contact whereNpwp($value)
 * @method static Builder|Contact whereOtherInfo($value)
 * @method static Builder|Contact wherePhoneNumber($value)
 * @method static Builder|Contact whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = [
        'branch_id',
        'full_name',
        'company_name',
        'email',
        'phone_number',
        'identity_type',
        'identity_number',
        'fax',
        'npwp',
        'complete_address',
        'other_info',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    //eloquent
    public function getDataWithPaginationBasedOnUserBranch(?int $branchId, int $perPage): LengthAwarePaginator
    {
        return self::where('branch_id', $branchId)->paginate($perPage);
    }

    public function searchDataBasedOnUserBranch(Request $request): Collection
    {
        $search = $request->input('search');

        return self::where('full_name', 'like', '%'.$search.'%')
            ->where('branch_id', $request->user()->branch_id)
            ->orWhere('company_name', 'like', '%'.$search.'%')
            ->orWhere('email', 'like', '%'.$search.'%')
            ->orWhere('phone_number', 'like', '%'.$search.'%')
            ->orWhere('identity_type', 'like', '%'.$search.'%')
            ->orWhere('identity_number', 'like', '%'.$search.'%')
            ->orWhere('fax', 'like', '%'.$search.'%')
            ->orWhere('npwp', 'like', '%'.$search.'%')
            ->orWhere('complete_address', 'like', '%'.$search.'%')
            ->orWhere('other_info', 'like', '%'.$search.'%')
            ->limit(25)
            ->get();
    }

    public function filterDataBasedOnUserBranch(int $branchId, int $perPage): LengthAwarePaginator
    {
        return self::where('branch_id', $branchId)->paginate($perPage);
    }

    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::orderby('full_name', 'asc');
        if ($search !== '') {
            $query->where('full_name', 'like', '%'.$request->search.'%')
                ->where('full_name', 'like', '%'.$request->search.'%');
        }
        $contact = $query->get(['id', 'full_name']);

        return $contact->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->full_name,
            ];
        })->toArray();
    }

    public function getSelectedData(int $contactId): array
    {
        $contact = self::where('id', $contactId)->first();

        return [
            'id' => $contact->id,
            'name' => $contact->full_name,
            'company_name' => $contact->company_name,
        ];
    }
}
