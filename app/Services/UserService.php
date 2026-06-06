<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Servicio encargado de gestionar los usuarios del sistema.
 * Administra el registro, actualización de contraseñas (con hash seguro),
 * desactivación de cuentas y la política de cambio obligatorio de clave.
 */
class UserService
{
    /**
     * Obtiene el listado paginado de usuarios, ordenados alfabéticamente por su nombre.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function listar()
    {
        return User::orderBy('name')->paginate(10);
    }

    /**
     * Crea un nuevo usuario en el sistema.
     * Encripta de forma segura la contraseña usando el algoritmo Hash de Laravel antes de almacenar el registro.
     *
     * @param array $datos Datos del nuevo usuario (nombre, email, rol, contraseña).
     * @return \App\Models\User El usuario creado.
     */
    public function crear(array $datos): User
    {
        $datos['password'] = Hash::make($datos['password']);
        return User::create($datos);
    }

    /**
     * Actualiza la información de un usuario existente.
     * Si se provee una nueva contraseña, esta se encripta y se activa la bandera
     * 'debe_cambiar_password' para forzar al usuario a renovar su clave en el próximo inicio de sesión.
     *
     * @param \App\Models\User $user Modelo del usuario a actualizar.
     * @param array $datos Nuevos datos provistos por el administrador.
     * @return \App\Models\User El usuario actualizado.
     */
    public function actualizar(User $user, array $datos): User
    {
        if (!empty($datos['password'])) {
            $datos['password'] = Hash::make($datos['password']);
            // Si el administrador cambia la contraseña manualmente, se le obliga al usuario a cambiarla al ingresar
            $datos['debe_cambiar_password'] = true;
        } else {
            // Si no se asignó una contraseña en el formulario, se conserva la actual sin alterar el hash
            unset($datos['password']);
        }

        $user->update($datos);
        return $user;
    }

    /**
     * Elimina permanentemente un usuario del sistema.
     *
     * @param \App\Models\User $user Modelo del usuario a eliminar.
     * @return void
     */
    public function eliminar(User $user): void
    {
        $user->delete();
    }

    /**
     * Alterna (activa/desactiva) el estado de acceso de un usuario.
     * Los usuarios inactivos no pueden autenticarse en el sistema.
     *
     * @param \App\Models\User $user Modelo del usuario al cual se le alternará el estado.
     * @return void
     */
    public function toggleActivo(User $user): void
    {
        $user->update(['activo' => !$user->activo]);
    }
}