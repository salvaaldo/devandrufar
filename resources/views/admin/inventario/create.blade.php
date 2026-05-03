@extends('layouts.app')

@section('title', 'Registrar Stock')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-3xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Registrar Stock</h2>
        <a href="{{ route('inventario.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
            ← Volver
        </a>
    </div>

    <form action="{{ route('inventario.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Producto -->
            <div class="md:col-span-2">
                <label class="block mb-1 text-sm font-medium text-gray-700">Producto</label>
                <select name="producto_id" id="producto_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('producto_id') border-red-500 @enderror">
                    <option value="">Buscar producto...</option>
                    @foreach($productos as $producto)
                        <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                            {{ $producto->codigo }} - {{ $producto->nombre }} {{ $producto->concentracion ? '(' . $producto->concentracion . ')' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('producto_id')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Lote -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Número de Lote</label>
                <input type="text" name="lote" value="{{ old('lote') }}" placeholder="Ej: LOT-2024-001"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('lote') border-red-500 @enderror">
                @error('lote')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cantidad -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Cantidad</label>
                <input type="number" name="cantidad" value="{{ old('cantidad') }}" min="1" placeholder="Ej: 100"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('cantidad') border-red-500 @enderror">
                @error('cantidad')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fecha Ingreso -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Fecha de Ingreso</label>
                <input type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', date('Y-m-d')) }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('fecha_ingreso') border-red-500 @enderror">
                @error('fecha_ingreso')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fecha Vencimiento -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Fecha de Vencimiento</label>
                <input type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento') }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('fecha_vencimiento') border-red-500 @enderror">
                @error('fecha_vencimiento')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-6 py-2.5">
                Registrar Stock
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#producto_id').select2({
            placeholder: 'Buscar producto...',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush