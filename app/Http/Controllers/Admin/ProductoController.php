<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Producto;
use App\Services\ProductoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function __construct(private ProductoService $productoService)
    {
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Producto::with('medicamento')->orderBy('nombre');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('codigo', 'LIKE', "%{$search}%")
                  ->orWhere('marca', 'LIKE', "%{$search}%");
            });
        }

        $productos = $query->paginate(15)->withQueryString();
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

    public function pdf()
    {
        $productos = Producto::with('medicamento')->orderBy('nombre')->get();

        $pdf = Pdf::loadView('admin.productos.pdf', compact('productos'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('catalogo-productos-' . now()->format('d-m-Y') . '.pdf');
    }
}