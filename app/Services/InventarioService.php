<?php

namespace App\Services;

use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

/**
 * Servicio encargado de gestionar las operaciones del inventario,
 * incluyendo el registro de stock, cálculo de estados de vencimiento,
 * y la deducción de existencias usando el algoritmo FIFO (First In, First Out).
 */
class InventarioService
{
    /**
     * Obtiene el listado paginado del inventario, ordenado por fecha de vencimiento.
     * Carga de forma anticipada la relación con el producto asociado.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function listar()
    {
        return Inventario::with('producto')
            ->orderBy('fecha_vencimiento')
            ->paginate(15);
    }

    /**
     * Registra un nuevo lote de inventario en el sistema.
     * Calcula automáticamente su estado inicial (vigente, por vencer, vencido) según la fecha de vencimiento.
     * Al finalizar, limpia la caché global de alertas para que los paneles muestren datos actualizados.
     *
     * @param array $datos Datos estructurados del lote (lote, cantidad, fecha_vencimiento, producto_id, etc.).
     * @return \App\Models\Inventario El registro del lote creado.
     */
    public function crear(array $datos): Inventario
    {
        // Calcular el estado del lote en base a su fecha de vencimiento antes de guardarlo
        $datos['estado'] = Inventario::calcularEstado($datos['fecha_vencimiento']);
        $item = Inventario::create($datos);
        
        // Limpiar caché de alertas para forzar la actualización en el Dashboard y la voz interactiva
        \Illuminate\Support\Facades\Cache::forget('vencidos_count_global');
        \Illuminate\Support\Facades\Cache::forget('proximos_count_global');
        
        return $item;
    }

    /**
     * Elimina un lote específico del inventario.
     * Limpia la caché de alertas globales para mantener la congruencia de los contadores.
     *
     * @param \App\Models\Inventario $inventario Modelo del registro de inventario a eliminar.
     * @return void
     */
    public function eliminar(Inventario $inventario): void
    {
        $inventario->delete();
        
        // Limpiar la caché tras la eliminación
        \Illuminate\Support\Facades\Cache::forget('vencidos_count_global');
        \Illuminate\Support\Facades\Cache::forget('proximos_count_global');
    }

    /**
     * Obtiene la lista completa de productos ordenados alfabéticamente por su nombre.
     * Útil para poblar selectores en formularios de inventario.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obtenerProductos()
    {
        return Producto::orderBy('nombre')->get();
    }

    /**
     * Descuenta stock de un producto utilizando la lógica FIFO/PEPS (Primero en Entrar, Primero en Salir).
     * Selecciona los lotes activos (con cantidad > 0 y no vencidos) ordenándolos de menor a mayor
     * fecha de vencimiento, consumiendo el stock de los lotes más próximos a vencer primero.
     *
     * @param int $productoId ID único del producto.
     * @param int $cantidadARestar Cantidad total de unidades que se desean descontar.
     * @throws \Exception Si la cantidad acumulada en los lotes disponibles no es suficiente para cubrir la resta.
     * @return array Detalle de los lotes afectados con sus IDs y las cantidades descontadas.
     */
    public function descontarStockProducto(int $productoId, int $cantidadARestar): array
    {
        // Obtener los lotes disponibles ordenados ascendentemente por fecha de vencimiento (los que vencen antes van primero)
        $lotes = Inventario::where('producto_id', $productoId)
            ->where('cantidad', '>', 0)
            ->where('estado', '!=', 'vencido')
            ->orderBy('fecha_vencimiento', 'asc')
            ->get();

        $lotesDescontados = [];
        $cantidadRestante = $cantidadARestar;

        // Recorrer los lotes y descontar la cantidad requerida de manera progresiva
        foreach ($lotes as $lote) {
            if ($cantidadRestante <= 0) {
                break;
            }

            if ($lote->cantidad >= $cantidadRestante) {
                // El lote actual tiene suficiente stock para cubrir todo el remanente
                $lotesDescontados[] = [
                    'id'       => $lote->id,
                    'cantidad' => $cantidadRestante,
                    'lote'     => $lote->lote
                ];
                $lote->decrement('cantidad', $cantidadRestante);
                $cantidadRestante = 0;
            } else {
                // El lote actual no cubre todo, se agota por completo y se continúa con el siguiente lote
                $lotesDescontados[] = [
                    'id'       => $lote->id,
                    'cantidad' => $lote->cantidad,
                    'lote'     => $lote->lote
                ];
                $cantidadRestante -= $lote->cantidad;
                $lote->update(['cantidad' => 0]);
            }
        }

        // Si después de recorrer todos los lotes válidos aún queda cantidad por restar, hay un error de stock insuficiente
        if ($cantidadRestante > 0) {
            throw new \Exception("Stock insuficiente para el producto ID: $productoId. Faltan $cantidadRestante.");
        }

        // Limpiar caché de alertas para actualizar el Dashboard y el sistema de asistencia por voz
        \Illuminate\Support\Facades\Cache::forget('vencidos_count_global');
        \Illuminate\Support\Facades\Cache::forget('proximos_count_global');

        return $lotesDescontados;
    }

    /**
     * Actualiza el campo 'estado' (vigente, por vencer, vencido) de todos los registros de inventario.
     * Compara el estado actual con el calculado según la fecha de vencimiento y el día de hoy.
     *
     * @return int Cantidad de registros que sufrieron una actualización de estado.
     */
    public function actualizarEstados(): int
    {
        $inventarios = Inventario::all();
        $contador = 0;

        foreach ($inventarios as $item) {
            $nuevoEstado = Inventario::calcularEstado($item->fecha_vencimiento->format('Y-m-d'));
            
            // Si el estado ha cambiado, lo actualizamos en la base de datos e incrementamos el contador
            if ($item->estado !== $nuevoEstado) {
                $item->update(['estado' => $nuevoEstado]);
                $contador++;
            }
        }

        // Si al menos un lote cambió de estado, invalidamos la caché global de alertas
        if ($contador > 0) {
            \Illuminate\Support\Facades\Cache::forget('vencidos_count_global');
            \Illuminate\Support\Facades\Cache::forget('proximos_count_global');
        }

        return $contador;
    }
}