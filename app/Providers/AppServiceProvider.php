<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\Inventario;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // ✅ Detectar automáticamente si es local o ngrok (usando headers de proxy)
        $host = request()->header('x-forwarded-host') ?? request()->getHost();
        
        if (str_contains($host, 'ngrok')) {
            URL::forceRootUrl('https://' . $host);
            URL::forceScheme('https');
        }

        // 🔔 Compartir conteo de vencidos y próximos globalmente
        View::composer('*', function ($view) {
            if (auth()->check()) {
                // Conteo de Vencidos (Caché 10 min)
                $vencidosCount = \Illuminate\Support\Facades\Cache::remember('vencidos_count_global', 600, function () {
                    return Inventario::where('estado', 'vencido')
                                    ->where('cantidad', '>', 0)
                                    ->count();
                });

                // Conteo de Próximos (Caché 10 min)
                $proximosCount = \Illuminate\Support\Facades\Cache::remember('proximos_count_global', 600, function () {
                    return Inventario::where('estado', 'por_vencer')
                                    ->where('cantidad', '>', 0)
                                    ->count();
                });

                $view->with('globalVencidosCount', (int)$vencidosCount);
                $view->with('globalProximosCount', (int)$proximosCount);
            }
        });
    }
}