@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- Header del Dashboard -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800">Panel de Control</h2>
    <p class="text-gray-500 text-sm mt-1">Resumen general del sistema de inventario</p>
</div>

<!-- Grid de Estadísticas Principales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <!-- Total Productos -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Productos</p>
                <p class="text-4xl font-bold mt-2">{{ $totalProductos }}</p>
                <p class="text-blue-100 text-xs mt-2">En inventario</p>
            </div>
            <div class="bg-white/20 rounded-full p-4">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                    <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Medicamentos Vigentes -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Vigentes</p>
                <p class="text-4xl font-bold mt-2">{{ $conteo['vigentes'] ?? 0 }}</p>
                <p class="text-green-100 text-xs mt-2">En buen estado</p>
            </div>
            <div class="bg-white/20 rounded-full p-4">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Por Vencer -->
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium">Por Vencer</p>
                <p class="text-4xl font-bold mt-2">{{ $conteo['por_vencer'] }}</p>
                <p class="text-yellow-100 text-xs mt-2">Próximos 30 días</p>
            </div>
            <div class="bg-white/20 rounded-full p-4">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Vencidos -->
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm font-medium">Vencidos</p>
                <p class="text-4xl font-bold mt-2">{{ $conteo['vencidos'] }}</p>
                <p class="text-red-100 text-xs mt-2">Requieren acción</p>
            </div>
            <div class="bg-white/20 rounded-full p-4">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

</div>

<!-- Acciones Rápidas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    
    <a href="{{ route('productos.create') }}" 
       class="bg-white hover:bg-gray-50 rounded-lg shadow p-4 flex items-center gap-4 transition border border-gray-200">
        <div class="bg-blue-100 rounded-lg p-3">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <div>
            <p class="font-semibold text-gray-800">Nuevo Producto</p>
            <p class="text-xs text-gray-500">Agregar al inventario</p>
        </div>
    </a>

    <a href="{{ route('ocr.index') }}" 
       class="bg-white hover:bg-gray-50 rounded-lg shadow p-4 flex items-center gap-4 transition border border-gray-200">
        <div class="bg-purple-100 rounded-lg p-3">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
            </svg>
        </div>
        <div>
            <p class="font-semibold text-gray-800">Detección OCR</p>
            <p class="text-xs text-gray-500">Escanear medicamentos</p>
        </div>
    </a>

    <a href="{{ route('historial-bajas.index') }}" 
       class="bg-white hover:bg-gray-50 rounded-lg shadow p-4 flex items-center gap-4 transition border border-gray-200">
        <div class="bg-red-100 rounded-lg p-3">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div>
            <p class="font-semibold text-gray-800">Historial Vencidos</p>
            <p class="text-xs text-gray-500">Ver registros de bajas</p>
        </div>
    </a>

</div>

