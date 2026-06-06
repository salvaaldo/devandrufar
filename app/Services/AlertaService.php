<?php

namespace App\Services;

use App\Models\Inventario;
use Carbon\Carbon;

/**
 * Servicio encargado de gestionar y consultar las alertas de inventario.
 * Se enfoca principalmente en la detección de productos vencidos o próximos a vencer.
 */
class AlertaService
{
    /**
     * Obtiene el listado de todos los lotes de inventario que ya han vencido.
     * Carga de forma anticipada (eager loading) la relación con el producto asociado.
     *
     * @return \Illuminate\Database\Eloquent\Collection Listado de lotes vencidos ordenados por fecha de vencimiento.
     */
    public function vencidos()
    {
        return Inventario::with('producto')
            ->where('estado', 'vencido')
            ->orderBy('fecha_vencimiento')
            ->get();
    }

    /**
     * Obtiene el listado de todos los lotes de inventario próximos a vencer (dentro del rango de 90 días).
     * Carga de forma anticipada la relación con el producto asociado.
     *
     * @return \Illuminate\Database\Eloquent\Collection Listado de lotes por vencer ordenados por fecha de vencimiento.
     */
    public function porVencer()
    {
        return Inventario::with('producto')
            ->where('estado', 'por_vencer')
            ->orderBy('fecha_vencimiento')
            ->get();
    }

    /**
     * Retorna un resumen numérico del total de alertas activas en el sistema.
     *
     * @return array Arreglo asociativo con las claves 'vencidos' y 'por_vencer' y sus respectivos conteos.
     */
    public function contarAlertas(): array
    {
        return [
            'vencidos'   => Inventario::where('estado', 'vencido')->count(),
            'por_vencer' => Inventario::where('estado', 'por_vencer')->count(),
        ];
    }

    /**
     * Recorre todo el inventario activo para evaluar y actualizar el campo 'estado' de cada lote.
     * Si se detectan cambios de estado en algún lote, se limpia la caché global de alertas
     * para que la interfaz de usuario (Dashboard y sistema de voz) muestre los datos reales.
     *
     * @return void
     */
    public function actualizarEstados(): void
    {
        // Recuperar todos los registros de inventario
        $inventarios = Inventario::all();
        $cambios = false;

        // Evaluar individualmente la fecha de vencimiento de cada lote
        foreach ($inventarios as $item) {
            $nuevoEstado = Inventario::calcularEstado($item->fecha_vencimiento->format('Y-m-d'));
            
            // Si el estado calculado difiere del estado actual almacenado, se actualiza la base de datos
            if ($item->estado !== $nuevoEstado) {
                $item->update(['estado' => $nuevoEstado]);
                $cambios = true;
            }
        }

        // Limpiar la caché si hubo modificaciones para forzar la recarga de datos frescos
        if ($cambios) {
            \Illuminate\Support\Facades\Cache::forget('vencidos_count_global');
            \Illuminate\Support\Facades\Cache::forget('proximos_count_global');
        }
    }
}