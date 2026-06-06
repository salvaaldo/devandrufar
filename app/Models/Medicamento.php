<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Eloquent para representar los medicamentos del catálogo nacional oficial (LINAME).
 * Almacena los códigos oficiales, nombres, formas farmacéuticas, concentraciones y precios referenciales normados.
 */
class Medicamento extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'medicamentos';

    /**
     * Atributos asignables de forma masiva.
     *
     * @var array
     */
    protected $fillable = [
        'codigo',
        'nombre',
        'forma_farmaceutica',
        'concentracion',
        'precio_referencial',
        'aclaracion',
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
            'activo'             => 'boolean',
            'precio_referencial' => 'decimal:2',
        ];
    }
}