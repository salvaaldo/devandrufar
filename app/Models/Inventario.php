<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Modelo Eloquent para representar los lotes físicos ingresados en el inventario.
 * Asocia productos comerciales con cantidades específicas, códigos de lote y fechas de vencimiento.
 */
class Inventario extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'inventario';

    /**
     * Atributos asignables de forma masiva.
     *
     * @var array
     */
    protected $fillable = [
        'producto_id',
        'lote',
        'cantidad',
        'fecha_vencimiento',
        'fecha_ingreso',
        'estado',
    ];

    /**
     * Definición de conversiones automáticas de atributos.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'fecha_vencimiento' => 'date',
            'fecha_ingreso'     => 'date',
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
     * Calcula dinámicamente el estado del lote (vigente, por vencer, vencido)
     * basándose en la fecha de vencimiento proporcionada en comparación con la fecha actual.
     *
     * Reglas:
     * - Si faltan menos de 0 días (ya pasó la fecha): 'vencido'.
     * - Si falta entre 0 y 90 días inclusive: 'por_vencer'.
     * - Si faltan más de 90 días: 'vigente'.
     *
     * @param string $fechaVencimiento Fecha de vencimiento en formato 'Y-m-d'.
     * @return string Estado resultante ('vencido', 'por_vencer', 'vigente').
     */
    public static function calcularEstado(string $fechaVencimiento): string
    {
        $hoy = Carbon::today();
        $vencimiento = Carbon::parse($fechaVencimiento);

        // Calcular los días de diferencia (valor negativo significa que ya venció)
        $dias = $hoy->diffInDays($vencimiento, false);

        if ($dias < 0) {
            return 'vencido';
        } elseif ($dias <= 90) {
            return 'por_vencer';
        } else {
            return 'vigente';
        }
    }
}

