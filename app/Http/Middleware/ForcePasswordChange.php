<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware para forzar el cambio de contraseña al primer inicio de sesión o tras un reseteo administrativo.
 * Intercepta todas las peticiones entrantes y, si el usuario autenticado tiene activa la bandera
 * 'debe_cambiar_password', restringe su navegación, permitiéndole únicamente acceder a las rutas
 * de cambio de contraseña o cierre de sesión, redireccionando cualquier otro intento.
 */
class ForcePasswordChange
{
    /**
     * Maneja la petición entrante.
     *
     * @param  \Illuminate\Http\Request  $request Petición HTTP entrante.
     * @param  \Closure  $next Siguiente middleware en la tubería.
     * @return \Symfony\Component\HttpFoundation\Response Respuesta HTTP de redirección o continuación.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario inició sesión y tiene pendiente el cambio de clave obligatorio
        if (Auth::check() && Auth::user()->debe_cambiar_password) {
            
            // Permitir el flujo únicamente si la petición va dirigida a las rutas específicas del cambio de contraseña o logout
            if (!$request->routeIs('password.change') && 
                !$request->routeIs('password.change.update') && 
                !$request->routeIs('logout')) {
                
                // Redireccionar forzosamente a la vista de cambio de contraseña con un mensaje explicativo
                return redirect()->route('password.change')
                    ->with('warning', 'Por seguridad, debes cambiar tu contraseña antes de continuar.');
            }
        }

        return $next($request);
    }
}