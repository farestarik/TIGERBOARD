<?php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('vendor.pagination.bootstrap-4');

        Schema::defaultStringLength(191);

        // Use View Composer to share settings
        View::composer('*', function ($view) {
            if (Schema::hasTable('settings')) {
                $site_settings = Settings::first();
                $view->with('site_settings', $site_settings);
            }
        });
    }
}