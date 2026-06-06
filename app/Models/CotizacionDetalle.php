<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent para representar los elementos o ítems individuales dentro de una cotización.
 * Registra el producto cotizado, cantidad, precios unitario/total y el desglose de los lotes FIFO afectados.
 */
class CotizacionDetalle extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'cotizacion_detalles';

    /**
     * Atributos asignables de forma masiva.
     *
     * @var array
     */
    protected $fillable = [
        'cotizacion_id',
        'producto_id',
        'inventario_id',
        'lote',
        'lotes_descontados',
        'nro_item',
        'cantidad',
        'precio_unitario',
        'precio_total',
    ];

    /**
     * Conversión automática de tipos de atributos.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'precio_unitario'   => 'decimal:2',
            'precio_total'      => 'decimal:2',
            'lotes_descontados' => 'array', // Almacena el JSON de lotes FIFO descontados como array de PHP
        ];
    }

    /**
     * Relación de pertenencia (muchos a uno) con el modelo de Producto comercial.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Relación de pertenencia (muchos a uno) con el modelo de Inventario (lote primario afectado).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }

    /**
     * Atributo dinámico (Accesor) para obtener una representación legible del lote del ítem.
     * Retorna el campo 'lote' directamente si está definido, de lo contrario acude al lote de la relación
     * de inventario. Si ninguno existe, retorna 'S/L' (Sin Lote).
     *
     * Acceso mediante: $detalle->lote_display
     *
     * @return string
     */
    public function getLoteDisplayAttribute()
    {
        return $this->lote ?? ($this->inventario ? $this->inventario->lote : 'S/L');
    }
}

