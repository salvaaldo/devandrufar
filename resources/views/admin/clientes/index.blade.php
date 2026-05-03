@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="bg-white rounded-lg shadow p-6">

    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Gestión de Clientes</h2>
        <a href="{{ route('clientes.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2">
            + Nuevo Cliente
        </a>
    </div>

    <!-- Tabla -->
    <div class="relative overflow-x-auto rounded-lg border border-gray-200">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">Código</th>
                    <th class="px-6 py-3">Nombre / Razón Social</th>
                    <th class="px-6 py-3">NIT</th>
                    <th class="px-6 py-3">Teléfono</th>
                    <th class="px-6 py-3">Dirección</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientes as $cliente)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $cliente->codigo }}</td>
                    <td class="px-6 py-4">{{ $cliente->nombre }}</td>
                    <td class="px-6 py-4">{{ $cliente->nit ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $cliente->telefono ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $cliente->direccion ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $cliente->activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('clientes.edit', $cliente) }}" class="text-white bg-yellow-400 hover:bg-yellow-500 font-medium rounded-lg text-xs px-3 py-1.5">
                            Editar
                        </a>
                        <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" onsubmit="return confirm('¿Eliminar este cliente?')">
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
                    <td colspan="8" class="px-6 py-8 text-center text-gray-400">No hay clientes registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $clientes->links() }}
    </div>

</div>
@endsection