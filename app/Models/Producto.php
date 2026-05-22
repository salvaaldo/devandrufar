<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

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

    protected function casts(): array
    {
        return [
            'precio_referencial' => 'decimal:2',
        ];
    }

    // Relación con medicamento LINAME
    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class);
    }

    // Relación con inventarios (lotes)
    public function inventarios()
    {
        return $this->hasMany(Inventario::class);
    }
}