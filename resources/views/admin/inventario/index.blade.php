@extends('layouts.app')

@section('title', 'Lista de productos de empresa')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">

        <!-- Encabezado -->
        <!-- Encabezado -->
        <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-700">Lista de productos de empresa</h2>
            </div>
            <div class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
                <form action="{{ route('inventario.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-2 w-full">
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" placeholder="Buscar lote o producto...">
                    </div>
                    <select name="estado" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                        <option value="">Todos los estados</option>
                        <option value="vigente" {{ request('estado') == 'vigente' ? 'selected' : '' }}>Vigente</option>
                        <option value="por_vencer" {{ request('estado') == 'por_vencer' ? 'selected' : '' }}>Por Vencer</option>
                        <option value="vencido" {{ request('estado') == 'vencido' ? 'selected' : '' }}>Vencido</option>
                    </select>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">Filtrar</button>
                    @if(request('search') || request('estado'))
                        <a href="{{ route('inventario.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Limpiar</a>
                    @endif
                </form>
                <div class="flex items-center gap-2">
                    <a href="{{ route('inventario.pdf') }}" target="_blank"
                        class="inline-flex items-center gap-1.5 text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-4 py-2 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Exportar PDF
                    </a>
                    <a href="{{ route('inventario.create') }}"
                        class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2 whitespace-nowrap">
                        + Registrar Stock
                    </a>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="relative overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Producto</th>
                        <th class="px-6 py-3">Lote</th>
                        <th class="px-6 py-3">Cantidad</th>
                        <th class="px-6 py-3">Fecha Ingreso</th>
                        <th class="px-6 py-3">Fecha Vencimiento</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventarios as $item)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $item->producto->nombre ?? 'Sin producto' }}</p>
                                <p class="text-xs text-gray-400">{{ $item->producto->codigo ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">{{ $item->lote }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $item->cantidad }}</td>
                            <td class="px-6 py-4">{{ $item->fecha_ingreso->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ $item->fecha_vencimiento->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                @if ($item->estado === 'vigente')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Vigente</span>
                                @elseif($item->estado === 'por_vencer')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Por
                                        Vencer</span>
                                @else
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Vencido</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('inventario.destroy', $item) }}" method="POST"
                                    onsubmit="return confirm('¿Eliminar este registro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-xs px-3 py-1.5">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-400">No hay registros en el
                                esta lista de productos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $inventarios->links() }}
        </div>

    </div>
@endsection
