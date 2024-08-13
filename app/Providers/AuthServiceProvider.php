<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Contact;
use App\Models\Department;
use App\Models\Product;
use App\Models\ServiceCategory;
use App\Policies\AccountPolicy;
use App\Policies\BranchPolicy;
use App\Policies\ContactPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\ProductPolicy;
use App\Policies\RolePolicy;
use App\Policies\ServiceCategoriesPolicy;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
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
        Role::class => RolePolicy::class
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


        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));


        Gate::before(static function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
