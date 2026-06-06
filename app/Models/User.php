<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo Eloquent para representar a los usuarios del sistema.
 * Implementa la autenticación nativa de Laravel, notificaciones y borrado lógico (SoftDeletes).
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Atributos que se pueden asignar de manera masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'ci',
        'telefono',
        'email',
        'password',
        'rol',
        'activo',
        'debe_cambiar_password',
    ];

    /**
     * Atributos que deben permanecer ocultos para las serializaciones (como respuestas JSON).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mapeo de conversión (cast) de tipos de atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'activo'   => 'boolean',
            'debe_cambiar_password' => 'boolean',
        ];
    }

    /**
     * Determina si el usuario tiene asignado el rol de Administrador.
     *
     * @return bool True si es administrador, false en caso contrario.
     */
    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    /**
     * Determina si el usuario tiene asignado el rol de Operador.
     *
     * @return bool True si es operador, false en caso contrario.
     */
    public function esOperador(): bool
    {
        return $this->rol === 'operador';
    }

    /**
     * Relación uno a muchos con las cotizaciones registradas por este usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class);
    }
}