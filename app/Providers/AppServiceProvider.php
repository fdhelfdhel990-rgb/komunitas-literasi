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
    $forwardedHost = request()->header('X-Forwarded-Host') ?: request()->getHost();

    if (str_contains($forwardedHost, 'devtunnels.ms')) {
        URL::forceRootUrl('https://' . $forwardedHost);
        URL::forceScheme('https');
    }
}
}
