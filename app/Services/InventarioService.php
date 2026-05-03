<?php

namespace App\Services;

use App\Models\Inventario;
use App\Models\Producto;

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
        return Inventario::create($datos);
    }

    public function eliminar(Inventario $inventario): void
    {
        $inventario->delete();
    }

    public function obtenerProductos()
    {
        return Producto::orderBy('nombre')->get();
    }
}