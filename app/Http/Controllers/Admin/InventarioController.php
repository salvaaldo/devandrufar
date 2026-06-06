<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventarioRequest;
use App\Models\Inventario;
use App\Services\InventarioService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

/**
 * Controlador de Inventario.
 * Gestiona el stock físico de lotes (ingresos, consultas de inventario, bajas directas
 * y la exportación del reporte de stock consolidado).
 */
class InventarioController extends Controller
{
    /**
     * Constructor del controlador.
     * Inyecta el servicio de inventario para las operaciones de negocio.
     */
    public function __construct(private InventarioService $inventarioService) {}

    /**
     * Muestra la lista paginada del inventario de lotes.
     * Permite buscar por código de lote, nombre del producto o código de barras, así como filtrar por estado (vigente, por vencer, vencido).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $estado = $request->input('estado');

        $query = Inventario::with('producto')->orderBy('created_at', 'desc');

        // Aplicar búsqueda sobre lote o relaciones del producto
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('lote', 'LIKE', "%{$search}%")
                  ->orWhereHas('producto', function($pq) use ($search) {
                      $pq->where('nombre', 'LIKE', "%{$search}%")
                         ->orWhere('codigo', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Aplicar filtro por estado de vencimiento del lote
        if ($estado) {
            $query->where('estado', $estado);
        }

        $inventarios = $query->paginate(20)->withQueryString();

        return view('admin.inventario.index', compact('inventarios'));
    }

    /**
     * Muestra el formulario de registro de un nuevo lote físico al almacén.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $productos = $this->inventarioService->obtenerProductos();
        return view('admin.inventario.create', compact('productos'));
    }

    /**
     * Almacena un nuevo lote de producto e inicializa su estado según la fecha de vencimiento.
     *
     * @param \App\Http\Requests\StoreInventarioRequest $request Datos validados del formulario de ingreso.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreInventarioRequest $request)
    {
        $this->inventarioService->crear($request->validated());
        return redirect()->route('inventario.index')
            ->with('success', 'Stock registrado correctamente.');
    }

    /**
     * Elimina físicamente un lote del inventario activo.
     *
     * @param \App\Models\Inventario $inventario Lote a eliminar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Inventario $inventario)
    {
        $this->inventarioService->eliminar($inventario);
        return redirect()->route('inventario.index')
            ->with('success', 'Registro eliminado correctamente.');
    }

    /**
     * Genera y transmite el PDF de stock general consolidado de productos comerciales.
     * Suma las cantidades físicas disponibles sumando todos los lotes de cada producto.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pdf()
    {
        // Obtener productos comerciales que tengan existencias cargadas en inventario
        $productos = \App\Models\Producto::withSum('inventarios as stock_total', 'cantidad')
            ->has('inventarios')
            ->orderBy('nombre')
            ->get();

        $pdf = Pdf::loadView('admin.inventario.pdf', compact('productos'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('reporte-stock-general-' . now()->format('d-m-Y') . '.pdf');
    }
}

