<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialBaja extends Model
{
    use HasFactory;

    protected $table = 'historial_bajas';

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

    protected function casts(): array
    {
        return [
            'fecha_vencimiento' => 'date',
            'fecha_ingreso'     => 'date',
            'fecha_baja'        => 'datetime',
        ];
    }

    // Relación con producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}