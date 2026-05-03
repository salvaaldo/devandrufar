@extends('layouts.app')

@section('title', 'Importar LINAME')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Importar Lista LINAME</h2>
        <a href="{{ route('medicamentos.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
            ← Volver
        </a>
    </div>

    <!-- Información -->
    <div class="flex items-start p-4 mb-6 text-blue-800 rounded-lg bg-blue-50 border border-blue-200">
        <svg class="w-5 h-5 mr-3 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <div class="text-sm">
            <p class="font-semibold mb-1">Instrucciones de importación</p>
            <ul class="space-y-1 text-blue-700">
                <li>• Sube el archivo Excel de la Lista LINAME oficial.</li>
                <li>• El sistema importará automáticamente: código, nombre, forma farmacéutica, concentración y precio referencial.</li>
                <li>• Si un medicamento ya existe, sus datos serán actualizados.</li>
                <li>• Formato aceptado: <strong>.xlsx</strong> o <strong>.xls</strong></li>
            </ul>
        </div>
    </div>

    <!-- Formulario -->
    <form action="{{ route('medicamentos.procesar') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-6">
            <label class="block mb-2 text-sm font-medium text-gray-700">Archivo Excel LINAME</label>

            <!-- Zona de carga -->
            <div class="flex items-center justify-center w-full">
                <label for="archivo" class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 @error('archivo') border-red-500 @enderror">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6" id="zona-texto">
                        <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="mb-1 text-sm text-gray-500"><span class="font-semibold">Haz clic para subir</span> o arrastra el archivo</p>
                        <p class="text-xs text-gray-400">Excel (.xlsx, .xls) hasta 10MB</p>
                    </div>
                    <input id="archivo" name="archivo" type="file" accept=".xlsx,.xls" class="hidden">
                </label>
            </div>

            @error('archivo')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" id="btn-importar" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-6 py-2.5">
                Importar Medicamentos
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    // Mostrar nombre del archivo seleccionado
    document.getElementById('archivo').addEventListener('change', function() {
        const archivo = this.files[0];
        if (archivo) {
            document.getElementById('zona-texto').innerHTML = `
                <svg class="w-10 h-10 mb-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="mb-1 text-sm text-gray-700 font-semibold">${archivo.name}</p>
                <p class="text-xs text-gray-400">${(archivo.size / 1024).toFixed(1)} KB</p>
            `;
        }
    });

    // Mostrar cargando al enviar
    document.querySelector('form').addEventListener('submit', function() {
        const btn = document.getElementById('btn-importar');
        btn.disabled = true;
        btn.textContent = 'Importando...';
    });
</script>
@endpush