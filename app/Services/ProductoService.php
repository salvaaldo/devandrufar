<?php

namespace App\Services;

use App\Models\Producto;
use App\Models\Medicamento;

class ProductoService
{
    public function listar()
    {
        return Producto::with('medicamento')->orderBy('nombre')->paginate(15);
    }

    public function crear(array $datos): Producto
    {
        // Jalar datos del medicamento LINAME
        $medicamento = Medicamento::findOrFail($datos['medicamento_id']);
        $datos['nombre']             = $medicamento->nombre;
        $datos['forma_farmaceutica'] = $medicamento->forma_farmaceutica;
        $datos['concentracion']      = $medicamento->concentracion;
        $datos['precio_referencial'] = $medicamento->precio_referencial;

        return Producto::create($datos);
    }

    public function actualizar(Producto $producto, array $datos): Producto
    {
        // Si cambió el medicamento, actualizar datos jalados del LINAME
        if ($datos['medicamento_id'] != $producto->medicamento_id) {
            $medicamento = Medicamento::findOrFail($datos['medicamento_id']);
            $datos['nombre']             = $medicamento->nombre;
            $datos['forma_farmaceutica'] = $medicamento->forma_farmaceutica;
            $datos['concentracion']      = $medicamento->concentracion;
            $datos['precio_referencial'] = $medicamento->precio_referencial;
        }

        $producto->update($datos);
        return $producto;
    }

    public function eliminar(Producto $producto): void
    {
        $producto->delete();
    }

    public function buscarMedicamento(int $id): ?Medicamento
    {
        return Medicamento::find($id);
    }
}