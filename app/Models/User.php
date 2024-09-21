<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $absent_id
 * @property string $placement
 * @property int|null $branch_id
 * @property string $nip
 * @property string $name
 * @property string $join_date
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $last_login
 * @property string|null $profile_pic
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Branch|null $branch
 * @property-read JobInformation|null $jobInformation
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 *
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User permission($permissions, $without = false)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null, $without = false)
 * @method static Builder|User whereAbsentId($value)
 * @method static Builder|User whereBranchId($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereJoinDate($value)
 * @method static Builder|User whereLastLogin($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User whereNip($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePlacement($value)
 * @method static Builder|User whereProfilePic($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User withoutPermission($permissions)
 * @method static Builder|User withoutRole($roles, $guard = null)
 *
 * @mixin Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'branch_id',
        'absent_id',
        'join_date',
        'name',
        'email',
        'password',
        'nip',
        'last_login',
        'profile_pic',
        'placement',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function jobInformation(): HasOne
    {
        return $this->hasOne(JobInformation::class);
    }

    public function identityInformation(): HasOne
    {
        return $this->hasOne(IdentityInformation::class);
    }

    public function education(): HasOne
    {
        return $this->hasOne(Education::class);
    }

    public function userHasWorkTime(): hasOne
    {
        return $this->hasOne(UserWorkTime::class, 'user_id');
    }


    public function attendance(): HasMany
    {
        return $this->hasMany(Attendances::class, 'employee_id', 'absent_id');
    }


    public function contract(): HasOne
    {
        return $this->hasOne(ContractManagement::class, 'user_id');
    }


    public function getUserDataBasedOnUserBranch(): LengthAwarePaginator
    {
        return self::with('branch')->where('branch_id', $this->branch_id)->paginate(10);
    }


    //eloquent
    public function getData(): Builder
    {
        return self::with('branch', 'roles');
    }

    public function getUserBasedOnBranch(Request $request): array
    {
        $search = $request->input('search');
        $query = self::with('branch')->where('branch_id', $request->user()->branch_id);

        if ($search !== '') {
            $query->where('name', 'like', '%'.$search.'%');
            $query->orWhere('email', 'like', '%'.$search.'%');
        }

        $user = $query->get();

        return $user->map(function ($user) {
            return [
                'id' => $user->id,
                'text' => "({$user->nip}) {$user->name}",
            ];
        })->toArray();
    }

    public function searchData(Request $request): Collection
    {
        $search = $request->input('search');

        return self::with('roles')
            ->where('name', 'like', '%'.$search.'%')
            ->orWhere('email', 'like', '%'.$search.'%');
    }

    public function filterBasedOnUserBranch(int $branchId, int $perPage): LengthAwarePaginator
    {
        return self::with('roles')->where('branch_id', $branchId)->paginate($perPage);
    }

    public function getUser(Request $request): array
    {
        $search = $request->search;

        $query = self::where('active', '=', 1)
            ->orderBy('name')
            ->select('id', 'name', 'nip');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('nip', 'like', '%'.$search.'%');
            });
        }

        if ($request->user()->hasRole('FA & Tax Manager')) {
            $query->whereNot('id', $request->user()->id)
                ->role(['Accounting']);
        }

        if ($request->user()->hasRole('Branch Manager')) {
            $query->whereNot('id', $request->user()->id)
                ->where('branch_id', $request->user()->branch_id)
                ->role(['KCA', 'WKCA', 'Teknisi', 'Accounting', 'Stocker']);
        }

        $users = $query->get();

        return $users->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nip.' '.$item->name,
            ];
        })->toArray();
    }

    public function getSelectedData(int $userId): ?array
    {
        $user = self::where('id', $userId)->first();

        return [
            'id' => $user->id,
            'name' => $user->name,
        ];
    }
}
