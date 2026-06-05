<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\HistorialBaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OcrController extends Controller
{
    public function index()
    {
        return view('admin.ocr.index');
    }

    /**
     * 🔍 Buscar por lote detectado por OCR
     */
    public function buscarPorLote(Request $request)
    {
        //  LIMPIEZA DEL LOTE
        $lote = strtoupper(trim($request->lote));
        $lote = preg_replace('/[^A-Z0-9]/', '', $lote);

        if (!$lote) {
            return response()->json([
                'encontrado' => false,
                'mensaje' => 'No se recibió lote válido'
            ]);
        }

        //  BUSCAR EXACTO
        $inventario = Inventario::with('producto')
            ->whereRaw('REPLACE(UPPER(lote), " ", "") = ?', [$lote])
            ->orderBy('fecha_vencimiento', 'asc')
            ->first();

        // SI NO ENCUENTRA → BUSCAR PARCIAL
        if (!$inventario) {
            $inventario = Inventario::with('producto')
                ->whereRaw('REPLACE(UPPER(lote), " ", "") LIKE ?', ['%' . $lote . '%'])
                ->orderBy('fecha_vencimiento', 'asc')
                ->first();
        }

        // NO ENCONTRADO
        if (!$inventario) {
            return response()->json([
                'encontrado'   => false,
                'mensaje'      => 'Lote no encontrado en inventario',
                'lote_buscado' => $lote
            ]);
        }

        // CALCULAR ESTADO
        $hoy           = Carbon::today();
        $fechaVenc     = Carbon::parse($inventario->fecha_vencimiento);
        $diasRestantes = $hoy->diffInDays($fechaVenc, false);

        if ($diasRestantes < 0) {
            $estado = 'VENCIDO';
        } elseif ($diasRestantes <= 90) {
            $estado = 'PROXIMO';
        } else {
            $estado = 'VIGENTE';
        }

        //  RESPUESTA
        return response()->json([
            'encontrado'        => true,
            'inventario_id'     => $inventario->id,
            'nombre'            => $inventario->producto->nombre ?? 'Sin nombre',
            'lote'              => $inventario->lote,
            'fecha_vencimiento' => $fechaVenc->format('m/Y'),
            'cantidad'          => $inventario->cantidad,
            'estado'            => $estado,
        ]);
    }

    /**
     *  Guardar detección OCR
     */
    public function guardar(Request $request)
    {
        \App\Models\Deteccion::create([
        'nombre_detectado' => $request->nombre,
        'fecha_detectada'  => $request->fecha,
        'estado'           => $request->estado,
        'lote'             => $request->lote,
        'user_id'          => auth()->id(),
    ]);

    return response()->json(['success' => true]);
    }

    /**
     *  Buscar lote vencido (modal baja)
     */
    public function buscarLoteVencido(Request $request)
    {
        $nombre = $request->nombre;

        if (!$nombre) {
            return response()->json(['encontrado' => false]);
        }

        $inventario = Inventario::with('producto')
            ->whereHas('producto', function ($q) use ($nombre) {
                $q->whereRaw('LOWER(nombre) LIKE ?', ['%' . strtolower($nombre) . '%']);
            })
            ->where('estado', 'vencido')
            ->first();

        if (!$inventario) {
            return response()->json(['encontrado' => false]);
        }

        return response()->json([
            'encontrado' => true,
            'lote_id'    => $inventario->id,
            'producto'   => $inventario->producto->nombre ?? '-',
            'lote'       => $inventario->lote,
            'cantidad'   => $inventario->cantidad,
            'fecha_venc' => Carbon::parse($inventario->fecha_vencimiento)->format('m/Y'),
        ]);
    }

    /**
     *  Dar de baja lote → mover a historial_bajas
     */
    public function darDeBaja(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $inventario = Inventario::with('producto')->find($request->lote_id);

            if (!$inventario) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'Lote no encontrado'
                ]);
            }

            // 1. Copiar a historial_bajas
            HistorialBaja::create([
                'producto_id'      => $inventario->producto_id,
                'user_id'          => auth()->id(),
                'lote'             => $inventario->lote,
                'cantidad'         => $inventario->cantidad,
                'fecha_vencimiento' => $inventario->fecha_vencimiento,
                'fecha_ingreso'    => $inventario->fecha_ingreso,
                'motivo'           => 'vencido',
                'observacion'      => $request->observacion ?? 'Baja automática por detección OCR',
            ]);

            // 2. Eliminar del inventario activo
            $inventario->delete();

            // 3. Limpiar caché de alertas
            \Illuminate\Support\Facades\Cache::forget('vencidos_count_global');
            \Illuminate\Support\Facades\Cache::forget('proximos_count_global');

            return response()->json(['success' => true]);
        });
    }

    /**
     *  Proxy hacia Python OCR
     */
    public function proxyDetectar(Request $request)
    {
        try {
            // Clave secreta sincronizada con el servicio Python
            $apiKey = "Andrufar2026_Secure_OCR_Token_#!";

            $response = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'Accept'    => 'application/json',
            ])->timeout(45) // Aumentamos timeout para imágenes pesadas
              ->post(env('OCR_SERVICE_URL', 'http://127.0.0.1:5000') . '/detectar', [
                  'imagen' => $request->input('imagen')
              ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'Error en el motor de IA',
                    'status'  => $response->status()
                ], $response->status());
            }

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'El servicio OCR (Python) no está respondiendo. Verifique que el servidor de IA esté activo.'
            ], 503);
        }
    }
}
