<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Account;
use App\Models\AccountCategory;
use App\Models\AccountTransaction;
use App\Models\Area;
use App\Models\Asset;
use App\Models\AttendanceManualRequest;
use App\Models\AttendancesSummary;
use App\Models\Boq;
use App\Models\BroadbandPacket;
use App\Models\Company;
use App\Models\Department;
use App\Models\EmployeeSchedule;
use App\Models\Fab;
use App\Models\ItemCollection;
use App\Models\LeaveAndPermission;
use App\Models\Master\Common\Branch;
use App\Models\Master\Common\Contact;
use App\Models\Master\Common\ServiceCategory;
use App\Models\Master\Common\SKL;
use App\Models\Master\Common\UnitType;
use App\Models\NationalHoliday;
use App\Models\OfferingLetter;
use App\Models\Product;
use App\Models\PSB;
use App\Models\PurchaseOrder;
use App\Models\SP;
use App\Models\TaxSetting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WorkTime;
use App\Policies\AccountCategoryPolicy;
use App\Policies\AccountPolicy;
use App\Policies\AreaPolicy;
use App\Policies\AssetPolicy;
use App\Policies\AttendanceManualRequestPolicy;
use App\Policies\AttendanceSummaryPolicy;
use App\Policies\BoqPolicy;
use App\Policies\BranchPolicy;
use App\Policies\BroadbandPacketPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\ContactPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\EmployeeSchedulePolicy;
use App\Policies\FabPolicy;
use App\Policies\InitialBalancePolicy;
use App\Policies\ItemCollectionPolicy;
use App\Policies\LeaveAndPermissionPolicy;
use App\Policies\NationalHolidayPolicy;
use App\Policies\OfferingLetterPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\ProductPolicy;
use App\Policies\PSBPolicy;
use App\Policies\PurchaseOrderPolicy;
use App\Policies\RolePolicy;
use App\Policies\ServiceCategoriesPolicy;
use App\Policies\SKLPolicy;
use App\Policies\SpPolicy;
use App\Policies\TaxSettingPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\UnitTypePolicy;
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
        AccountCategory::class => AccountCategoryPolicy::class,
        Account::class => AccountPolicy::class,
        Branch::class => BranchPolicy::class,
        Contact::class => ContactPolicy::class,
        ServiceCategory::class => ServiceCategoriesPolicy::class,
        Department::class => DepartmentPolicy::class,
        Role::class => RolePolicy::class,
        WorkTime::class => WorkTimePolicy::class,
        SP::class => SpPolicy::class,
        NationalHoliday::class => NationalHolidayPolicy::class,
        User::class => UserPolicy::class,
        Permission::class => PermissionPolicy::class,
        LeaveAndPermission::class => LeaveAndPermissionPolicy::class,
        AttendancesSummary::class => AttendanceSummaryPolicy::class,
        Boq::class => BoqPolicy::class,
        AccountTransaction::class => InitialBalancePolicy::class,
        Company::class => CompanyPolicy::class,
        OfferingLetter::class => OfferingLetterPolicy::class,
        Fab::class => FabPolicy::class,
        PurchaseOrder::class => PurchaseOrderPolicy::class,
        SKL::class => SKLPolicy::class,
        UnitType::class => UnitTypePolicy::class,
        BroadbandPacket::class => BroadbandPacketPolicy::class,
        Area::class => AreaPolicy::class,
        TaxSetting::class => TaxSettingPolicy::class,
        Asset::class => AssetPolicy::class,
        PSB::class => PSBPolicy::class,
        EmployeeSchedule::class => EmployeeSchedulePolicy::class,
        Transaction::class => TransactionPolicy::class,
        AttendanceManualRequest::class => AttendanceManualRequestPolicy::class,
        ItemCollection::class => ItemCollectionPolicy::class,
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
