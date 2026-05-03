@extends('layouts.app')

@section('title', 'Nuevo Producto')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-3xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Nuevo Producto</h2>
        <a href="{{ route('productos.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
            ← Volver
        </a>
    </div>

    <form action="{{ route('productos.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Código propio -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Código del producto</label>
                <input type="text" name="codigo" value="{{ old('codigo') }}" placeholder="Ej: MNIIFA172"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('codigo') border-red-500 @enderror">
                @error('codigo')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buscar medicamento LINAME -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Medicamento LINAME</label>
                <select name="medicamento_id" id="medicamento_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('medicamento_id') border-red-500 @enderror">
                    <option value="">Buscar medicamento...</option>
                    @foreach(\App\Models\Medicamento::orderBy('nombre')->get() as $med)
                        <option value="{{ $med->id }}" {{ old('medicamento_id') == $med->id ? 'selected' : '' }}>
                            {{ $med->nombre }} - {{ $med->concentracion }}
                        </option>
                    @endforeach
                </select>
                @error('medicamento_id')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Datos jalados automáticamente -->
            <div class="md:col-span-2">
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4" id="datos-medicamento" style="display:none">
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-3">Datos jalados del LINAME</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div>
                            <p class="text-xs text-gray-400">Nombre</p>
                            <p class="text-sm font-medium text-gray-700" id="info-nombre">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Forma Farmacéutica</p>
                            <p class="text-sm font-medium text-gray-700" id="info-forma">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Concentración</p>
                            <p class="text-sm font-medium text-gray-700" id="info-conc">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Precio Referencial</p>
                            <p class="text-sm font-medium text-gray-700" id="info-precio">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Origen -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Origen <span class="text-gray-400">(opcional)</span></label>
                <input type="text" name="origen" value="{{ old('origen') }}" placeholder="Ej: NAL, IMP"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>

            <!-- Marca -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Marca <span class="text-gray-400">(opcional)</span></label>
                <input type="text" name="marca" value="{{ old('marca') }}" placeholder="Ej: IFA Prodexa"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>

        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-6 py-2.5">
                Guardar Producto
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#medicamento_id').select2({
            placeholder: 'Buscar medicamento...',
            allowClear: true,
            width: '100%'
        });

        $('#medicamento_id').on('change', function() {
            const id = $(this).val();
            if (!id) {
                document.getElementById('datos-medicamento').style.display = 'none';
                return;
            }

            fetch(`/productos/buscar-medicamento?id=${id}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('info-nombre').textContent = data.nombre ?? '-';
                    document.getElementById('info-forma').textContent = data.forma_farmaceutica ?? '-';
                    document.getElementById('info-conc').textContent = data.concentracion ?? '-';
                    document.getElementById('info-precio').textContent = data.precio_referencial ? 'Bs. ' + parseFloat(data.precio_referencial).toFixed(2) : '-';
                    document.getElementById('datos-medicamento').style.display = 'block';
                })
                .catch(() => {
                    document.getElementById('datos-medicamento').style.display = 'none';
                });
        });
    });
</script>
@endpush