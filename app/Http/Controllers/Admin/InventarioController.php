<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventarioRequest;
use App\Models\Inventario;
use App\Services\InventarioService;

class InventarioController extends Controller
{
    public function __construct(private InventarioService $inventarioService) {}

    public function index()
    {
        $inventarios = Inventario::with('producto')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.inventario.index', compact('inventarios'));
    }

    public function create()
    {
        $productos = $this->inventarioService->obtenerProductos();
        return view('admin.inventario.create', compact('productos'));
    }

    public function store(StoreInventarioRequest $request)
    {
        $this->inventarioService->crear($request->validated());
        return redirect()->route('inventario.index')
            ->with('success', 'Stock registrado correctamente.');
    }

    public function destroy(Inventario $inventario)
    {
        $this->inventarioService->eliminar($inventario);
        return redirect()->route('inventario.index')
            ->with('success', 'Registro eliminado correctamente.');
    }
}
