<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\HistorialBaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Controlador de OCR.
 * Administra la comunicación con el microservicio de reconocimiento de texto (FastAPI),
 * las búsquedas de lotes escaneados y el proceso de baja física de inventario vencido.
 */
class OcrController extends Controller
{
    /**
     * Muestra la vista principal del escáner OCR inteligente.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.ocr.index');
    }

    /**
     * 🔍 Busca un lote de inventario activo utilizando el código de lote detectado por el OCR.
     * Realiza un saneamiento del texto de búsqueda eliminando caracteres especiales
     * y ejecuta primero una búsqueda exacta; si no tiene éxito, aplica una búsqueda aproximada (LIKE).
     *
     * @param \Illuminate\Http\Request $request Petición conteniendo la cadena del lote.
     * @return \Illuminate\Http\JsonResponse Datos del lote hallado y su clasificación de vencimiento actual.
     */
    public function buscarPorLote(Request $request)
    {
        // Limpieza del lote: remover espacios en blanco y caracteres no alfanuméricos
        $lote = strtoupper(trim($request->lote));
        $lote = preg_replace('/[^A-Z0-9]/', '', $lote);

        if (!$lote) {
            return response()->json([
                'encontrado' => false,
                'mensaje'    => 'No se recibió lote válido'
            ]);
        }

        // 1. Intentar búsqueda exacta omitiendo espacios
        $inventario = Inventario::with('producto')
            ->whereRaw('REPLACE(UPPER(lote), " ", "") = ?', [$lote])
            ->orderBy('fecha_vencimiento', 'asc')
            ->first();

        // 2. Si no se encuentra exacto, intentar coincidencia parcial
        if (!$inventario) {
            $inventario = Inventario::with('producto')
                ->whereRaw('REPLACE(UPPER(lote), " ", "") LIKE ?', ['%' . $lote . '%'])
                ->orderBy('fecha_vencimiento', 'asc')
                ->first();
        }

        // Si el lote no existe en el sistema
        if (!$inventario) {
            return response()->json([
                'encontrado'   => false,
                'mensaje'      => 'Lote no encontrado en inventario',
                'lote_buscado' => $lote
            ]);
        }

        // Calcular estado temporal de vencimiento para la respuesta del escáner
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

        // Retornar información estructurada del lote y producto para pintar en la UI
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
     * Guarda la bitácora de detecciones OCR para auditoría interna y análisis del motor de IA.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
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
     * Busca un lote que esté clasificado como "vencido" según el nombre aproximado del producto.
     * Útil en el asistente de bajas rápidas por OCR.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarLoteVencido(Request $request)
    {
        $nombre = $request->nombre;

        if (!$nombre) {
            return response()->json(['encontrado' => false]);
        }

        // Búsqueda del primer lote vencido que coincida con el nombre del producto escaneado
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
     * Da de baja física a un lote vencido del sistema.
     * Utiliza una transacción de base de datos para transferir la información del lote
     * al historial de bajas y posteriormente borrarlo del inventario activo de forma segura.
     *
     * @param \Illuminate\Http\Request $request Petición que incluye lote_id y observaciones opcionales.
     * @return \Illuminate\Http\JsonResponse
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

            // 1. Mover la información del lote al historial de bajas
            HistorialBaja::create([
                'producto_id'       => $inventario->producto_id,
                'user_id'           => auth()->id(),
                'lote'              => $inventario->lote,
                'cantidad'          => $inventario->cantidad,
                'fecha_vencimiento' => $inventario->fecha_vencimiento,
                'fecha_ingreso'     => $inventario->fecha_ingreso,
                'motivo'            => 'vencido',
                'observacion'       => $request->observacion ?? 'Baja automática por detección OCR',
            ]);

            // 2. Eliminar físicamente el lote del inventario activo
            $inventario->delete();

            // 3. Limpiar caché global de alertas de vencimiento
            \Illuminate\Support\Facades\Cache::forget('vencidos_count_global');
            \Illuminate\Support\Facades\Cache::forget('proximos_count_global');

            return response()->json(['success' => true]);
        });
    }

    /**
     * Proxy HTTP hacia el microservicio externo de Python OCR.
     * Envía la imagen en base64 adjuntando las cabeceras de autorización requeridas por la API de FastAPI.
     *
     * @param \Illuminate\Http\Request $request Petición con la imagen codificada en base64.
     * @return \Illuminate\Http\JsonResponse Respuesta del microservicio de OCR o estructura de error controlado.
     */
    public function proxyDetectar(Request $request)
    {
        try {
            // Clave secreta sincronizada con el servicio Python
            $apiKey = "Andrufar2026_Secure_OCR_Token_#!";

            // Enviar petición POST HTTP hacia el servidor de Python
            $response = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'Accept'    => 'application/json',
            ])->timeout(45) // Amplio tiempo de espera debido al procesamiento de imágenes
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
            // Error controlado en caso de caída del microservicio FastAPI
            return response()->json([
                'success' => false,
                'error'   => 'El servicio OCR (Python) no está respondiendo. Verifique que el servidor de IA esté activo.'
            ], 503);
        }
    }
}

