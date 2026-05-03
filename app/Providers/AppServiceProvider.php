<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // ✅ AGREGA ESTO
        if (str_contains(request()->getHost(), 'ngrok')) {
            URL::forceRootUrl(config('app.url'));
            URL::forceScheme('https');
        }
    }
}