@extends('layouts.app')

@section('title', 'Historial de Detecciones OCR')

@section('content')
<div class="bg-white rounded-lg shadow p-6">

    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Historial de Detecciones OCR</h2>
        <a href="{{ route('ocr.index') }}"
            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2">
            + Nueva Detección
        </a>
    </div>

    <!-- Tabla -->
    <div class="relative overflow-x-auto rounded-lg border border-gray-200">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">Nombre Detectado</th>
                    <th class="px-6 py-3">Lote</th>
                    <th class="px-6 py-3">Fecha Detectada</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3">Usuario</th>
                    <th class="px-6 py-3">Fecha Detección</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detecciones as $deteccion)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $deteccion->nombre_detectado ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($deteccion->lote)
                            <span class="text-xs bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full font-bold border border-blue-200">
                                {{ $deteccion->lote }}
                            </span>
                        @else
                            <span class="text-gray-300">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $deteccion->fecha_detectada ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($deteccion->estado === 'VIGENTE')
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Vigente</span>
                        @elseif($deteccion->estado === 'PROXIMO')
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Por Vencer</span>
                        @elseif($deteccion->estado === 'VENCIDO')
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Vencido</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">Desconocido</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $deteccion->user->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $deteccion->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">No hay detecciones registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $detecciones->links() }}
    </div>

</div>
@endsection