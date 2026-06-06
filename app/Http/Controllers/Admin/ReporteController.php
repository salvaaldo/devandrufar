<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\Cotizacion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controlador de Reportes y Estadísticas.
 * Administra las consultas financieras, estadísticas operativas de usuarios
 * y exportación de reportes PDF de ventas e inventario vencido.
 */
class ReporteController extends Controller
{
    /**
     * Genera y transmite el PDF de productos vencidos en el inventario.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function vencidosPdf()
    {
        $vencidos = Inventario::with('producto')
            ->where('estado', 'vencido')
            ->orderBy('fecha_vencimiento')
            ->get();

        $pdf = Pdf::loadView('admin.reportes.vencidos_pdf', compact('vencidos'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('reporte-vencidos-' . now()->format('d-m-Y') . '.pdf');
    }

    /**
     * Muestra las estadísticas de ventas/cotizaciones de un periodo y estadísticas de desempeño de usuarios.
     * Calcula ventas totales filtrando las cotizaciones anuladas.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function ventas(Request $request)
    {
        $mes = $request->input('mes', now()->month);
        $anio = $request->input('anio', now()->year);

        // Consultar ventas en el periodo de mes/año indicado (excluyendo cotizaciones anuladas)
        $query = Cotizacion::with(['cliente', 'user'])
            ->whereYear('created_at', $anio)
            ->whereMonth('created_at', $mes)
            ->where('estado', '!=', 'anulada')
            ->orderBy('created_at', 'desc');

        $ventas = $query->get();
        $totalGeneral = $ventas->sum('total');

        // Obtener estadísticas de cotizaciones exitosas por usuario (Día, Semana, Mes actual)
        $usuariosStats = \App\Models\User::withCount([
            'cotizaciones as total_dia' => function ($q) {
                $q->whereDate('created_at', now()->toDateString())->where('estado', '!=', 'anulada');
            },
            'cotizaciones as total_semana' => function ($q) {
                $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('estado', '!=', 'anulada');
            },
            'cotizaciones as total_mes' => function ($q) {
                $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->where('estado', '!=', 'anulada');
            }
        ])->get();

        return view('admin.reportes.ventas', compact('ventas', 'totalGeneral', 'mes', 'anio', 'usuariosStats'));
    }

    /**
     * Genera e inicia la transmisión de un reporte en PDF detallando las ventas de un mes/año seleccionado.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ventasPdf(Request $request)
    {
        $mes = $request->input('mes', now()->month);
        $anio = $request->input('anio', now()->year);

        $ventas = Cotizacion::with(['cliente', 'user'])
            ->whereYear('created_at', $anio)
            ->whereMonth('created_at', $mes)
            ->where('estado', '!=', 'anulada')
            ->orderBy('created_at', 'asc')
            ->get();

        $totalGeneral = $ventas->sum('total');

        $pdf = Pdf::loadView('admin.reportes.ventas_pdf', compact('ventas', 'totalGeneral', 'mes', 'anio'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream("reporte-ventas-{$mes}-{$anio}.pdf");
    }
}