@extends('layouts.app')

@section('title', 'Medicamentos')

@section('content')
<div class="bg-white rounded-lg shadow p-6">

    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Lista de Medicamentos</h2>
        <a href="{{ route('medicamentos.importar') }}" class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-4 py-2">
            ↑ Importar LINAME
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
                    <th class="px-6 py-3">Precio Ref. (Bs.)</th>
                    <th class="px-6 py-3">Aclaración</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicamentos as $medicamento)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $medicamento->codigo }}</td>
                    <td class="px-6 py-4">{{ $medicamento->nombre }}</td>
                    <td class="px-6 py-4">{{ $medicamento->forma_farmaceutica ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $medicamento->concentracion ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $medicamento->precio_referencial ? 'Bs. ' . number_format($medicamento->precio_referencial, 2) : '-' }}</td>
                    <td class="px-6 py-4">{{ $medicamento->aclaracion ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                        No hay medicamentos. 
                        <a href="{{ route('medicamentos.importar') }}" class="text-blue-600 hover:underline">Importar LINAME</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $medicamentos->links() }}
    </div>

</div>
@endsection