<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistorialBaja;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class HistorialBajaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = HistorialBaja::with(['producto', 'user'])
            ->orderBy('fecha_baja', 'desc');

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