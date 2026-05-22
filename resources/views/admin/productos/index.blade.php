@extends('layouts.app')

@section('title', 'Productos')

@section('content')
<div class="bg-white rounded-lg shadow p-6">

    <!-- Encabezado -->
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-700">Catálogo de Productos</h2>
        </div>
        <div class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
            <form action="{{ route('productos.index') }}" method="GET" class="flex items-center w-full md:w-80">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" placeholder="Buscar producto...">
                </div>
                <button type="submit" class="ml-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">Buscar</button>
                @if(request('search'))
                    <a href="{{ route('productos.index') }}" class="ml-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Limpiar</a>
                @endif
            </form>
            <div class="flex items-center gap-2">
                <a href="{{ route('productos.pdf') }}" target="_blank"
                    class="inline-flex items-center gap-1.5 text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-4 py-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Exportar PDF
                </a>
                <a href="{{ route('productos.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2">
                    + Nuevo Producto
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
                    <th class="px-6 py-3">Código</th>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Forma Farmacéutica</th>
                    <th class="px-6 py-3">Concentración</th>
                    <th class="px-6 py-3">Origen</th>
                    <th class="px-6 py-3">Marca</th>
                    <th class="px-6 py-3">Precio Ref. (Bs.)</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $producto->codigo }}</td>
                    <td class="px-6 py-4">{{ $producto->nombre }}</td>
                    <td class="px-6 py-4">{{ $producto->forma_farmaceutica ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $producto->concentracion ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $producto->origen ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $producto->marca ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $producto->precio_referencial ? 'Bs. ' . number_format($producto->precio_referencial, 2) : '-' }}</td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('productos.edit', $producto) }}" class="text-white bg-yellow-400 hover:bg-yellow-500 font-medium rounded-lg text-xs px-3 py-1.5">
                            Editar
                        </a>
                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" onsubmit="return confirm('¿Eliminar este producto?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-xs px-3 py-1.5">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-8 text-center text-gray-400">No hay productos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $productos->links() }}
    </div>

</div>
@endsection