<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Eloquent para representar a los clientes de la distribuidora/empresa.
 * Almacena información fiscal (NIT), datos de contacto y estado de habilitación comercial.
 */
class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'clientes';

    /**
     * Atributos asignables de forma masiva.
     *
     * @var array
     */
    protected $fillable = [
        'codigo',
        'nombre',
        'nit',
        'telefono',
        'direccion',
        'activo',
    ];

    /**
     * Conversión automática de tipos de atributos.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }
}