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
        return $this->belongsTo(User::class);
    }

    public function detalles()
    {
        return $this->hasMany(CotizacionDetalle::class);
    }

    // Generar número automático COT-000001
    public static function generarNumero(): string
    {
        $ultimo = self::orderBy('id', 'desc')->first();
        $numero = $ultimo ? (intval(substr($ultimo->numero, 4)) + 1) : 1;
        return 'COT-' . str_pad($numero, 6, '0', STR_PAD_LEFT);
    }
}