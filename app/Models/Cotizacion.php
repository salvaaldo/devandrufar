<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;
    protected $table = 'cotizaciones';

    protected $fillable = [
        'numero',
        'nombre',
        'cliente_id',
        'user_id',
        'total',
        'estado',
        'observacion',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
        ];
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function detalles()
    {
        return $this->hasMany(CotizacionDetalle::class);
    }

    // Generar número automático 000001
    public static function generarNumero(): string
    {
        $ultimo = self::orderBy('id', 'desc')->first();
        // Ya no hay prefijo "COT-", así que convertimos a entero directamente
        $numero = $ultimo ? (intval($ultimo->numero) + 1) : 1;
        return str_pad($numero, 7, '0', STR_PAD_LEFT);
    }
}