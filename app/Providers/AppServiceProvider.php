<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        $appUrl = env('APP_URL');

        if ($appUrl) {
            URL::forceRootUrl(rtrim($appUrl, '/'));
        }

        if ($this->app->environment('production') && ($appUrl && str_starts_with($appUrl, 'https://'))) {
            URL::forceScheme('https');
        }
    }
}