<!-- Grid de Gráficos y Tablas -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Distribución de Estado (2/3 del ancho) -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Distribución de Estado</h3>
                <p class="text-sm text-gray-500">Estado actual del inventario</p>
            </div>
            <a href="{{ route('inventario.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                Ver inventario →
            </a>
        </div>

        <!-- Barra de progreso visual -->
        <div class="space-y-4">
            
            <!-- Vigentes -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
                        <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                        Vigentes
                    </span>
                    <span class="text-sm font-bold text-gray-800">{{ $conteo['vigentes'] ?? 0 }} productos</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    @php
                        $total = max($totalProductos, 1);
                        $porcentajeVigentes = ($conteo['vigentes'] ?? 0) / $total * 100;
                    @endphp
                    <div class="bg-green-500 h-3 rounded-full transition-all" style="width: {{ $porcentajeVigentes }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ number_format($porcentajeVigentes, 1) }}% del total</p>
            </div>

            <!-- Por Vencer -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
                        <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                        Por Vencer
                    </span>
                    <span class="text-sm font-bold text-gray-800">{{ $conteo['por_vencer'] }} productos</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    @php
                        $porcentajePorVencer = $conteo['por_vencer'] / $total * 100;
                    @endphp
                    <div class="bg-yellow-500 h-3 rounded-full transition-all" style="width: {{ $porcentajePorVencer }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ number_format($porcentajePorVencer, 1) }}% del total</p>
            </div>

            <!-- Vencidos -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
                        <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                        Vencidos
                    </span>
                    <span class="text-sm font-bold text-gray-800">{{ $conteo['vencidos'] }} productos</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    @php
                        $porcentajeVencidos = $conteo['vencidos'] / $total * 100;
                    @endphp
                    <div class="bg-red-500 h-3 rounded-full transition-all" style="width: {{ $porcentajeVencidos }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ number_format($porcentajeVencidos, 1) }}% del total</p>
            </div>

        </div>

        <!-- Resumen numérico -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($porcentajeVigentes, 1) }}%</p>
                    <p class="text-xs text-gray-500 mt-1">Óptimo</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($porcentajePorVencer, 1) }}%</p>
                    <p class="text-xs text-gray-500 mt-1">Atención</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($porcentajeVencidos, 1) }}%</p>
                    <p class="text-xs text-gray-500 mt-1">Crítico</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas Recientes (1/3 del ancho) -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Alertas</h3>
                <p class="text-sm text-gray-500">Requieren atención</p>
            </div>
            <a href="{{ route('alertas.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                Ver todas →
            </a>
        </div>

        <div class="space-y-3">
            @if($conteo['vencidos'] > 0)
            <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-red-800">{{ $conteo['vencidos'] }} Vencidos</p>
                        <p class="text-xs text-red-600">Dar de baja urgente</p>
                    </div>
                </div>
            </div>
            @endif

            @if($conteo['por_vencer'] > 0)
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 rounded">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-yellow-800">{{ $conteo['por_vencer'] }} Por Vencer</p>
                        <p class="text-xs text-yellow-600">Próximos 30 días</p>
                    </div>
                </div>
            </div>
            @endif

            @if($conteo['vencidos'] == 0 && $conteo['por_vencer'] == 0)
            <div class="bg-green-50 border-l-4 border-green-500 p-3 rounded">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-green-800">Todo en orden</p>
                        <p class="text-xs text-green-600">Sin alertas pendientes</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>

<!-- Tablas de Detalle -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

    <!-- Últimos Vencidos -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 rounded-lg p-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-white font-bold">Últimos Vencidos</h3>
                </div>
                <a href="{{ route('alertas.index') }}" class="text-white/90 hover:text-white text-sm font-medium">
                    Ver todos →
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="space-y-3">
                @forelse($vencidos as $item)
                <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-100 hover:bg-red-100 transition">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ $item->producto->nombre }}</p>
                        <p class="text-xs text-gray-500 mt-1">Lote: <span class="font-medium">{{ $item->lote }}</span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Vencido</p>
                        <p class="text-sm font-bold text-red-600">{{ $item->fecha_vencimiento->format('d/m/Y') }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-gray-500 font-medium">¡Excelente!</p>
                    <p class="text-xs text-gray-400 mt-1">No hay medicamentos vencidos</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Próximos a Vencer -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 rounded-lg p-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-white font-bold">Próximos a Vencer</h3>
                </div>
                <a href="{{ route('alertas.index') }}" class="text-white/90 hover:text-white text-sm font-medium">
                    Ver todos →
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="space-y-3">
                @forelse($porVencer as $item)
                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border border-yellow-100 hover:bg-yellow-100 transition">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ $item->producto->nombre }}</p>
                        <p class="text-xs text-gray-500 mt-1">Lote: <span class="font-medium">{{ $item->lote }}</span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Vence</p>
                        <p class="text-sm font-bold text-yellow-600">{{ $item->fecha_vencimiento->format('d/m/Y') }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-gray-500 font-medium">Todo bajo control</p>
                    <p class="text-xs text-gray-400 mt-1">No hay productos por vencer pronto</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection