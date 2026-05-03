@extends('layouts.app')

@section('title', 'Alertas de Vencimiento')

@section('content')

<!-- Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Sistema de Alertas</h2>
            <p class="text-gray-500 text-sm mt-1">Monitoreo de fechas de vencimiento</p>
        </div>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver al Dashboard
        </a>
    </div>
</div>

<!-- Cards de Resumen -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    
    <!-- Card Vencidos -->
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="bg-white/20 rounded-lg p-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-red-100 text-sm font-medium">Medicamentos Vencidos</p>
                        <p class="text-4xl font-bold mt-1">{{ $conteo['vencidos'] }}</p>
                    </div>
                </div>
                <div class="bg-white/10 rounded-lg p-3 mt-4">
                    <p class="text-red-50 text-xs font-medium"> Requiere acción inmediata</p>
                    <p class="text-white/90 text-xs mt-1">Estos productos deben ser dados de baja del inventario</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Por Vencer -->
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="bg-white/20 rounded-lg p-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Por Vencer</p>
                        <p class="text-4xl font-bold mt-1">{{ $conteo['por_vencer'] }}</p>
                    </div>
                </div>
                <div class="bg-white/10 rounded-lg p-3 mt-4">
                    <p class="text-yellow-50 text-xs font-medium">Próximos 30 días</p>
                    <p class="text-white/90 text-xs mt-1">Planifica la rotación de estos productos</p>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Tabla de Vencidos -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <!-- Header de la tabla -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 rounded-lg p-2">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-bold text-lg">Medicamentos Vencidos</h3>
                    <p class="text-red-100 text-xs">{{ $conteo['vencidos'] }} productos requieren atención urgente</p>
                </div>
            </div>
            @if($conteo['vencidos'] > 0)
            <a href="{{ route('historial-bajas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-red-600 rounded-lg hover:bg-red-50 transition text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Registrar Bajas
            </a>
            @endif
        </div>
    </div>

    <!-- Contenido de la tabla -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Producto</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Código</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Lote</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Cantidad</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Fecha Vencimiento</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Días Vencido</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($vencidos as $item)
                <tr class="hover:bg-red-50 transition">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900">{{ $item->producto->nombre }}</p>
                        @if($item->producto->presentacion)
                        <p class="text-xs text-gray-500 mt-1">{{ $item->producto->presentacion }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $item->producto->codigo }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-medium text-gray-700">{{ $item->lote }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-700">
                            {{ $item->cantidad }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm font-semibold text-red-600">{{ $item->fecha_vencimiento->format('d/m/Y') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z" clip-rule="evenodd"/>
                            </svg>
                            {{ $item->fecha_vencimiento->diffInDays(now()) }} días
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12">
                        <div class="text-center">
                            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-lg font-semibold text-gray-500">¡Excelente!</p>
                            <p class="text-sm text-gray-400 mt-2">No hay medicamentos vencidos en este momento</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Tabla de Por Vencer -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <!-- Header de la tabla -->
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 rounded-lg p-2">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-bold text-lg">Por Vencer en los Próximos 30 Días</h3>
                    <p class="text-yellow-100 text-xs">{{ $conteo['por_vencer'] }} productos requieren seguimiento</p>
                </div>
            </div>
            @if($conteo['por_vencer'] > 0)
            <a href="{{ route('inventario.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-yellow-600 rounded-lg hover:bg-yellow-50 transition text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                </svg>
                Ver Inventario
            </a>
            @endif
        </div>
    </div>

    <!-- Contenido de la tabla -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Producto</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Código</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Lote</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Cantidad</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Fecha Vencimiento</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Estado</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($porVencer as $item)
                <tr class="hover:bg-yellow-50 transition">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900">{{ $item->producto->nombre }}</p>
                        @if($item->producto->presentacion)
                        <p class="text-xs text-gray-500 mt-1">{{ $item->producto->presentacion }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $item->producto->codigo }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-medium text-gray-700">{{ $item->lote }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-yellow-100 text-yellow-700">
                            {{ $item->cantidad }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm font-semibold text-yellow-600">{{ $item->fecha_vencimiento->format('d/m/Y') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $dias = now()->startOfDay()->diffInDays($item->fecha_vencimiento->startOfDay(), false);
                        @endphp

                        @if ($dias < 0)
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z" clip-rule="evenodd"/>
                                </svg>
                                Vencido hace {{ abs($dias) }} días
                            </span>
                        @elseif ($dias <= 7)
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z" clip-rule="evenodd"/>
                                </svg>
                                 {{ $dias }} días restantes
                            </span>
                        @elseif ($dias <= 15)
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-orange-100 text-orange-700">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                {{ $dias }} días restantes
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                {{ $dias }} días restantes
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12">
                        <div class="text-center">
                            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-lg font-semibold text-gray-500">Todo bajo control</p>
                            <p class="text-sm text-gray-400 mt-2">No hay productos próximos a vencer en los próximos 30 días</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection