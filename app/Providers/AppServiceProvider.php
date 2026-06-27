<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Cara aman memeriksa apakah request datang dari ngrok
        if (str_contains(request()->headers->get('X-Forwarded-Host', ''), 'ngrok-free.app') || 
            str_contains(request()->header('host', ''), 'ngrok-free.app')) {
            
            URL::forceScheme('https');
        }
    }
}