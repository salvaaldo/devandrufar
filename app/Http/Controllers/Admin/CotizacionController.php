<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Inventario;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    // Lista de cotizaciones
    public function index()
    {
        $cotizaciones = Cotizacion::with(['cliente', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.cotizaciones.index', compact('cotizaciones'));
    }

    // Formulario nueva cotización
    public function create()
    {
        $numero   = Cotizacion::generarNumero();
        $clientes = Cliente::where('activo', true)->orderBy('nombre')->get();
        $productos = Producto::orderBy('nombre')->get();

        return view('admin.cotizaciones.create', compact('numero', 'clientes', 'productos'));
    }

    // Guardar cotización
    public function store(Request $request)
    {
        $request->validate([
            'numero'     => 'required|unique:cotizaciones,numero',
            'cliente_id' => 'required|exists:clientes,id',
            'items'      => 'required|array|min:1',
        ]);

        try {
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

                CotizacionDetalle::create([
                    'cotizacion_id'   => $cotizacion->id,
                    'producto_id'     => $item['producto_id'],
                    'inventario_id'   => !empty($item['inventario_id']) ? $item['inventario_id'] : null,
                    'lote'            => $item['lote'] ?? 'S/L',
                    'nro_item'        => $nro + 1,
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'precio_total'    => $precioTotal,
                ]);

                // ── DESCUENTO FIFO DEL INVENTARIO ──────────────────────
                $cantidadRestante = $item['cantidad'];
                $productoId = $item['producto_id'];

                // Traemos los lotes del producto ordenados por fecha de ingreso (FIFO)
                $lotes = Inventario::where('producto_id', $productoId)
                    ->where('cantidad', '>', 0)
                    ->where('estado', '!=', 'vencido')
                    ->orderBy('created_at', 'asc')
                    ->get();

                foreach ($lotes as $lote) {
                    if ($cantidadRestante <= 0) break;

                    if ($lote->cantidad >= $cantidadRestante) {
                        // Este lote tiene suficiente stock
                        $lote->decrement('cantidad', $cantidadRestante);
                        $cantidadRestante = 0;
                    } else {
                        // Este lote no alcanza, lo vaciamos y seguimos al siguiente
                        $cantidadRestante -= $lote->cantidad;
                        $lote->update(['cantidad' => 0]);
                    }
                }
                // ── FIN FIFO ────────────────────────────────────────────
            }

            $cotizacion->update(['total' => $total]);

            return response()->json([
                'success' => true,
                'id'      => $cotizacion->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
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
        try {
            $cotizacion = Cotizacion::findOrFail($id);
            $cotizacion->detalles()->delete();
            $cotizacion->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
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
