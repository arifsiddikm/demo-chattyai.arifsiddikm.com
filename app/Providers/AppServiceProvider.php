<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Fix setLocale error
        Carbon::setLocale(config('app.locale', 'id'));

        // Force HTTPS on production
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
