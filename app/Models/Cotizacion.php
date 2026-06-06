<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent para representar las cotizaciones de productos realizadas a los clientes.
 * Guarda el folio correlativo de cotización, el cliente asignado, el operador que la creó y el monto total.
 */
class Cotizacion extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'cotizaciones';

    /**
     * Atributos asignables de forma masiva.
     *
     * @var array
     */
    protected $fillable = [
        'numero',
        'nombre',
        'cliente_id',
        'user_id',
        'total',
        'estado',
        'observacion',
    ];

    /**
     * Conversión automática de tipos de atributos.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
        ];
    }

    /**
     * Relación de pertenencia (muchos a uno) con el modelo de Cliente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación de pertenencia (muchos a uno) con el usuario (operador/admin) que generó la cotización.
     * Permite incluir usuarios eliminados mediante SoftDeletes (`withTrashed`).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Relación uno a muchos con el detalle de los productos incluidos en la cotización.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalles()
    {
        return $this->hasMany(CotizacionDetalle::class);
    }

    /**
     * Genera automáticamente el siguiente número correlativo secuencial para una cotización.
     * Formatea el número autoincremental rellenándolo con ceros a la izquierda hasta completar 7 dígitos.
     * Ejemplo: si el último número es '0000005', el siguiente será '0000006'.
     *
     * @return string Número correlativo de cotización generado.
     */
    public static function generarNumero(): string
    {
        $ultimo = self::orderBy('id', 'desc')->first();
        $numero = $ultimo ? (intval($ultimo->numero) + 1) : 1;
        return str_pad($numero, 7, '0', STR_PAD_LEFT);
    }
}