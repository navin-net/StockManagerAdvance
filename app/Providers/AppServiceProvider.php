<?php

namespace App\Providers;

use Illuminate\Support\Facades\{App, Schema};
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
        App::setLocale(session('locale', 'en'));
        Schema::defaultStringLength(191);

    }
}
