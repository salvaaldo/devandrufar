<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\Producto;
use App\Services\AlertaService;

/**
 * Controlador del Panel de Control (Dashboard) de la sección administrativa.
 * Proporciona el resumen de alertas y conteos rápidos de inventario.
 */
class DashboardController extends Controller
{
    /**
     * Constructor del controlador.
     * Inyecta el servicio de AlertaService para evaluar las condiciones de vencimiento.
     */
    public function __construct(private AlertaService $alertaService)
    {
    }

    /**
     * Muestra la página principal del panel de administración (Dashboard).
     * Primero invoca la actualización masiva de estados de vencimiento para garantizar
     * datos fidedignos, luego recopila estadísticas de productos y recupera las primeras
     * 5 alertas de cada tipo para los widgets rápidos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Actualizar estados de inventario de forma preventiva antes de renderizar estadísticas
        $this->alertaService->actualizarEstados();

        $totalProductos = Producto::count();
        $conteo         = $this->alertaService->contarAlertas();
        
        // Obtener un avance limitado (máximo 5 registros) de lotes próximos a vencer y vencidos
        $porVencer      = $this->alertaService->porVencer()->take(5);
        $vencidos       = $this->alertaService->vencidos()->take(5);

        return view('dashboard', compact('totalProductos', 'conteo', 'porVencer', 'vencidos'));
    }
}