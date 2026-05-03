<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'ci',
        'telefono',
        'email',
        'password',
        'rol',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'activo'   => 'boolean',
        ];
    }

    // Verifica si el usuario es admin
    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    // Verifica si el usuario es operador
    public function esOperador(): bool
    {
        return $this->rol === 'operador';
    }
}