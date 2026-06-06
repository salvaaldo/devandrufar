<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Inventario;
use App\Services\InventarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controlador de Cotizaciones.
 * Encargado de registrar las cotizaciones del sistema y orquestar el consumo de stock
 * de lotes mediante el algoritmo FIFO (First In, First Out) a través del InventarioService.
 */
class CotizacionController extends Controller
{
    /**
     * Instancia del servicio de inventario.
     *
     * @var \App\Services\InventarioService
     */
    protected $inventarioService;

    /**
     * Constructor del controlador.
     * Inyecta el servicio de inventario para las deducciones de stock.
     */
    public function __construct(InventarioService $inventarioService)
    {
        $this->inventarioService = $inventarioService;
    }

    /**
     * Muestra la lista paginada de cotizaciones registradas.
     * Admite búsqueda de texto (por número, nombre o cliente) y filtrado por estado.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Cotizacion::with(['cliente', 'user'])
            ->orderBy('created_at', 'desc');

        // Aplicar búsqueda si se recibe el parámetro de búsqueda
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero', 'LIKE', "%{$search}%")
                  ->orWhere('nombre', 'LIKE', "%{$search}%")
                  ->orWhereHas('cliente', fn($c) => $c->where('nombre', 'LIKE', "%{$search}%"));
            });
        }

        // Aplicar filtro por estado de cotización (ej. activa, anulada)
        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        }

        $cotizaciones = $query->paginate(15)->withQueryString();

        return view('admin.cotizaciones.index', compact('cotizaciones'));
    }

    /**
     * Muestra el formulario para crear una nueva cotización.
     * Obtiene los clientes activos y solo los productos comerciales que poseen stock disponible y no vencido.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $clientes = Cliente::where('activo', true)->orderBy('nombre')->get();
        
        // Cargar únicamente productos con stock real activo y lotes no vencidos
        $productos = Producto::whereHas('inventarios', function($q) {
            $q->where('cantidad', '>', 0)
              ->where('estado', '!=', 'vencido');
        })->orderBy('nombre')->get();

        return view('admin.cotizaciones.create', compact('clientes', 'productos'));
    }

    /**
     * Almacena una nueva cotización en la base de datos.
     * Ejecuta una transacción para asegurar que la cotización, sus detalles y el descuento
     * progresivo FIFO de inventario ocurran de forma atómica y consistente.
     *
     * @param \Illuminate\Http\Request $request Petición con el cliente, número de cotización e ítems.
     * @return \Illuminate\Http\JsonResponse JSON con el estado de éxito e ID de la cotización creada.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario de cotización y estructura de ítems
        $request->validate([
            'numero'                  => 'required|unique:cotizaciones,numero',
            'cliente_id'              => 'required|exists:clientes,id',
            'items'                   => 'required|array|min:1',
            'items.*.producto_id'     => 'required|exists:productos,id',
            'items.*.cantidad'        => 'required|integer|min:1',
            'items.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            // 1. Crear el registro base de la cotización
            $cotizacion = Cotizacion::create([
                'numero'     => $request->numero,
                'nombre'     => $request->numero, // Se utiliza el número identificador como nombre
                'cliente_id' => $request->cliente_id,
                'user_id'    => auth()->id(),
                'total'      => 0,
            ]);

            $total = 0;

            // 2. Procesar cada ítem del detalle
            foreach ($request->items as $nro => $item) {
                $precioTotal = $item['cantidad'] * $item['precio_unitario'];
                $total += $precioTotal;

                // ── DEDUCCIÓN EN CASCADA FIFO (PEPS) DESDE EL SERVICIO ───────────
                // Descuenta unidades de los lotes ordenados por vencimiento más cercano
                $lotesDescontados = $this->inventarioService->descontarStockProducto(
                    $item['producto_id'], 
                    $item['cantidad']
                );

                // Crear el detalle de cotización guardando el mapeo de lotes afectados
                CotizacionDetalle::create([
                    'cotizacion_id'     => $cotizacion->id,
                    'producto_id'       => $item['producto_id'],
                    'inventario_id'     => null, // Obsoleto por el manejo múltiple de lotes en lotes_descontados
                    'lote'              => $item['lote'] ?? 'S/L',
                    'lotes_descontados' => $lotesDescontados, // Se almacena como array JSON en BD
                    'nro_item'          => $nro + 1,
                    'cantidad'          => $item['cantidad'],
                    'precio_unitario'   => $item['precio_unitario'],
                    'precio_total'      => $precioTotal,
                ]);
            }

            // 3. Actualizar el total final calculado de la cotización
            $cotizacion->update(['total' => $total]);

            return response()->json([
                'success' => true,
                'id'      => $cotizacion->id,
            ]);
        });
    }

    /**
     * Muestra el detalle de una cotización específica.
     *
     * @param int $id ID de la cotización.
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $cotizacion = Cotizacion::with(['cliente', 'user', 'detalles.producto', 'detalles.inventario'])
            ->findOrFail($id);

        return view('admin.cotizaciones.show', compact('cotizacion'));
    }

    /**
     * Retorna los lotes activos disponibles para un producto comercial.
     * Se ordena de menor a mayor fecha de vencimiento.
     * Útil para peticiones AJAX de autocompletado en el formulario.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lotesDisponibles(Request $request)
    {
        $productoId = $request->producto_id;

        $lotes = Inventario::where('producto_id', $productoId)
            ->where('cantidad', '>', 0)
            ->where('estado', '!=', 'vencido')
            ->orderBy('fecha_vencimiento', 'asc')
            ->get(['id', 'lote', 'cantidad', 'fecha_vencimiento', 'estado']);

        return response()->json($lotes);
    }

    /**
     * Endpoint de búsqueda de lotes dinámicos por término de texto.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarLotes(Request $request)
    {
        $term = $request->term;
        $productoId = $request->producto_id;

        $lotes = \App\Models\Inventario::where('producto_id', $productoId)
            ->where('lote', 'LIKE', "%{$term}%")
            ->where('cantidad', '>', 0)
            ->get(['lote', 'fecha_vencimiento', 'cantidad']);

        return response()->json($lotes);
    }

    /**
     * Elimina físicamente una cotización y sus detalles asociados en base de datos.
     *
     * @param int $id ID de la cotización.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $cotizacion = Cotizacion::findOrFail($id);
            $cotizacion->detalles()->delete();
            $cotizacion->delete();

            return response()->json(['success' => true]);
        });
    }

    /**
     * Anula una cotización y reversa el stock consumido.
     * Devuelve las unidades restadas a sus respectivos lotes de origen utilizando
     * la columna 'lotes_descontados' del historial del detalle.
     *
     * @param int $id ID de la cotización a anular.
     * @return \Illuminate\Http\JsonResponse
     */
    public function anular($id)
    {
        return DB::transaction(function () use ($id) {
            $cotizacion = Cotizacion::with('detalles')->findOrFail($id);

            if ($cotizacion->estado === 'anulada') {
                return response()->json(['success' => false, 'error' => 'La cotización ya está anulada.']);
            }

            // Devolver stock a los lotes originales de cada ítem cotizado
            foreach ($cotizacion->detalles as $detalle) {
                if (!empty($detalle->lotes_descontados) && is_array($detalle->lotes_descontados)) {
                    foreach ($detalle->lotes_descontados as $loteDesc) {
                        $inventario = Inventario::find($loteDesc['id']);
                        if ($inventario) {
                            $inventario->increment('cantidad', $loteDesc['cantidad']);
                        }
                    }
                }
            }

            // Cambiar el estado de la cotización a 'anulada'
            $cotizacion->update(['estado' => 'anulada']);

            return response()->json(['success' => true]);
        });
    }

    /**
     * Retorna la suma total de stock disponible (no vencido) de un producto.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stockTotal(Request $request)
    {
        $productoId = $request->producto_id;

        $stock = Inventario::where('producto_id', $productoId)
            ->where('cantidad', '>', 0)
            ->where('estado', '!=', 'vencido')
            ->sum('cantidad');

        return response()->json(['stock' => $stock]);
    }

    /**
     * Genera y transmite el PDF de la cotización formateada para su impresión o descarga.
     *
     * @param int $id ID de la cotización.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pdf($id)
    {
        $cotizacion = Cotizacion::with(['cliente', 'user', 'detalles.producto'])
            ->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.cotizaciones.pdf', compact('cotizacion'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream('cotizacion-' . $cotizacion->numero . '.pdf');
    }
}

