@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
    }
    .dark-glass {
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(51, 65, 85, 0.5);
        border-radius: 20px;
    }
    .neon-border {
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.3), inset 0 0 10px rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.5);
    }
    .neon-border-red {
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.3), inset 0 0 10px rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.5);
    }
    .neon-border-yellow {
        box-shadow: 0 0 15px rgba(245, 158, 11, 0.3), inset 0 0 10px rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.5);
    }
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    .hero-banner {
        background-image: url('{{ asset("img/dashboard_hero.png") }}');
        background-size: cover;
        background-position: center;
        position: relative;
    }
    .hero-banner::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.7) 50%, rgba(15, 23, 42, 0.4) 100%);
        border-radius: 24px;
    }
    .content-relative { position: relative; z-index: 10; }
    
    /* Para que el fondo principal de la página (layout) no interfiera */
    .dashboard-wrapper {
        min-height: calc(100vh - 64px);
    }
</style>

<div class="dashboard-wrapper pb-10">

    <!-- Hero Banner -->
    <div class="hero-banner rounded-[24px] p-8 md:p-10 mb-8 shadow-2xl overflow-hidden mt-2 border border-gray-800">
        <div class="content-relative flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-2">
                    Panel de Control <span class="text-blue-400">Inteligente</span>
                </h1>
                <p class="text-gray-300 text-sm md:text-base max-w-xl leading-relaxed">
                    Sistema de visión artificial y gestión de productos PEPS. Bienvenido a la farmacia del futuro.
                </p>
            </div>
            <div class="mt-6 md:mt-0 glass-card px-5 py-3 flex items-center gap-3">
                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse shadow-[0_0_10px_#4ade80]"></div>
                <span class="text-white font-medium text-sm tracking-wide">Sistema en línea</span>
            </div>
        </div>

        <!-- Stats Cards flotantes sobre el banner -->
        <div class="content-relative grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            
            <!-- Total -->
            <div class="dark-glass p-6 neon-border group hover:-translate-y-2 transition-all duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Total Productos</p>
                        <h3 class="text-4xl font-black text-white">{{ $totalProductos }}</h3>
                    </div>
                    <div class="p-3 bg-blue-500/20 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM4 7V5a2 2 0 012-2h12a2 2 0 012 2v2"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-gray-400">
                    <span class="text-blue-400 mr-2 font-bold">100%</span> Productos en stock
                </div>
            </div>

            <!-- Por Vencer -->
            <div class="dark-glass p-6 neon-border-yellow group hover:-translate-y-2 transition-all duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Por Vencer</p>
                        <h3 class="text-4xl font-black text-white">{{ $conteo['por_vencer'] }}</h3>
                    </div>
                    <div class="p-3 bg-yellow-500/20 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-gray-400">
                    <span class="text-yellow-400 mr-2 font-bold">90 días</span> Requiere rotación PEPS
                </div>
            </div>

            <!-- Vencidos -->
            <div class="dark-glass p-6 neon-border-red group hover:-translate-y-2 transition-all duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Vencidos</p>
                        <h3 class="text-4xl font-black text-white">{{ $conteo['vencidos'] }}</h3>
                    </div>
                    <div class="p-3 bg-red-500/20 rounded-xl group-hover:scale-110 transition-transform animate-pulse">
                        <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-gray-400">
                    <span class="text-red-400 mr-2 font-bold">Peligro</span> Dar de baja urgente
                </div>
            </div>

        </div>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mt-10">

        <!-- Columna Izquierda: Acciones y Progreso -->
        <div class="xl:col-span-1 space-y-8">
            
            <!-- Acciones Rápidas (Estilo App Móvil) -->
            <div class="bg-white rounded-[20px] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-5">Acciones Rápidas</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('ocr.index') }}" class="flex flex-col items-center justify-center p-4 bg-gradient-to-b from-blue-50 to-blue-100/50 rounded-2xl hover:shadow-lg hover:-translate-y-1 transition-all group border border-blue-100/50">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm text-blue-600 group-hover:scale-110 transition-transform mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-gray-700">Detección OCR</span>
                    </a>

                    <a href="{{ route('productos.create') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-2xl hover:shadow-lg hover:-translate-y-1 transition-all group border border-gray-100">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm text-gray-600 group-hover:scale-110 transition-transform mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-gray-700">Nuevo Producto</span>
                    </a>

                    <a href="{{ route('historial-bajas.index') }}" class="flex flex-col items-center justify-center p-4 bg-red-50/50 rounded-2xl hover:shadow-lg hover:-translate-y-1 transition-all group border border-red-100/50">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm text-red-500 group-hover:scale-110 transition-transform mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-gray-700">Ver Bajas</span>
                    </a>

                    <a href="{{ route('inventario.index') }}" class="flex flex-col items-center justify-center p-4 bg-emerald-50/50 rounded-2xl hover:shadow-lg hover:-translate-y-1 transition-all group border border-emerald-100/50">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm text-emerald-600 group-hover:scale-110 transition-transform mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-gray-700">Lista de productos de empresa</span>
                    </a>
                </div>
            </div>

            <!-- Distribución de Inventario -->
            <div class="bg-white rounded-[20px] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 relative overflow-hidden">
                <!-- Decoración -->
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
                
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-6 relative z-10">Estado de Productos</h3>
                
                @php
                    $total = max($totalProductos, 1);
                    $pPV = $conteo['por_vencer'] / $total * 100;
                    $pVe = $conteo['vencidos'] / $total * 100;
                @endphp

                <div class="space-y-6 relative z-10">
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-semibold text-gray-700">Vigentes</span>
                            <span class="font-bold text-emerald-500">{{ number_format(100 - ($pPV + $pVe), 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-emerald-400 h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ 100 - ($pPV + $pVe) }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-semibold text-gray-700">Por Vencer</span>
                            <span class="font-bold text-yellow-500">{{ number_format($pPV, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-yellow-400 h-full rounded-full shadow-[0_0_10px_#facc15] transition-all duration-1000 ease-out" style="width: {{ $pPV }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-semibold text-gray-700">Vencidos</span>
                            <span class="font-bold text-red-500">{{ number_format($pVe, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-red-500 h-full rounded-full shadow-[0_0_10px_#ef4444] transition-all duration-1000 ease-out" style="width: {{ $pVe }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Tablas de Atención -->
        <div class="xl:col-span-2 space-y-8">
            
            <!-- Últimos Vencidos -->
            <div class="bg-white rounded-[20px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-red-100 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-50 bg-gradient-to-r from-red-50/50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Medicamentos Vencidos</h3>
                            <p class="text-xs text-red-500 font-medium">Acción requerida inmediata</p>
                        </div>
                    </div>
                    <a href="{{ route('reportes.vencidos.pdf') }}" target="_blank" class="flex items-center gap-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white px-4 py-2 rounded-xl text-xs font-bold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Generar PDF
                    </a>
                </div>
                
                <div class="p-2">
                    @forelse($vencidos as $item)
                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition-colors border-b border-gray-50 last:border-0">
                        <div class="flex items-center gap-4">
                            <div class="w-1.5 h-10 bg-red-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $item->producto->nombre ?? 'Sin nombre' }}</p>
                                <div class="flex gap-3 text-xs text-gray-500 mt-1">
                                    <span class="bg-gray-100 px-2 py-0.5 rounded-md">Lote: {{ $item->lote }}</span>
                                    <span>Stock: <strong class="text-gray-700">{{ $item->cantidad }}</strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right bg-red-50 px-4 py-2 rounded-xl border border-red-100">
                            <p class="text-xs text-red-400 font-medium uppercase tracking-wide mb-0.5">Venció el</p>
                            <p class="text-sm font-black text-red-600">{{ \Carbon\Carbon::parse($item->fecha_vencimiento)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="py-12 flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h4 class="text-gray-900 font-bold">¡Stock al día!</h4>
                        <p class="text-sm text-gray-500 mt-1">No tienes medicamentos vencidos actualmente.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Próximos a Vencer -->
            <div class="bg-white rounded-[20px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-yellow-100 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-50 bg-gradient-to-r from-yellow-50/50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Próximos a Vencer</h3>
                            <p class="text-xs text-yellow-600 font-medium">Vencen en los próximos 90 días</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-2">
                    @forelse($porVencer as $item)
                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition-colors border-b border-gray-50 last:border-0">
                        <div class="flex items-center gap-4">
                            <div class="w-1.5 h-10 bg-yellow-400 rounded-full"></div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $item->producto->nombre ?? 'Sin nombre' }}</p>
                                <div class="flex gap-3 text-xs text-gray-500 mt-1">
                                    <span class="bg-gray-100 px-2 py-0.5 rounded-md">Lote: {{ $item->lote }}</span>
                                    <span>Stock: <strong class="text-gray-700">{{ $item->cantidad }}</strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right bg-yellow-50 px-4 py-2 rounded-xl border border-yellow-100">
                            <p class="text-xs text-yellow-600 font-medium uppercase tracking-wide mb-0.5">Vence el</p>
                            <p class="text-sm font-black text-yellow-600">{{ \Carbon\Carbon::parse($item->fecha_vencimiento)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="py-12 flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="text-gray-900 font-bold">Sin alertas</h4>
                        <p class="text-sm text-gray-500 mt-1">No hay medicamentos próximos a vencer.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

@endsection