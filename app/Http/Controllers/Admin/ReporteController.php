<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\Cotizacion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
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

    public function ventas(Request $request)
    {
        $mes = $request->input('mes', now()->month);
        $anio = $request->input('anio', now()->year);

        // Ventas del periodo seleccionado
        $query = Cotizacion::with(['cliente', 'user'])
            ->whereYear('created_at', $anio)
            ->whereMonth('created_at', $mes)
            ->where('estado', '!=', 'anulada')
            ->orderBy('created_at', 'desc');

        $ventas = $query->get();
        $totalGeneral = $ventas->sum('total');

        // Estadísticas por Usuario
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