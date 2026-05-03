<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\Producto;
use App\Services\AlertaService;

class DashboardController extends Controller
{
    public function __construct(private AlertaService $alertaService)
    {
    }

    public function index()
    {
        // Actualizar estados antes de mostrar
        $this->alertaService->actualizarEstados();

        $totalProductos = Producto::count();
        $conteo         = $this->alertaService->contarAlertas();
        $porVencer      = $this->alertaService->porVencer()->take(5);
        $vencidos       = $this->alertaService->vencidos()->take(5);

        return view('dashboard', compact('totalProductos', 'conteo', 'porVencer', 'vencidos'));
    }
}