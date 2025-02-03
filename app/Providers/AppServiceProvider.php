<?php

namespace App\Providers;

use App\Models\Attendances;
use App\Observers\AttendanceSummaryObserver;
use App\Observers\GoodsStockObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Attendances::observe(AttendanceSummaryObserver::class);
    }
}
