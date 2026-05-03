<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\MedicamentoController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\InventarioController;
use App\Http\Controllers\Admin\AlertaController;
use App\Http\Controllers\Admin\OcrController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Endpoint público OCR
Route::get('api/medicamentos-lista', function () {
    return response()->json(
        \App\Models\Medicamento::select('id', 'nombre')->get()
    );
})->name('api.medicamentos');

Route::middleware(['auth'])->group(function () {

    // =========================
    // PROFILE
    // =========================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // =========================
    // DASHBOARD
    // =========================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =========================
    // USUARIOS
    // =========================
    Route::resource('usuarios', UserController::class);

    // =========================
    // CLIENTES
    // =========================
    Route::resource('clientes', ClienteController::class);

    // =========================
    // MEDICAMENTOS
    // =========================
    Route::resource('medicamentos', MedicamentoController::class);

    Route::get('medicamentos-importar', [MedicamentoController::class, 'importar'])
        ->name('medicamentos.importar');

    Route::post('medicamentos-importar', [MedicamentoController::class, 'procesarImportacion'])
        ->name('medicamentos.procesar');

    // =========================
    // PRODUCTOS
    // =========================
    Route::resource('productos', ProductoController::class);

    Route::get('productos/buscar-medicamento', [ProductoController::class, 'buscarMedicamento'])
        ->name('productos.buscar-medicamento');

    // =========================
    // INVENTARIO
    // =========================
    Route::resource('inventario', InventarioController::class);

    // =========================
    // ALERTAS
    // =========================
    Route::get('alertas', [AlertaController::class, 'index'])->name('alertas.index');

    // =========================
    // HISTORIAL BAJAS
    // =========================
    Route::get('historial-bajas', [App\Http\Controllers\Admin\HistorialBajaController::class, 'index'])
        ->name('historial-bajas.index');



    // AJAX: LOTES DISPONIBLES
    Route::get(
        'cotizaciones/lotes-disponibles',
        [\App\Http\Controllers\Admin\CotizacionController::class, 'lotesDisponibles']
    )->name('cotizaciones.lotes');

    // =========================
    // COTIZACIONES
    // =========================

    // AJAX rutas especiales ANTES del resource
    Route::get(
        'cotizaciones/lotes-disponibles',
        [\App\Http\Controllers\Admin\CotizacionController::class, 'lotesDisponibles']
    )->name('cotizaciones.lotes');

    Route::get(
        'cotizaciones/lotes-buscar',
        [\App\Http\Controllers\Admin\CotizacionController::class, 'buscarLotes']
    )->name('cotizaciones.lotes.buscar');

    //cotizacion fifo
    Route::get(
        'cotizaciones/stock-total',
        [\App\Http\Controllers\Admin\CotizacionController::class, 'stockTotal']
    )->name('cotizaciones.stock');
    //pdf cotizaciones
    Route::get(
        'cotizaciones/{id}/pdf',
        [\App\Http\Controllers\Admin\CotizacionController::class, 'pdf']
    )->name('cotizaciones.pdf');

    // Resource DESPUÉS
    Route::resource('cotizaciones', \App\Http\Controllers\Admin\CotizacionController::class);



    // =========================
    // OCR
    // =========================
    Route::post('ocr/proxy-detectar', [OcrController::class, 'proxyDetectar']); // ✅ AGREGA ESTA
    Route::post('ocr/guardar', function (\Illuminate\Http\Request $request) {
        try {
            \App\Models\Deteccion::create([
                'nombre_detectado' => $request->input('nombre'),
                'fecha_detectada'  => $request->input('fecha'),
                'estado'           => $request->input('estado', 'DESCONOCIDO'),
                'user_id'          => auth()->id() ?? 1,
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    });

    Route::post('ocr/buscar-por-lote', [OcrController::class, 'buscarPorLote']);

    Route::post('ocr/buscar-lote-vencido', function (\Illuminate\Http\Request $request) {
        try {
            $nombre = $request->input('nombre');

            $producto = \App\Models\Producto::where('nombre', 'LIKE', '%' . $nombre . '%')->first();
            if (!$producto) return response()->json(['encontrado' => false]);

            $lote = \App\Models\Inventario::where('producto_id', $producto->id)
                ->where('estado', 'vencido')
                ->where('cantidad', '>', 0)
                ->first();

            if (!$lote) return response()->json(['encontrado' => false]);

            return response()->json([
                'encontrado' => true,
                'lote_id'    => $lote->id,
                'lote'       => $lote->lote,
                'cantidad'   => $lote->cantidad,
                'producto'   => $producto->nombre,
                'fecha_venc' => $lote->fecha_vencimiento->format('d/m/Y'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['encontrado' => false, 'error' => $e->getMessage()]);
        }
    });

    Route::post('ocr/dar-de-baja', [OcrController::class, 'darDeBaja']);

    Route::get('ocr/capturar-ip', function (\Illuminate\Http\Request $request) {
        try {
            $url = $request->query('url');
            $contexto = stream_context_create(['http' => ['timeout' => 5]]);
            $imagen = file_get_contents($url, false, $contexto);

            if ($imagen === false) {
                return response()->json(['error' => 'No se pudo obtener la imagen'], 500);
            }

            return response()->json([
                'imagen' => 'data:image/jpeg;base64,' . base64_encode($imagen)
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    Route::get('ocr/historial', function () {
        $detecciones = \App\Models\Deteccion::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.ocr.historial', compact('detecciones'));
    })->name('ocr.historial');

    Route::get('ocr', [OcrController::class, 'index'])->name('ocr.index');
});

require __DIR__ . '/auth.php';
