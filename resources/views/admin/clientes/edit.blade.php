@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Editar Cliente</h2>
        <a href="{{ route('clientes.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
            ← Volver
        </a>
    </div>

    <form action="{{ route('clientes.update', $cliente) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Código -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Código</label>
                <input type="text" name="codigo" value="{{ old('codigo', $cliente->codigo) }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('codigo') border-red-500 @enderror">
                @error('codigo')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nombre -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Nombre / Razón Social</label>
                <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('nombre') border-red-500 @enderror">
                @error('nombre')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- NIT -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">NIT <span class="text-gray-400">(opcional)</span></label>
                <input type="text" name="nit" value="{{ old('nit', $cliente->nit) }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>

            <!-- Teléfono -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Teléfono <span class="text-gray-400">(opcional)</span></label>
                <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>

            <!-- Dirección -->
            <div class="md:col-span-2">
                <label class="block mb-1 text-sm font-medium text-gray-700">Dirección <span class="text-gray-400">(opcional)</span></label>
                <input type="text" name="direccion" value="{{ old('direccion', $cliente->direccion) }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>

            <!-- Estado -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Estado</label>
                <select name="activo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="1" {{ old('activo', $cliente->activo) ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ !old('activo', $cliente->activo) ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-6 py-2.5">
                Actualizar Cliente
            </button>
        </div>

    </form>
</div>
@endsection