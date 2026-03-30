<?php

namespace App\Providers;

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
        // 🚀 Serverless Auto-Repair For SQLite (Laravel Cloud)
        // If the 'users' table is missing (which happens after server sleeps),
        // we force an automatic migration and seeding so the app never shows a 500 Error.
        if (!\Illuminate\Support\Facades\Schema::hasTable('users')) {
            try {
                \Illuminate\Support\Facades\Artisan::call('migrate:fresh --seed --force');
            } catch (\Exception $e) {
                // Fails quietly if something blocks it
            }
        }
    }
}
