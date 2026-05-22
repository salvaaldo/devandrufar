<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AlertaService;
use Barryvdh\DomPDF\Facade\Pdf;

class AlertaController extends Controller
{
    public function __construct(private AlertaService $alertaService)
    {
    }

    public function index()
    {
        // Actualizar estados antes de mostrar
        $this->alertaService->actualizarEstados();

        $vencidos   = $this->alertaService->vencidos();
        $porVencer  = $this->alertaService->porVencer();
        $conteo     = $this->alertaService->contarAlertas();

        return view('admin.alertas.index', compact('vencidos', 'porVencer', 'conteo'));
    }

    public function pdf()
    {
        $this->alertaService->actualizarEstados();

        $vencidos  = $this->alertaService->vencidos();
        $porVencer = $this->alertaService->porVencer();

        $pdf = Pdf::loadView('admin.alertas.pdf', compact('vencidos', 'porVencer'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('reporte-alertas-' . now()->format('d-m-Y') . '.pdf');
    }
}