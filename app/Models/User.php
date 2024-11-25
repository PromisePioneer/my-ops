<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'branch_id',
        'absent_id',
        'company_id',
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

    public function userHasArea(): HasOne
    {
        return $this->hasOne(UserHasArea::class, 'user_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
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


    public function attendancesSummary(): HasMany
    {
        return $this->hasMany(AttendancesSummary::class, 'employee_id', 'absent_id');
    }


    public function contract(): HasOne
    {
        return $this->hasOne(ContractManagement::class, 'user_id');
    }


    //eloquent
    public function getData(): Builder
    {
        return self::with('branch', 'roles', 'company');
    }

    public function getUserBasedOnBranch(Request $request): array
    {
        $search = $request->input('search');
        $query = self::with('branch')->where('branch_id', $request->user()->branch_id);

        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
            $query->orWhere('email', 'like', '%' . $search . '%');
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
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%');
    }


    public function getUser(Request $request): array
    {
        $search = $request->search;

        $query = self::where('active', '=', 1)
            ->orderBy('name')
            ->select('id', 'name', 'nip');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nip', 'like', '%' . $search . '%');
            });
        }

        if ($request->user()->hasRole('FA & Tax Manager')) {
            $query->whereNot('id', $request->user()->id)
                ->role(['Accounting']);
        }

        if ($request->user()->hasRole('Branch Manager')) {
            $query->whereNot('id', $request->user()->id)
                ->where('branch_id', $request->user()->branch_id)
                ->role([
                    'Head Engineer',
                    'Senior Engineer',
                    'Finance & Accounting Staff',
                    'Stocker Staff',
                ]);
        }

        $users = $query->get();

        return $users->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nip . ' ' . $item->name,
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
