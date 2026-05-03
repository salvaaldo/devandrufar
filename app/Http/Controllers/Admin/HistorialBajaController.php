<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistorialBaja;

class HistorialBajaController extends Controller
{
    public function index()
    {
        $bajas = HistorialBaja::with(['producto', 'user'])
            ->orderBy('fecha_baja', 'desc')
            ->paginate(15);

        return view('admin.historial_bajas.index', compact('bajas'));
    }
}