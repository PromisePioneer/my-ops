<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, Searchable, SoftDeletes, LogsActivity;
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


    public function leaves(): HasMany
    {
        return $this->hasMany(LeaveAndPermission::class, 'user_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function toSearchableArray(): array
    {
        $this->loadMissing('attendancesSummary');
        return [
            'name' => $this->name,
            'nip' => $this->nip
        ];
    }

    public function makeSearchableUsing(Collection $models): Collection
    {
        return $models->load('attendancesSummary');
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


    public function attendancesSummary(): HasMany
    {
        return $this->hasMany(AttendanceSummary::class, 'employee_id', 'absent_id');
    }


    public function employeeSchedules(): HasMany
    {
        return $this->hasMany(EmployeeSchedule::class, 'employee_id', 'absent_id');
    }


    public function leaveAndPermissions(): HasMany
    {
        return $this->hasMany(LeaveAndPermission::class, 'user_id');
    }

    public function overtimeAllowance()
    {
        return $this->hasMany(UserHasOvertime::class, 'user_id');
    }

    public function mealAllowance(): HasMany
    {
        return $this->hasMany(UserHasMealAllowance::class, 'user_id');
    }

    public function transportationAllowance(): HasMany
    {
        return $this->hasMany(UserHasTransportationAllowance::class, 'user_id');
    }

    public function SLADeduction(): HasMany
    {
        return $this->hasMany(SLADeduction::class, 'kca_id');
    }

    public function ninePastFifteenDeduction(): HasMany
    {
        return $this->hasMany(NinePastFiveTeenLateDeduction::class, 'technician_id');
    }


    public function additionalDeduction(): HasMany
    {
        return $this->hasMany(AdditionalDeduction::class, 'user_id');
    }


    public function contract(): HasOne
    {
        return $this->hasOne(ContractManagement::class, 'user_id');
    }


    public function weekHoliday(): HasOne
    {
        return $this->hasOne(WeekHoliday::class, 'user_id');
    }


    public function employeeSchedule(): BelongsTo
    {
        return $this->belongsTo(EmployeeSchedule::class, 'employee_id', 'absent_id');
    }


    //eloquent
    public function getData(): Builder
    {
        return self::with('branch', 'roles', 'company');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->logOnly([
                'branch_id',
                'absent_id',
                'company_id',
                'join_date',
                'name',
                'email',
                'nip',
                'last_login',
                'profile_pic',
                'placement',
            ]);
    }
}
