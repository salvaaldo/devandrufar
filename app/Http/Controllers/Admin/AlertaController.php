<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AlertaService;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controlador de Alertas de Vencimiento.
 * Proporciona el listado detallado y los reportes PDF de los productos que ya han expirado
 * o que están próximos a hacerlo dentro del margen de 90 días.
 */
class AlertaController extends Controller
{
    /**
     * Constructor del controlador.
     * Inyecta el servicio encargado de gestionar y evaluar las alertas del inventario.
     */
    public function __construct(private AlertaService $alertaService)
    {
    }

    /**
     * Muestra la vista principal con las tablas de productos vencidos y por vencer.
     * Ejecuta una actualización de estados preventiva antes de consultar la base de datos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Actualizar estados del inventario preventivamente
        $this->alertaService->actualizarEstados();

        $vencidos   = $this->alertaService->vencidos();
        $porVencer  = $this->alertaService->porVencer();
        $conteo     = $this->alertaService->contarAlertas();

        return view('admin.alertas.index', compact('vencidos', 'porVencer', 'conteo'));
    }

    /**
     * Genera e inicia la transmisión (stream) de un reporte en formato PDF
     * con el listado consolidado de todas las alertas activas en el sistema.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pdf()
    {
        // Actualizar estados del inventario
        $this->alertaService->actualizarEstados();

        $vencidos  = $this->alertaService->vencidos();
        $porVencer = $this->alertaService->porVencer();

        $pdf = Pdf::loadView('admin.alertas.pdf', compact('vencidos', 'porVencer'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('reporte-alertas-' . now()->format('d-m-Y') . '.pdf');
    }
}