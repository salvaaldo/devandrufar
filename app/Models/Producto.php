<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Eloquent para representar los productos comerciales o de farmacia del sistema.
 * Agrupa la información comercial (marca, origen) y sincroniza datos clave con un medicamento oficial de LINAME.
 */
class Producto extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'productos';

    /**
     * Atributos asignables de forma masiva.
     *
     * @var array
     */
    protected $fillable = [
        'codigo',
        'medicamento_id',
        'nombre',
        'forma_farmaceutica',
        'concentracion',
        'precio_referencial',
        'origen',
        'marca',
    ];

    /**
     * Definición de conversiones automáticas de atributos.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'precio_referencial' => 'decimal:2',
        ];
    }

    /**
     * Relación de pertenencia (muchos a uno) con el catálogo oficial de Medicamento (LINAME).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class);
    }

    /**
     * Relación uno a muchos con los lotes de inventario ingresados para este producto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventarios()
    {
        return $this->hasMany(Inventario::class);
    }
}