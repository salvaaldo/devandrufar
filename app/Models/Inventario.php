<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventario';

    protected $fillable = [
        'producto_id',
        'lote',
        'cantidad',
        'fecha_vencimiento',
        'fecha_ingreso',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_vencimiento' => 'date',
            'fecha_ingreso'     => 'date',
        ];
    }

    // Relación con producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Calcular estado automáticamente
    public static function calcularEstado(string $fechaVencimiento): string
    {
        $hoy = Carbon::today()->startOfDay();
        $vencimiento = Carbon::parse($fechaVencimiento)->startOfDay();

        $dias = $hoy->diffInDays($vencimiento, false);

        if ($dias < 0) {
            return 'vencido';
        } elseif ($dias <= 120) {
            return 'por_vencer';
        } else {
            return 'vigente';
        }
    }
}
