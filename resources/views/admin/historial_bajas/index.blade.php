@extends('layouts.app')

@section('title', 'Historial de Vencidos')

@section('content')

<div class="bg-white rounded-lg shadow p-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">📋 Historial de Bajas por Vencimiento</h2>
            <p class="text-sm text-gray-400 mt-1">Registro de todos los lotes dados de baja del inventario</p>
        </div>
        <a href="{{ route('dashboard') }}"
            class="text-sm text-blue-600 hover:underline">← Volver al Dashboard</a>
    </div>

    <div class="relative overflow-x-auto rounded-lg border border-gray-200">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">Producto</th>
                    <th class="px-6 py-3">Lote</th>
                    <th class="px-6 py-3">Cantidad</th>
                    <th class="px-6 py-3">Fecha Vencimiento</th>
                    <th class="px-6 py-3">Motivo</th>
                    <th class="px-6 py-3">Dado de baja por</th>
                    <th class="px-6 py-3">Fecha Baja</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bajas as $baja)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-400">{{ $baja->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $baja->producto->nombre ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">
                            {{ $baja->lote }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $baja->cantidad }} uds.</td>
                    <td class="px-6 py-4 text-red-600 font-medium">
                        {{ $baja->fecha_vencimiento->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full capitalize">
                            {{ $baja->motivo }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-xs font-bold text-blue-600">
                                    {{ strtoupper(substr($baja->user->name ?? 'U', 0, 1)) }}
                                </span>
                            </div>
                            <span>{{ $baja->user->name ?? 'Desconocido' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-400">
                        {{ \Carbon\Carbon::parse($baja->fecha_baja)->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-400">
                        No hay registros de bajas aún.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($bajas->hasPages())
    <div class="mt-4">
        {{ $bajas->links() }}
    </div>
    @endif

</div>

@endsection