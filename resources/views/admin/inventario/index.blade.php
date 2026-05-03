@extends('layouts.app')

@section('title', 'Inventario')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">

        <!-- Encabezado -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-700">Control de Inventario</h2>
            <a href="{{ route('inventario.create') }}"
                class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2">
                + Registrar Stock
            </a>
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
                                inventario.</td>
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
