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
        // Auto-create database.sqlite if using SQLite and file does not exist
        if (config('database.default') === 'sqlite') {
            $path = database_path('database.sqlite');
            if (!file_exists($path)) {
                @touch($path);
            }
        }
    }
}
