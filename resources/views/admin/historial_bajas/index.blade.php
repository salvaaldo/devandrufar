@extends('layouts.app')

@section('title', 'Bajas de Medicamentos')

@section('content')

<div class="bg-white rounded-lg shadow p-6">

    <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">📋 Registro de Bajas de Medicamentos</h2>
            <p class="text-sm text-gray-400 mt-1">Registro de todos los lotes eliminados de la lista de productos</p>
        </div>
        <div class="flex items-center gap-4 w-full md:w-auto">
            <form action="{{ route('historial-bajas.index') }}" method="GET" class="flex items-center w-full md:w-80">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" placeholder="Buscar por lote o producto...">
                </div>
                <button type="submit" class="ml-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">Buscar</button>
                @if(request('search'))
                    <a href="{{ route('historial-bajas.index') }}" class="ml-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Limpiar</a>
                @endif
            </form>
            <a href="{{ route('historial-bajas.pdf') }}" target="_blank"
                class="inline-flex items-center gap-1.5 text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-4 py-2 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportar PDF
            </a>
            <a href="{{ route('dashboard') }}" class="text-sm text-blue-600 hover:underline whitespace-nowrap">← Volver</a>
        </div>
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
                        No hay registros en esta lista aún.
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