<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Producto;
use App\Services\ProductoService;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function __construct(private ProductoService $productoService)
    {
    }

    public function index()
    {
        $productos = $this->productoService->listar();
        return view('admin.productos.index', compact('productos'));
    }

    public function create()
    {
        return view('admin.productos.create');
    }

    public function store(StoreProductoRequest $request)
    {
        $this->productoService->crear($request->validated());
        return redirect()->route('productos.index')
            ->with('success', 'Producto registrado correctamente.');
    }

    public function edit(Producto $producto)
    {
        return view('admin.productos.edit', compact('producto'));
    }

    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $this->productoService->actualizar($producto, $request->validated());
        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $this->productoService->eliminar($producto);
        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    // Endpoint AJAX para buscar medicamento por ID
    public function buscarMedicamento(Request $request)
    {
        $medicamento = $this->productoService->buscarMedicamento($request->id);
        if ($medicamento) {
            return response()->json($medicamento);
        }
        return response()->json(null, 404);
    }
}