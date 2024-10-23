<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\AttendancesSummary;
use App\Models\Branch;
use App\Models\Contact;
use App\Models\Department;
use App\Models\LeaveAndPermission;
use App\Models\NationalHoliday;
use App\Models\Product;
use App\Models\ServiceCategory;
use App\Models\SP;
use App\Models\User;
use App\Models\WorkTime;
use App\Policies\AccountPolicy;
use App\Policies\AttendanceRecordPolicy;
use App\Policies\BoqPolicy;
use App\Policies\BranchPolicy;
use App\Policies\ContactPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\InitialBalancePolicy;
use App\Policies\LeaveAndPermissionPolicy;
use App\Policies\NationalHolidayPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\ProductPolicy;
use App\Policies\RolePolicy;
use App\Policies\ServiceCategoriesPolicy;
use App\Policies\SpPolicy;
use App\Policies\UserPolicy;
use App\Policies\WorkTimePolicy;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Account::class => AccountPolicy::class,
        Branch::class => BranchPolicy::class,
        Contact::class => ContactPolicy::class,
        Product::class => ProductPolicy::class,
        ServiceCategory::class => ServiceCategoriesPolicy::class,
        Department::class => DepartmentPolicy::class,
        Role::class => RolePolicy::class,
        WorkTime::class => WorkTimePolicy::class,
        SP::class => SpPolicy::class,
        NationalHoliday::class => NationalHolidayPolicy::class,
        User::class => UserPolicy::class,
        Permission::class => PermissionPolicy::class,
        LeaveAndPermission::class => LeaveAndPermissionPolicy::class,
        AttendancesSummary::class => AttendanceRecordPolicy::class,
        BoqPolicy::class => BoqPolicy::class,
        AccountTransaction::class => InitialBalancePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        Gate::before(static function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
