<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotizacionDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'cotizacion_id',
        'producto_id',
        'inventario_id',
        'lote', // <--- AGREGA ESTA LÍNEA
        'nro_item',
        'cantidad',
        'precio_unitario',
        'precio_total',
    ];

    protected function casts(): array
    {
        return [
            'precio_unitario' => 'decimal:2',
            'precio_total'    => 'decimal:2',
        ];
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }
    // Agrega esto al final de tu modelo CotizacionDetalle.php
    public function getLoteDisplayAttribute()
    {
        // Si hay un texto en 'lote', úsalo; si no, intenta sacarlo del inventario
        return $this->lote ?? ($this->inventario ? $this->inventario->lote : 'S/L');
    }
}
