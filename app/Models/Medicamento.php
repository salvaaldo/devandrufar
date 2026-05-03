<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'codigo',
        'nombre',
        'forma_farmaceutica',
        'concentracion',
        'precio_referencial',
        'aclaracion',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo'             => 'boolean',
            'precio_referencial' => 'decimal:2',
        ];
    }
}