<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent para representar los registros de lecturas o detecciones OCR.
 * Almacena las lecturas en bruto y los metadatos obtenidos al escanear imágenes de empaques de medicamentos.
 */
class Deteccion extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'detecciones';

    /**
     * Atributos asignables de forma masiva.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_detectado',
        'fecha_detectada',
        'estado',
        'user_id',
        'lote',
    ];

    /**
     * Relación de pertenencia (muchos a uno) con el usuario operador que inició el escaneo OCR.
     * Soporta usuarios eliminados mediante SoftDeletes (`withTrashed`).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}