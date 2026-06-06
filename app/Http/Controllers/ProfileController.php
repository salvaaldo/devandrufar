<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Controlador encargado de gestionar el perfil del usuario autenticado.
 * Administra el cambio forzado de contraseña en el primer acceso y la edición/eliminación de datos personales.
 */
class ProfileController extends Controller
{
    /**
     * Muestra la vista con el formulario para forzar el cambio de contraseña inicial.
     *
     * @return \Illuminate\View\View
     */
    public function showPasswordChange(): View
    {
        return view('auth.password-change');
    }

    /**
     * Procesa y valida la actualización forzada de contraseña de un usuario.
     * Encripta la nueva clave, apaga la bandera 'debe_cambiar_password' y redirige al dashboard.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        // Validar que la contraseña cumpla políticas estrictas (longitud, caracteres mixtos, números y símbolos)
        $validated = $request->validate([
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()
            ],
        ]);

        // Actualizar la contraseña del usuario y apagar la bandera de cambio obligatorio
        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'debe_cambiar_password' => false,
        ]);

        return redirect()->route('dashboard')->with('success', 'Contraseña actualizada correctamente. Bienvenido al sistema.');
    }

    /**
     * Muestra el formulario para editar la información del perfil del usuario logueado.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Procesa la actualización de los datos del perfil (nombre, email).
     * Si el correo electrónico cambia, invalida la verificación del mismo.
     *
     * @param \App\Http\Requests\ProfileUpdateRequest $request Solicitud de actualización validada.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        // Si cambió el correo electrónico, se desmarca la fecha de verificación
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Elimina permanentemente la cuenta del usuario logueado (cierre y baja).
     * Requiere que el usuario confirme su contraseña actual antes de proceder.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Cerrar sesión del usuario en la sesión web actual
        Auth::logout();

        // Realizar baja del usuario (Soft Delete)
        $user->delete();

        // Destruir la sesión
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

