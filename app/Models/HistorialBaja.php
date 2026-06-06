<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent para representar el historial de bajas (descartes) de inventario.
 * Almacena los registros de lotes que salieron del sistema por vencimiento, daño u otros motivos sin ser vendidos.
 */
class HistorialBaja extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'historial_bajas';

    /**
     * Atributos asignables de forma masiva.
     *
     * @var array
     */
    protected $fillable = [
        'producto_id',
        'user_id',
        'lote',
        'cantidad',
        'fecha_vencimiento',
        'fecha_ingreso',
        'motivo',
        'observacion',
        'fecha_baja',
    ];

    /**
     * Conversión automática de tipos de atributos.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'fecha_vencimiento' => 'date',
            'fecha_ingreso'     => 'date',
            'fecha_baja'        => 'datetime',
        ];
    }

    /**
     * Relación de pertenencia (muchos a uno) con el modelo de Producto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Relación de pertenencia (muchos a uno) con el usuario administrador u operador que autorizó la baja.
     * Soporta la recuperación de usuarios eliminados mediante SoftDeletes (`withTrashed`).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}