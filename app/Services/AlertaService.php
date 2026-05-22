<?php

namespace App\Services;

use App\Models\Inventario;
use Carbon\Carbon;

class AlertaService
{
    public function vencidos()
    {
        return Inventario::with('producto')
            ->where('estado', 'vencido')
            ->orderBy('fecha_vencimiento')
            ->get();
    }

    public function porVencer()
    {
        return Inventario::with('producto')
            ->where('estado', 'por_vencer')
            ->orderBy('fecha_vencimiento')
            ->get();
    }

    public function contarAlertas(): array
    {
        return [
            'vencidos'   => Inventario::where('estado', 'vencido')->count(),
            'por_vencer' => Inventario::where('estado', 'por_vencer')->count(),
        ];
    }

    public function actualizarEstados(): void
    {
        $inventarios = Inventario::all();
        $cambios = false;

        foreach ($inventarios as $item) {
            $nuevoEstado = Inventario::calcularEstado($item->fecha_vencimiento->format('Y-m-d'));
            if ($item->estado !== $nuevoEstado) {
                $item->update(['estado' => $nuevoEstado]);
                $cambios = true;
            }
        }

        if ($cambios) {
            \Illuminate\Support\Facades\Cache::forget('vencidos_count_global');
            \Illuminate\Support\Facades\Cache::forget('proximos_count_global');
        }
    }
}