<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventarioRequest;
use App\Models\Inventario;
use App\Services\InventarioService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function __construct(private InventarioService $inventarioService) {}

    public function index(Request $request)
    {
        $search = $request->input('search');
        $estado = $request->input('estado');

        $query = Inventario::with('producto')->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('lote', 'LIKE', "%{$search}%")
                  ->orWhereHas('producto', function($pq) use ($search) {
                      $pq->where('nombre', 'LIKE', "%{$search}%")
                         ->orWhere('codigo', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($estado) {
            $query->where('estado', $estado);
        }

        $inventarios = $query->paginate(20)->withQueryString();

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

    public function pdf()
    {
        // Obtenemos los productos que tienen al menos un registro en inventario
        // y sumamos su cantidad total
        $productos = \App\Models\Producto::withSum('inventarios as stock_total', 'cantidad')
            ->has('inventarios')
            ->orderBy('nombre')
            ->get();

        $pdf = Pdf::loadView('admin.inventario.pdf', compact('productos'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('reporte-stock-general-' . now()->format('d-m-Y') . '.pdf');
    }
}
