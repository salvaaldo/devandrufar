@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="bg-white rounded-lg shadow p-6">

    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Gestión de Usuarios</h2>
        <a href="{{ route('usuarios.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2">
            + Nuevo Usuario
        </a>
    </div>

    <!-- Tabla -->
    <div class="relative overflow-x-auto rounded-lg border border-gray-200">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">CI</th>
                    <th class="px-6 py-3">Correo</th>
                    <th class="px-6 py-3">Teléfono</th>
                    <th class="px-6 py-3">Rol</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $usuario->name }}</td>
                    <td class="px-6 py-4">{{ $usuario->ci }}</td>
                    <td class="px-6 py-4">{{ $usuario->email }}</td>
                    <td class="px-6 py-4">{{ $usuario->telefono ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $usuario->rol === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ ucfirst($usuario->rol) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $usuario->activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('usuarios.edit', $usuario) }}" class="text-white bg-yellow-400 hover:bg-yellow-500 font-medium rounded-lg text-xs px-3 py-1.5">
                            Editar
                        </a>
                        @if($usuario->id !== auth()->id())
                        <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" onsubmit="return confirm('¿Eliminar este usuario?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-xs px-3 py-1.5">
                                Eliminar
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-400">No hay usuarios registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $usuarios->links() }}
    </div>

</div>
@endsection