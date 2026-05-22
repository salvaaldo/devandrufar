<?php

namespace App\Services;

use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class InventarioService
{
    public function listar()
    {
        return Inventario::with('producto')
            ->orderBy('fecha_vencimiento')
            ->paginate(15);
    }

    public function crear(array $datos): Inventario
    {
        $datos['estado'] = Inventario::calcularEstado($datos['fecha_vencimiento']);
        $item = Inventario::create($datos);
        
        // Limpiar caché de alertas
        \Illuminate\Support\Facades\Cache::forget('vencidos_count_global');
        \Illuminate\Support\Facades\Cache::forget('proximos_count_global');
        
        return $item;
    }

    public function eliminar(Inventario $inventario): void
    {
        $inventario->delete();
        
        // Limpiar caché de alertas
        \Illuminate\Support\Facades\Cache::forget('vencidos_count_global');
        \Illuminate\Support\Facades\Cache::forget('proximos_count_global');
    }

    public function obtenerProductos()
    {
        return Producto::orderBy('nombre')->get();
    }

    /**
     * Descuenta stock de un producto usando lógica FIFO (PEPS)
     * Retorna los lotes afectados para el detalle
     */
    public function descontarStockProducto(int $productoId, int $cantidadARestar): array
    {
        $lotes = Inventario::where('producto_id', $productoId)
            ->where('cantidad', '>', 0)
            ->where('estado', '!=', 'vencido')
            ->orderBy('fecha_vencimiento', 'asc')
            ->get();

        $lotesDescontados = [];
        $cantidadRestante = $cantidadARestar;

        foreach ($lotes as $lote) {
            if ($cantidadRestante <= 0) break;

            if ($lote->cantidad >= $cantidadRestante) {
                $lotesDescontados[] = ['id' => $lote->id, 'cantidad' => $cantidadRestante, 'lote' => $lote->lote];
                $lote->decrement('cantidad', $cantidadRestante);
                $cantidadRestante = 0;
            } else {
                $lotesDescontados[] = ['id' => $lote->id, 'cantidad' => $lote->cantidad, 'lote' => $lote->lote];
                $cantidadRestante -= $lote->cantidad;
                $lote->update(['cantidad' => 0]);
            }
        }

        if ($cantidadRestante > 0) {
            throw new \Exception("Stock insuficiente para el producto ID: $productoId. Faltan $cantidadRestante.");
        }

        // Limpiar caché de alertas para actualizar el Dashboard y la Voz
        \Illuminate\Support\Facades\Cache::forget('vencidos_count_global');
        \Illuminate\Support\Facades\Cache::forget('proximos_count_global');

        return $lotesDescontados;
    }

    /**
     * Actualiza el campo 'estado' de todos los registros de inventario
     */
    public function actualizarEstados(): int
    {
        $inventarios = Inventario::all();
        $contador = 0;

        foreach ($inventarios as $item) {
            $nuevoEstado = Inventario::calcularEstado($item->fecha_vencimiento->format('Y-m-d'));
            if ($item->estado !== $nuevoEstado) {
                $item->update(['estado' => $nuevoEstado]);
                $contador++;
            }
        }

        if ($contador > 0) {
            \Illuminate\Support\Facades\Cache::forget('vencidos_count_global');
            \Illuminate\Support\Facades\Cache::forget('proximos_count_global');
        }

        return $contador;
    }
}