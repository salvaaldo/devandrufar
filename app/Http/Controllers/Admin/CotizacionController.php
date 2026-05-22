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

class CotizacionController extends Controller
{
    protected $inventarioService;

    public function __construct(InventarioService $inventarioService)
    {
        $this->inventarioService = $inventarioService;
    }

    // Lista de cotizaciones
    public function index(Request $request)
    {
        $query = Cotizacion::with(['cliente', 'user'])
            ->orderBy('created_at', 'desc');

        // Búsqueda por número, nombre o cliente
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero', 'LIKE', "%{$search}%")
                  ->orWhere('nombre', 'LIKE', "%{$search}%")
                  ->orWhereHas('cliente', fn($c) => $c->where('nombre', 'LIKE', "%{$search}%"));
            });
        }

        // Filtro por estado
        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        }

        $cotizaciones = $query->paginate(15)->withQueryString();

        return view('admin.cotizaciones.index', compact('cotizaciones'));
    }

    // Formulario nueva cotización
    public function create()
    {
        $clientes = Cliente::where('activo', true)->orderBy('nombre')->get();
        
        // Solo enviamos a la vista los productos que tienen stock > 0 y no están vencidos
        $productos = Producto::whereHas('inventarios', function($q) {
            $q->where('cantidad', '>', 0)
              ->where('estado', '!=', 'vencido');
        })->orderBy('nombre')->get();

        return view('admin.cotizaciones.create', compact('clientes', 'productos'));
    }

    // Guardar cotización
    public function store(Request $request)
    {
        $request->validate([
            'numero'               => 'required|unique:cotizaciones,numero',
            'cliente_id'           => 'required|exists:clientes,id',
            'items'                => 'required|array|min:1',
            'items.*.producto_id'  => 'required|exists:productos,id',
            'items.*.cantidad'     => 'required|integer|min:1',
            'items.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            $cotizacion = Cotizacion::create([
                'numero'     => $request->numero,
                'nombre'     => $request->numero, // usamos el numero como nombre
                'cliente_id' => $request->cliente_id,
                'user_id'    => auth()->id(),
                'total'      => 0,
            ]);

            $total = 0;

            foreach ($request->items as $nro => $item) {
                $precioTotal = $item['cantidad'] * $item['precio_unitario'];
                $total += $precioTotal;

                // ── DESCUENTO FIFO (PEPS) DESDE EL SERVICIO ───────────
                $lotesDescontados = $this->inventarioService->descontarStockProducto(
                    $item['producto_id'], 
                    $item['cantidad']
                );

                CotizacionDetalle::create([
                    'cotizacion_id'     => $cotizacion->id,
                    'producto_id'       => $item['producto_id'],
                    'inventario_id'     => null, // Ahora usamos lotes_descontados
                    'lote'              => $item['lote'] ?? 'S/L',
                    'lotes_descontados' => $lotesDescontados,
                    'nro_item'          => $nro + 1,
                    'cantidad'          => $item['cantidad'],
                    'precio_unitario'   => $item['precio_unitario'],
                    'precio_total'      => $precioTotal,
                ]);
            }

            $cotizacion->update(['total' => $total]);

            return response()->json([
                'success' => true,
                'id'      => $cotizacion->id,
            ]);
        });
    }

    // Ver cotización
    public function show($id)
    {
        $cotizacion = Cotizacion::with(['cliente', 'user', 'detalles.producto', 'detalles.inventario'])
            ->findOrFail($id);

        return view('admin.cotizaciones.show', compact('cotizacion'));
    }

    // Buscar lotes disponibles de un producto
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

    public function buscarLotes(Request $request)
    {
        $term = $request->term;
        $productoId = $request->producto_id;

        // Buscamos lotes que coincidan con lo que el usuario escribe
        $lotes = \App\Models\Inventario::where('producto_id', $productoId)
            ->where('lote', 'LIKE', "%{$term}%")
            ->where('cantidad', '>', 0) // Solo lotes con stock
            ->get(['lote', 'fecha_vencimiento', 'cantidad']);

        return response()->json($lotes);
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $cotizacion = Cotizacion::findOrFail($id);
            $cotizacion->detalles()->delete();
            $cotizacion->delete();

            return response()->json(['success' => true]);
        });
    }

    public function anular($id)
    {
        return DB::transaction(function () use ($id) {
            $cotizacion = Cotizacion::with('detalles')->findOrFail($id);

            if ($cotizacion->estado === 'anulada') {
                return response()->json(['success' => false, 'error' => 'La cotización ya está anulada.']);
            }

            // Devolver stock a los lotes originales
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

            $cotizacion->update(['estado' => 'anulada']);

            return response()->json(['success' => true]);
        });
    }

    public function stockTotal(Request $request)
    {
        $productoId = $request->producto_id;

        $stock = Inventario::where('producto_id', $productoId)
            ->where('cantidad', '>', 0)
            ->where('estado', '!=', 'vencido')
            ->sum('cantidad');

        return response()->json(['stock' => $stock]);
    }

    public function pdf($id)
    {
        $cotizacion = Cotizacion::with(['cliente', 'user', 'detalles.producto'])
            ->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.cotizaciones.pdf', compact('cotizacion'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream('cotizacion-' . $cotizacion->numero . '.pdf');
    }
}
