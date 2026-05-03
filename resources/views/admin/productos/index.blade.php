@extends('layouts.app')

@section('title', 'Productos')

@section('content')
<div class="bg-white rounded-lg shadow p-6">

    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Catálogo de Productos</h2>
        <a href="{{ route('productos.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2">
            + Nuevo Producto
        </a>
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