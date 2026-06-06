<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Controlador encargado de gestionar el ciclo de vida de la sesión autenticada.
 * Provee las vistas y lógica de inicio y cierre de sesión de usuarios.
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Muestra la vista del formulario de inicio de sesión.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Procesa la solicitud de autenticación entrante.
     * Si la autenticación es correcta, regenera la sesión, actualiza de forma automática
     * los estados de vencimiento de lotes del inventario y redirige al dashboard.
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request Solicitud de inicio de sesión validada.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Ejecutar validaciones y autenticación del usuario
        $request->authenticate();

        // Regenerar la sesión para prevenir ataques de fijación de sesión
        $request->session()->regenerate();

        //  Actualizar los estados de vencimiento del inventario de forma automática tras un login exitoso
        try {
            app(\App\Services\InventarioService::class)->actualizarEstados();
        } catch (\Exception $e) {
            // Registrar error silencioso en los logs para no bloquear el inicio de sesión del usuario
            \Illuminate\Support\Facades\Log::error("Error al actualizar inventario en login: " . $e->getMessage());
        }

        // Redirigir a la ruta destinada o al dashboard por defecto
        return redirect()->intended(route('dashboard', absolute: false))->with('login_success', true);
    }

    /**
     * Destruye la sesión del usuario autenticado (Cierre de sesión).
     * Invalida los datos de sesión y regenera el token CSRF por seguridad.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Cerrar sesión en el guard web
        Auth::guard('web')->logout();

        // Invalidar la sesión actual
        $request->session()->invalidate();

        // Regenerar el token CSRF
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

