@extends('layouts.app')

@section('title', 'Detección OCR')

@section('content')

<style>
    .ocr-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 8px 24px rgba(0,0,0,0.06);
    }
    .modo-btn {
        flex: 1;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 10px;
        border: 2px solid #e5e7eb;
        color: #6b7280;
        background: #f9fafb;
        cursor: pointer;
        transition: all 0.2s;
        letter-spacing: 0.2px;
    }
    .modo-btn.activo {
        border-color: #2563eb;
        background: #2563eb;
        color: #fff;
        box-shadow: 0 4px 12px rgba(37,99,235,0.3);
    }
    .modo-btn:hover:not(.activo) {
        border-color: #93c5fd;
        background: #eff6ff;
        color: #2563eb;
    }
    .camara-box {
        position: relative;
        background: #0f172a;
        border-radius: 14px;
        overflow: hidden;
        height: 320px;
    }
    .camara-box video {
        width: 100%; height: 100%; object-fit: cover;
    }
    .camara-overlay {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }
    /* Esquinas del visor */
    .visor-corner {
        position: absolute;
        width: 28px; height: 28px;
        border-color: #3b82f6;
        border-style: solid;
    }
    .visor-corner.tl { top: 16px; left: 16px; border-width: 3px 0 0 3px; border-radius: 4px 0 0 0; }
    .visor-corner.tr { top: 16px; right: 16px; border-width: 3px 3px 0 0; border-radius: 0 4px 0 0; }
    .visor-corner.bl { bottom: 16px; left: 16px; border-width: 0 0 3px 3px; border-radius: 0 0 0 4px; }
    .visor-corner.br { bottom: 16px; right: 16px; border-width: 0 3px 3px 0; border-radius: 0 0 4px 0; }
    /* Línea de scan animada */
    .scan-line {
        position: absolute;
        left: 16px; right: 16px;
        height: 2px;
        background: linear-gradient(90deg, transparent, #3b82f6, transparent);
        animation: scanMove 2s ease-in-out infinite;
        display: none;
    }
    .scan-line.activo { display: block; }
    @keyframes scanMove {
        0% { top: 16px; opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { top: calc(100% - 16px); opacity: 0; }
    }
    /* Badge contador */
    .contador-badge {
        position: absolute;
        top: 12px; right: 12px;
        width: 52px; height: 52px;
        border-radius: 50%;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(8px);
        border: 2px solid #3b82f6;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        display: none;
    }
    .contador-badge.visible { display: flex; }
    .contador-num { font-size: 20px; font-weight: 700; color: #fff; line-height: 1; }
    .contador-label { font-size: 8px; color: #93c5fd; letter-spacing: 0.5px; }
    /* Guía */
    .guia-badge {
        position: absolute;
        bottom: 12px; left: 50%;
        transform: translateX(-50%);
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(8px);
        color: #fff;
        font-size: 12px;
        padding: 6px 14px;
        border-radius: 20px;
        white-space: nowrap;
        display: none;
    }
    .guia-badge.visible { display: block; }
    /* Status chips */
    .status-chip {
        position: absolute;
        bottom: 12px; left: 50%;
        transform: translateX(-50%);
        font-size: 11px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        white-space: nowrap;
        display: none;
    }
    .status-chip.visible { display: block; }
    .status-chip.amarillo { background: #fef3c7; color: #92400e; }
    .status-chip.verde { background: #d1fae5; color: #065f46; }
    /* Panel resultado */
    .resultado-field {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        transition: border-color 0.2s;
    }
    .resultado-field.detectado {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    .resultado-field label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #94a3b8;
        display: block;
        margin-bottom: 4px;
    }
    .resultado-field .valor {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
    }
    .resultado-field .valor.vacio { color: #cbd5e1; font-size: 14px; font-weight: 400; }
    /* Badge confianza */
    .confianza-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        margin-top: 6px;
    }
    .confianza-alta { background: #d1fae5; color: #065f46; }
    .confianza-media { background: #fef3c7; color: #92400e; }
    .confianza-baja { background: #fee2e2; color: #991b1b; }
    /* Botones acción */
    .btn-guardar {
        width: 100%;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(37,99,235,0.3);
    }
    .btn-guardar:hover { opacity: 0.92; transform: translateY(-1px); }
    .btn-nueva {
        width: 100%;
        background: #f1f5f9;
        color: #475569;
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 8px;
    }
    .btn-nueva:hover { background: #e2e8f0; }
    /* Upload area */
    .upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        height: 320px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #f8fafc;
    }
    .upload-area:hover { border-color: #3b82f6; background: #eff6ff; }
    /* Modal vencido */
    .modal-overlay {
        position: fixed; inset: 0; z-index: 50;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .modal-overlay.visible { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 20px;
        padding: 2rem;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.3);
    }
    /* Historial */
    .historial-row:hover { background: #f8fafc; }
</style>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Panel izquierdo: Cámara --}}
    <div class="ocr-card p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-base font-bold text-gray-800">📷 Captura de Imagen</h2>
            <span id="estado-camara" class="text-xs font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Sin iniciar</span>
        </div>

        {{-- Tabs modo --}}
        <div class="flex gap-2 mb-5">
            <button onclick="setModo('webcam')" id="btn-webcam" class="modo-btn activo">💻 Webcam</button>
            <button onclick="setModo('archivo')" id="btn-archivo" class="modo-btn">🖼️ Imagen</button>
            <button onclick="abrirBajaManual()" id="btn-manual" class="modo-btn">⌨️ Baja Manual</button>
        </div>

        {{-- Webcam --}}
        <div id="modo-webcam">
            <div class="camara-box mb-4">
                <video id="video" autoplay playsinline></video>
                <canvas id="canvas-motion" class="absolute top-0 left-0 w-full h-full opacity-0"></canvas>

                {{-- Visor corners --}}
                <div class="camara-overlay">
                    <div class="visor-corner tl"></div>
                    <div class="visor-corner tr"></div>
                    <div class="visor-corner bl"></div>
                    <div class="visor-corner br"></div>
                    <div id="scan-line" class="scan-line"></div>
                </div>

                {{-- Contador --}}
                <div id="contador-badge" class="contador-badge">
                    <span id="cuenta-regresiva" class="contador-num">5</span>
                    <span class="contador-label">seg</span>
                </div>

                {{-- Guía --}}
                <div id="guia-badge" class="guia-badge">📦 Acerque el medicamento a la cámara</div>

                {{-- Status chips --}}
                <div id="chip-estabilizando" class="status-chip amarillo">⏳ Mantenga quieto...</div>
                <div id="chip-capturando" class="status-chip verde">📸 Capturando...</div>
            </div>

            <div class="flex flex-col gap-2">
                <div class="flex gap-2">
                    <button onclick="iniciarWebcam()" id="btn-iniciar"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm px-4 py-2.5 transition-all">
                        ▶ Iniciar Cámara
                    </button>
                    <button onclick="detenerWebcam()" id="btn-detener" disabled
                        class="flex-1 bg-gray-200 text-gray-400 font-semibold rounded-xl text-sm px-4 py-2.5 transition-all">
                        ⏹ Detener
                    </button>
                </div>
                <button onclick="capturarManual()" id="btn-capturar-manual" disabled
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-base px-4 py-4 transition-all shadow-lg flex items-center justify-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    📸 CAPTURAR Y ESCANEAR
                </button>
            </div>
        </div>

        {{-- Subir imagen --}}
        <div id="modo-archivo" class="hidden">
            <label for="input-imagen" class="upload-area mb-3">
                <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <p class="text-sm font-semibold text-gray-500">Haz clic para subir una imagen</p>
                <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP</p>
                <input id="input-imagen" type="file" accept="image/*" class="hidden" onchange="procesarImagen(this)">
            </label>
            <img id="preview-imagen" class="w-full rounded-xl hidden mb-3" style="max-height:200px; object-fit:contain;">
        </div>

        <canvas id="canvas-captura" class="hidden"></canvas>
    </div>

    {{-- Panel derecho: Resultados --}}
    <div class="ocr-card p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-base font-bold text-gray-800">🔍 Resultados OCR</h2>
            <span id="badge-estado-resultado" class="text-xs font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Esperando...</span>
        </div>

        {{-- Estado vacío --}}
        <div id="estado-ocr" class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-3">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-400">Inicia la cámara y acerca un medicamento</p>
            <p class="text-xs text-gray-300 mt-1">El sistema detectará automáticamente</p>
        </div>

        {{-- Procesando --}}
        <div id="procesando" class="hidden flex flex-col items-center justify-center h-64 text-center">
            <div class="w-12 h-12 border-3 border-blue-200 border-t-blue-600 rounded-full animate-spin mb-4" style="border-width: 3px;"></div>
            <p class="text-sm font-semibold text-gray-600">Analizando imagen...</p>
            <p class="text-xs text-gray-400 mt-1">El modelo OCR está procesando</p>
        </div>

        {{-- Resultados --}}
        <div id="resultados" class="hidden">
            {{-- Barra de Porcentaje de Precisión --}}
            <div class="mb-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Precisión de lectura IA</span>
                    <span id="texto-porcentaje" class="text-sm font-black text-blue-600">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="barra-porcentaje" class="bg-blue-600 h-2 rounded-full transition-all duration-1000 ease-out" style="width: 0%"></div>
                </div>
                <p id="texto-confianza-detalle" class="text-[10px] text-gray-400 mt-1.5 font-medium"></p>
            </div>

            <div class="space-y-3 mb-4">
                <div class="resultado-field" id="campo-fecha">
                    <label> Fecha de Vencimiento</label>
                    <div class="valor vacio" id="resultado-fecha">No detectada</div>
                </div>
                <div class="resultado-field" id="campo-nombre">
                    <label> Medicamento</label>
                    <div class="valor vacio text-sm" id="resultado-nombre">No detectado</div>
                </div>
            </div>

            <button id="btn-guardar" onclick="guardarDeteccion()" class="btn-guardar hidden">
                💾 Guardar en sistema
            </button>
            <button onclick="limpiarResultados()" class="btn-nueva">
                🔄 Escanear otro medicamento
            </button>
        </div>
    </div>
</div>

{{-- Historial --}}
<div class="ocr-card p-6 mt-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-bold text-gray-800"> Historial de Detecciones</h2>
        <span class="text-xs text-gray-400">Sesión actual</span>
    </div>
    <div class="overflow-x-auto rounded-xl border border-gray-100">
        <table class="w-full text-sm text-left">
            <thead class="text-xs font-semibold text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-3">Medicamento</th>
                    <th class="px-5 py-3">Fecha Venc.</th>
                    <th class="px-5 py-3">Estado</th>
                    <th class="px-5 py-3">Detectado</th>
                </tr>
            </thead>
            <tbody id="historial-body">
                <tr><td colspan="4" class="px-5 py-8 text-center text-gray-300 text-xs">No hay detecciones en esta sesión</td></tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Modal corrección manual --}}
<div id="modal-manual" class="modal-overlay">
    <div class="modal-box">
        <div class="text-center mb-5">
            <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800">No se detectaron datos</h3>
            <p class="text-sm text-gray-400 mt-1">Ingresa los datos manualmente o intenta de nuevo</p>
        </div>
        <div class="space-y-3 mb-5">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5"> Nombre del medicamento</label>
                <input type="text" id="modal-nombre" placeholder="Ej: Paracetamol 500mg"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5"> Fecha de vencimiento</label>
                <input type="text" id="modal-fecha" placeholder="Ej: 07/2026"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-50">
                <p class="text-xs text-gray-400 mt-1">Formato: MM/YYYY o DD/MM/YYYY</p>
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <button onclick="guardarDesdeModal()" class="btn-guardar"> Guardar datos</button>
            <button onclick="intentarDeNuevo()" class="btn-nueva">🔄 Intentar de nuevo</button>
            <button onclick="cerrarModal()" class="w-full text-gray-400 text-sm py-2 hover:text-gray-600 transition-colors">Cancelar</button>
        </div>
    </div>
</div>

{{-- Modal búsqueda manual --}}
<div id="modal-baja-manual" class="modal-overlay">
    <div class="modal-box">
        <div class="text-center mb-5">
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Búsqueda Manual</h3>
            <p class="text-sm text-gray-400 mt-1">Busca el medicamento para dar de baja su lote vencido</p>
        </div>
        <div class="mb-5">
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Nombre del medicamento</label>
            <input type="text" id="manual-search-nombre" placeholder="Escriba el nombre..."
                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-50"
                onkeyup="if(event.key === 'Enter') buscarLoteManual()">
        </div>
        <div id="manual-search-loading" class="hidden text-center py-4">
            <div class="w-6 h-6 border-2 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-2"></div>
            <p class="text-xs text-gray-400">Buscando en productos de empresa...</p>
        </div>
        <div class="flex flex-col gap-2">
            <button onclick="buscarLoteManual()" class="btn-guardar"> Buscar Lote</button>
            <button onclick="cerrarBajaManual()" class="w-full text-gray-400 text-sm py-2 hover:text-gray-600 transition-colors">Cancelar</button>
        </div>
    </div>
</div>

{{-- Modal lote vencido --}}
<div id="modal-vencido" class="modal-overlay">
    <div class="modal-box">
        <div class="text-center mb-5">
            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <h3 id="modal-vencido-titulo" class="text-lg font-bold text-gray-800">⚠️ Lote Vencido Detectado</h3>
            <p class="text-sm text-gray-400 mt-1">Se encontró este lote en la lista de productos</p>
            <div class="mt-3 flex justify-center">
                <span id="modal-vencido-precision" class="text-[11px] font-bold px-3 py-1 bg-green-100 text-green-700 rounded-full border border-green-200 shadow-sm">
                    🎯 Precisión IA: 99% (Verificado)
                </span>
            </div>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-5">
            <div class="grid grid-cols-2 gap-3 text-sm mb-4">
                <div><p class="text-xs text-gray-400 mb-0.5">Producto</p><p id="vencido-producto" class="font-semibold text-gray-800">-</p></div>
                <div><p class="text-xs text-gray-400 mb-0.5">Lote</p><p id="vencido-lote" class="font-semibold text-gray-800">-</p></div>
                <div><p class="text-xs text-gray-400 mb-0.5">Cantidad</p><p id="vencido-cantidad" class="font-semibold text-red-600">-</p></div>
                <div><p class="text-xs text-gray-400 mb-0.5">Vencimiento</p><p id="vencido-fecha" class="font-semibold text-red-600">-</p></div>
            </div>
            <div>
                <label class="block text-[10px] uppercase font-bold text-red-400 mb-1">Observación de Baja</label>
                <textarea id="vencido-observacion" rows="2" 
                    class="w-full bg-white border border-red-100 rounded-lg text-sm p-2 focus:ring-red-500 focus:border-red-500"
                    placeholder="Ej: Producto dañado o vencido en estante..."></textarea>
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <button onclick="confirmarBaja()"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl py-3 text-sm transition-all">
                🗑️ Dar de baja de los productos
            </button>
            <button onclick="cerrarModalVencido()"
                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold rounded-xl py-3 text-sm transition-all">
                Cerrar sin acción
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let stream = null;
    let modoActual = 'webcam';
    let procesando = false;
    let ultimaDeteccion = null;
    let loteVencidoId = null;
    let cuentaRegresivaInterval = null;

    // ── Detección de Movimiento ──
    let prevFrame = null;
    let motionInterval = null;
    const MOTION_THRESHOLD = 25; // Sensibilidad (menor = más sensible)
    const PIXEL_DIFF_RATIO = 0.05; // Porcentaje de píxeles cambiados para activar

    // ── Modo ──
    function setModo(modo) {
        modoActual = modo;
        ['webcam', 'archivo'].forEach(m => {
            document.getElementById('modo-' + m).classList.toggle('hidden', m !== modo);
            document.getElementById('btn-' + m).classList.toggle('activo', m === modo);
        });
        if (modo !== 'webcam') detenerWebcam();
    }

    // ── Webcam ──
    async function iniciarWebcam() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } }
            });
            const video = document.getElementById('video');
            video.srcObject = stream;
            document.getElementById('btn-iniciar').disabled = true;
            document.getElementById('btn-detener').disabled = false;
            document.getElementById('btn-detener').className = 'flex-1 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl text-sm px-4 py-2.5 transition-all';
            document.getElementById('estado-camara').textContent = '🟢 Activa';
            document.getElementById('estado-camara').className = 'text-xs font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full';
            
            video.onloadedmetadata = () => {
                video.play();
                document.getElementById('btn-capturar-manual').disabled = false;
                document.getElementById('guia-badge').textContent = '✅ Cámara lista. Presione el botón para escanear';
                document.getElementById('guia-badge').classList.add('visible');
            };
        } catch (e) {
            alert('No se pudo acceder a la cámara: ' + e.message);
        }
    }



    function detenerWebcam() {
        if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
        ocultarIndicadores();
        document.getElementById('btn-iniciar').disabled = false;
        document.getElementById('btn-detener').disabled = true;
        document.getElementById('btn-capturar-manual').disabled = true;
        document.getElementById('btn-detener').className = 'flex-1 bg-gray-200 text-gray-400 font-semibold rounded-xl text-sm px-4 py-2.5 transition-all';
        document.getElementById('estado-camara').textContent = 'Detenida';
        document.getElementById('estado-camara').className = 'text-xs font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full';
    }

    function ocultarIndicadores() {
        ['contador-badge', 'guia-badge', 'chip-estabilizando', 'chip-capturando'].forEach(id => {
            document.getElementById(id).classList.remove('visible');
        });
        document.getElementById('scan-line').classList.remove('activo');
    }

    function capturarManual() {
        if (!stream || procesando) return;
        
        // Efecto visual de flash
        const video = document.getElementById('video');
        video.style.opacity = '0.5';
        setTimeout(() => video.style.opacity = '1', 100);

        document.getElementById('chip-capturando').classList.add('visible');
        capturarWebcam();
    }

    function capturarWebcam() {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas-captura');
        
        // 1. Reducimos aún más la resolución a 480px para máxima velocidad
        canvas.width = Math.min(video.videoWidth, 480);
        canvas.height = Math.round(video.videoHeight * (canvas.width / video.videoWidth));
        const ctx = canvas.getContext('2d');
        
        // 2. Aplicamos un filtro de alto contraste y blanco/negro
        // Esto reduce enormemente el peso del archivo y ayuda al OCR
        ctx.filter = 'grayscale(100%) contrast(150%)';
        ctx.imageSmoothingEnabled = false; // Sin suavizado, bordes más duros para OCR
        
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // 3. Compresión más agresiva (0.4) ya que es blanco y negro
        enviarOCR(canvas.toDataURL('image/jpeg', 0.4));
    }

    // ── Subir imagen ──
    function procesarImagen(input) {
        const file = input.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('preview-imagen').src = e.target.result;
            document.getElementById('preview-imagen').classList.remove('hidden');
            enviarOCR(e.target.result);
        };
        reader.readAsDataURL(file);
        input.value = '';
    }

    function actualizarBarraPrecision(pct) {
        const nivel = pct >= 90 ? 'alta' : (pct > 0 && pct < 80 ? 'media' : (pct === 0 ? 'nula' : 'baja'));
        const barra = document.getElementById('barra-porcentaje');
        const textoPct = document.getElementById('texto-porcentaje');
        const textoDetalle = document.getElementById('texto-confianza-detalle');
        
        barra.style.width = '0%';
        
        setTimeout(() => {
            barra.style.width = pct + '%';
            textoPct.textContent = pct + '%';
            
            if (pct >= 90) {
                barra.className = 'h-2 rounded-full transition-all duration-1000 ease-out bg-green-500';
                textoPct.className = 'text-sm font-black text-green-600';
                textoDetalle.textContent = '✅ Alta confianza: Medicamento identificado correctamente.';
            } else if (pct >= 50) {
                barra.className = 'h-2 rounded-full transition-all duration-1000 ease-out bg-yellow-400';
                textoPct.className = 'text-sm font-black text-yellow-600';
                textoDetalle.textContent = '⚠️ Confianza media: Faltan datos, verifique visualmente.';
            } else {
                barra.className = 'h-2 rounded-full transition-all duration-1000 ease-out bg-red-500';
                textoPct.className = 'text-sm font-black text-red-600';
                textoDetalle.textContent = pct === 0 ? '❌ No se pudo identificar el medicamento.' : '❌ Lectura dudosa o lote inexistente.';
            }
        }, 100);
    }

    // ── Enviar OCR ──
    async function enviarOCR(base64) {
        if (procesando) return;
        procesando = true;
        document.getElementById('estado-ocr').classList.add('hidden');
        document.getElementById('resultados').classList.add('hidden');
        document.getElementById('procesando').classList.remove('hidden');
        document.getElementById('chip-capturando').classList.remove('visible');

        try {
            const res = await fetch('/ocr/proxy-detectar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ imagen: base64 })
            });
            const data = await res.json();
            document.getElementById('procesando').classList.add('hidden');

            if (data.success) {
                if (data.confianza_estado === 'rechazado') {
                    procesando = false;
                    return;
                }

                if (data.lote) {
                    // Delegamos la actualización de la barra a la función que busca en la base de datos
                    buscarPorLote(data.lote, data.fecha, data.estado);
                } else if (data.nombre && data.fecha) {
                    actualizarBarraPrecision(85); // No hay lote pero detectó nombre y fecha
                    mostrarResultado(data.nombre, data.fecha, data.estado, null);
                } else if (data.fecha) {
                    actualizarBarraPrecision(60); // Solo detectó la fecha
                    mostrarResultado(null, data.fecha, data.estado ?? 'DESCONOCIDO', null);
                } else if (data.nombre) {
                    actualizarBarraPrecision(60); // Solo detectó el nombre
                    mostrarResultado(data.nombre, null, data.estado, null);
                } else {
                    noDetectado();
                }
            } else {
                noDetectado();
            }
        } catch (e) {
            document.getElementById('procesando').classList.add('hidden');
            alert('No se pudo conectar al servicio OCR.');
            limpiarResultados();
        }
        procesando = false;
    }

    function mostrarResultado(nombre, fecha, estado, lote) {
        ultimaDeteccion = { nombre, fecha, estado, lote };

        const fechaEl = document.getElementById('resultado-fecha');
        fechaEl.textContent = fecha ?? 'No detectada';
        fechaEl.className = fecha ? 'valor' : 'valor vacio';
        document.getElementById('campo-fecha').classList.toggle('detectado', !!fecha);

        const nombreEl = document.getElementById('resultado-nombre');
        nombreEl.className = nombre ? 'valor text-sm flex flex-wrap items-center gap-2 mt-1' : 'valor vacio text-sm mt-1';
        if (lote) {
            nombreEl.innerHTML = `<span>${nombre ?? 'Sin nombre'}</span> <span class="text-[10px] bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full font-bold tracking-wide border border-blue-200 shadow-sm whitespace-nowrap">LOTE: ${lote}</span>`;
        } else {
            nombreEl.textContent = nombre ?? 'No detectado';
        }
        document.getElementById('campo-nombre').classList.toggle('detectado', !!nombre);

        // Badge estado
        const estadoBadge = document.getElementById('badge-estado-resultado');
        const estadoMap = {
            'VIGENTE': ['🟢 Vigente', 'text-green-600 bg-green-50'],
            'PROXIMO': ['🟡 Por vencer', 'text-yellow-600 bg-yellow-50'],
            'VENCIDO': ['🔴 Vencido', 'text-red-600 bg-red-50'],
            'DESCONOCIDO': ['⚪ Desconocido', 'text-gray-400 bg-gray-100'],
        };
        const [txt, cls] = estadoMap[estado] ?? estadoMap['DESCONOCIDO'];
        estadoBadge.textContent = txt;
        estadoBadge.className = `text-xs font-medium px-3 py-1 rounded-full ${cls}`;

        document.getElementById('resultados').classList.remove('hidden');
        document.getElementById('btn-guardar').classList.remove('hidden');
        agregarAlHistorial(nombre ?? '-', fecha, estado);
    }

    function noDetectado() {
        procesando = false;
        actualizarBarraPrecision(0); // Forzar a 0%
        if (modoActual === 'archivo') {
            abrirModal();
        } else {
            document.getElementById('estado-ocr').classList.remove('hidden');
        }
    }

    // ── Buscar por lote ──
    async function buscarPorLote(lote, fecha, estadoOcr) {
        try {
            const res = await fetch('/ocr/buscar-por-lote', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: JSON.stringify({ lote, fecha })
            });
            const data = await res.json();
            document.getElementById('procesando').classList.add('hidden');

            if (data.encontrado) {
                actualizarBarraPrecision(99); // Identificado 100% en base de datos = 99% precisión
                mostrarResultado(data.nombre, data.fecha_vencimiento, data.estado, lote);
                if (data.estado === 'VENCIDO' || data.estado === 'PROXIMO') {
                    loteVencidoId = data.inventario_id;
                    document.getElementById('vencido-producto').textContent = data.nombre;
                    document.getElementById('vencido-lote').textContent = lote;
                    document.getElementById('vencido-cantidad').textContent = data.cantidad + ' unidades';
                    document.getElementById('vencido-fecha').textContent = data.fecha_vencimiento;
                    document.getElementById('modal-vencido-titulo').textContent = data.estado === 'VENCIDO' ? '⚠️ Lote Vencido Detectado' : '⏰ Lote Próximo a Vencer';
                    document.getElementById('modal-vencido-precision').innerHTML = '🎯 Precisión IA: 99% (Verificado en BD)';
                    document.getElementById('modal-vencido-precision').className = 'text-[11px] font-bold px-3 py-1 bg-green-100 text-green-700 rounded-full border border-green-200 shadow-sm';
                    document.getElementById('modal-vencido').classList.add('visible');
                }
            } else {
                actualizarBarraPrecision(40); // Leyó un lote pero no existe en el sistema
                mostrarResultado(null, fecha, estadoOcr, lote);
                document.getElementById('resultado-nombre').innerHTML = `<span class="text-orange-500 text-sm">Lote ${lote} no encontrado en la lista de productos</span>`;
            }
        } catch (e) {
            console.error('Error buscando lote:', e);
            abrirModal();
        }
    }

    // ── Guardar ──
    function guardarDeteccion() {
        if (!ultimaDeteccion) return;
        fetch('/ocr/guardar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: JSON.stringify(ultimaDeteccion)
        }).then(r => r.json()).then(d => {
            if (d.success) {
                document.getElementById('btn-guardar').classList.add('hidden');
                document.getElementById('btn-guardar').textContent = '✅ Guardado';
            }
        }).catch(e => console.error(e));
    }

    function guardarDesdeModal() {
        const nombre = document.getElementById('modal-nombre').value.trim();
        const fecha = document.getElementById('modal-fecha').value.trim();
        if (!nombre && !fecha) { alert('Ingresa al menos el nombre o la fecha.'); return; }
        const estado = calcularEstado(fecha);
        fetch('/ocr/guardar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: JSON.stringify({ nombre, fecha, estado })
        }).then(r => r.json()).then(d => {
            if (d.success) { agregarAlHistorial(nombre, fecha, estado); cerrarModal(); }
        }).catch(e => console.error(e));
    }

    // ── Limpiar ──
    function limpiarResultados() {
        document.getElementById('resultados').classList.add('hidden');
        document.getElementById('estado-ocr').classList.remove('hidden');
        document.getElementById('btn-guardar').classList.add('hidden');
        document.getElementById('badge-confianza').innerHTML = '';
        document.getElementById('badge-estado-resultado').textContent = 'Esperando...';
        document.getElementById('badge-estado-resultado').className = 'text-xs font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full';
        document.getElementById('campo-fecha').classList.remove('detectado');
        document.getElementById('campo-nombre').classList.remove('detectado');
        document.getElementById('preview-imagen').classList.add('hidden');
        ultimaDeteccion = null;
        procesando = false;
        cuentaRegresivaInterval = null;
        prevFrame = null;
    }

    // ── Modal manual ──
    function abrirModal() {
        document.getElementById('modal-nombre').value = '';
        document.getElementById('modal-fecha').value = '';
        document.getElementById('modal-manual').classList.add('visible');
        procesando = false;
    }
    function cerrarModal() {
        document.getElementById('modal-manual').classList.remove('visible');
        limpiarResultados();
    }
    function intentarDeNuevo() {
        document.getElementById('modal-manual').classList.remove('visible');
        limpiarResultados();
    }

    // ── Modal vencido ──
    function confirmarBaja() {
        if (!loteVencidoId) return;
        const observacion = document.getElementById('vencido-observacion').value;
        fetch('/ocr/dar-de-baja', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: JSON.stringify({ 
                lote_id: loteVencidoId,
                observacion: observacion 
            })
        }).then(r => r.json()).then(d => {
            if (d.success) { 
                cerrarModalVencido(); 
                if(confirm('✅ Lote dado de baja correctamente. ¿Deseas ver el historial de bajas ahora?')) {
                    window.location.href = "{{ route('historial-bajas.index') }}";
                }
            }
        }).catch(e => console.error(e));
    }
    function cerrarModalVencido() {
        document.getElementById('modal-vencido').classList.remove('visible');
        document.getElementById('vencido-observacion').value = '';
        loteVencidoId = null;
    }

    // ── Historial ──
    function agregarAlHistorial(nombre, fecha, estado) {
        const tbody = document.getElementById('historial-body');
        const ahora = new Date().toLocaleDateString('es-BO') + ' ' + new Date().toLocaleTimeString('es-BO', { hour: '2-digit', minute: '2-digit' });
        const estadoClase = { 'VIGENTE': 'bg-green-50 text-green-700', 'PROXIMO': 'bg-yellow-50 text-yellow-700', 'VENCIDO': 'bg-red-50 text-red-700', 'DESCONOCIDO': 'bg-gray-50 text-gray-500' };
        const estadoTexto = { 'VIGENTE': 'Vigente', 'PROXIMO': 'Por Vencer', 'VENCIDO': 'Vencido', 'DESCONOCIDO': 'Desconocido' };
        const fila = `<tr class="historial-row border-b border-gray-50 transition-colors">
            <td class="px-5 py-3.5 font-medium text-gray-800 text-sm">${nombre ?? '-'}</td>
            <td class="px-5 py-3.5 text-gray-600 text-sm">${fecha ?? '-'}</td>
            <td class="px-5 py-3.5"><span class="px-2.5 py-1 rounded-lg text-xs font-semibold ${estadoClase[estado] ?? estadoClase['DESCONOCIDO']}">${estadoTexto[estado] ?? 'Desconocido'}</span></td>
            <td class="px-5 py-3.5 text-gray-400 text-xs">${ahora}</td>
        </tr>`;
        if (tbody.querySelector('td[colspan]')) tbody.innerHTML = '';
        tbody.insertAdjacentHTML('afterbegin', fila);
    }

    // ── Calcular estado ──
    function calcularEstado(fecha) {
        if (!fecha) return 'DESCONOCIDO';
        try {
            let fechaObj;
            if (fecha.match(/^\d{2}\/\d{4}$/)) {
                const [m, y] = fecha.split('/');
                fechaObj = new Date(parseInt(y), parseInt(m), 0);
            } else if (fecha.match(/^\d{2}\/\d{2}\/\d{4}$/)) {
                const [d, m, y] = fecha.split('/');
                fechaObj = new Date(parseInt(y), parseInt(m) - 1, parseInt(d));
            } else return 'DESCONOCIDO';
            const diff = Math.floor((fechaObj - new Date()) / 86400000);
            if (diff < 0) return 'VENCIDO';
            if (diff <= 30) return 'PROXIMO';
            return 'VIGENTE';
        } catch { return 'DESCONOCIDO'; }
    }

    // ── Búsqueda Manual ──
    function abrirBajaManual() {
        document.getElementById('manual-search-nombre').value = '';
        document.getElementById('modal-baja-manual').classList.add('visible');
        setTimeout(() => document.getElementById('manual-search-nombre').focus(), 300);
    }

    function cerrarBajaManual() {
        document.getElementById('modal-baja-manual').classList.remove('visible');
    }

    async function buscarLoteManual() {
        const nombre = document.getElementById('manual-search-nombre').value.trim();
        if (!nombre) return;

        const loading = document.getElementById('manual-search-loading');
        loading.classList.remove('hidden');

        try {
            const res = await fetch('/ocr/buscar-lote-vencido', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
                },
                body: JSON.stringify({ nombre })
            });
            const data = await res.json();
            loading.classList.add('hidden');

            if (data.encontrado) {
                cerrarBajaManual();
                loteVencidoId = data.lote_id;
                document.getElementById('vencido-producto').textContent = data.producto;
                document.getElementById('vencido-lote').textContent = data.lote;
                document.getElementById('vencido-cantidad').textContent = data.cantidad + ' unidades';
                document.getElementById('vencido-fecha').textContent = data.fecha_venc;
                document.getElementById('modal-vencido-titulo').textContent = '⚠️ Lote Vencido Encontrado';
                document.getElementById('modal-vencido').classList.add('visible');
            } else {
                alert('No se encontró ningún lote VENCIDO con ese nombre.');
            }
        } catch (e) {
            loading.classList.add('hidden');
            alert('Error al buscar en el inventario.');
        }
    }

    // ── Init ──
    document.addEventListener('DOMContentLoaded', () => { iniciarWebcam(); });
    window.addEventListener('beforeunload', () => { detenerWebcam(); });
</script>
@endpush