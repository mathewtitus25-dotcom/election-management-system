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
        // Only run during web requests, NOT during artisan commands (like build/deploy)
        if (app()->runningInConsole()) {
            return;
        }

        try {
            $dbPath = database_path('database.sqlite');

            // Step 1: Create the SQLite file if it was deleted (server hibernated)
            if (!file_exists($dbPath)) {
                touch($dbPath);
            }

            // Step 2: Check if tables exist. If not, auto-migrate and seed.
            if (!\Illuminate\Support\Facades\Schema::hasTable('users')) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--seed' => true, '--force' => true]);
            }
        } catch (\Exception $e) {
            // Fails quietly during edge cases
        }
    }
}
