<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deteccion extends Model
{
    use HasFactory;

    protected $table = 'detecciones';

    protected $fillable = [
        'nombre_detectado',
        'fecha_detectada',
        'estado',
        'user_id',
        'lote',

    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}