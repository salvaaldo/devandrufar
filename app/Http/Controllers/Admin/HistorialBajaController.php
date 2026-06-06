<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistorialBaja;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controlador de Historial de Bajas.
 * Permite consultar la bitácora histórica de productos y lotes que han sido descartados
 * del inventario activo por motivos de caducidad o daños, y descargar reportes PDF.
 */
class HistorialBajaController extends Controller
{
    /**
     * Muestra la lista paginada de las bajas de inventario registradas.
     * Permite filtrar los registros por texto (código de lote o nombre del producto comercial).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Consultar bajas cargando relaciones con producto y el usuario que autorizó
        $query = HistorialBaja::with(['producto', 'user'])
            ->orderBy('fecha_baja', 'desc');

        // Búsqueda aproximada en lote o producto
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('lote', 'LIKE', "%{$search}%")
                  ->orWhereHas('producto', function($pq) use ($search) {
                      $pq->where('nombre', 'LIKE', "%{$search}%");
                  });
            });
        }

        $bajas = $query->paginate(15)->withQueryString();

        return view('admin.historial_bajas.index', compact('bajas'));
    }

    /**
     * Genera e inicia la descarga de un documento PDF que contiene todo el historial
     * acumulado de bajas del sistema, formateado de forma apaisada (landscape) A4.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pdf()
    {
        $bajas = HistorialBaja::with(['producto', 'user'])
            ->orderBy('fecha_baja', 'desc')
            ->get();

        $pdf = Pdf::loadView('admin.historial_bajas.pdf', compact('bajas'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('reporte-bajas-' . now()->format('d-m-Y') . '.pdf');
    }
}