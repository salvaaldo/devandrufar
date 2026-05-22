<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function listar()
    {
        return User::orderBy('name')->paginate(10);
    }

    public function crear(array $datos): User
    {
        $datos['password'] = Hash::make($datos['password']);
        return User::create($datos);
    }

    public function actualizar(User $user, array $datos): User
    {
        if (!empty($datos['password'])) {
            $datos['password'] = Hash::make($datos['password']);
            $datos['debe_cambiar_password'] = true; // Forzar cambio si el admin la resetea
        } else {
            unset($datos['password']);
        }

        $user->update($datos);
        return $user;
    }

    public function eliminar(User $user): void
    {
        $user->delete();
    }

    public function toggleActivo(User $user): void
    {
        $user->update(['activo' => !$user->activo]);
    }
}