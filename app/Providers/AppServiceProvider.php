<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
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
        if (filter_var(env('VITE_USE_BUILD_ONLY', false), FILTER_VALIDATE_BOOLEAN)) {
            Vite::useHotFile(storage_path('framework/vite.hot'));
        }
    }
}
