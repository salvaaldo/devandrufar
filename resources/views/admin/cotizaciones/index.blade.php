@extends('layouts.app')

@section('title', 'Cotizaciones')

@section('content')
<div class="p-6">

    {{-- Encabezado --}}
    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Cotizaciones</h1>
            <p class="text-sm text-gray-500">Gestión de cotizaciones a clientes</p>
        </div>
        <a href="{{ route('cotizaciones.create') }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva cotización
        </a>
    </div>

    {{-- Tarjetas de resumen --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total</p>
            <p class="text-2xl font-bold text-gray-800">{{ $cotizaciones->total() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Enviadas</p>
            <p class="text-2xl font-bold text-blue-600">{{ $cotizaciones->getCollection()->where('estado','enviada')->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Aprobadas</p>
            <p class="text-2xl font-bold text-green-600">{{ $cotizaciones->getCollection()->where('estado','aprobada')->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Rechazadas</p>
            <p class="text-2xl font-bold text-red-500">{{ $cotizaciones->getCollection()->where('estado','rechazada')->count() }}</p>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">N° Cotización</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Nombre</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Cliente</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Usuario</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Estado</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($cotizaciones as $cot)
                        <tr class="hover:bg-gray-50 transition-colors duration-100">

                            {{-- N° --}}
                            <td class="px-4 py-3 font-mono font-semibold text-blue-600 text-xs">
                                {{ $cot->numero }}
                            </td>

                            {{-- Nombre --}}
                            <td class="px-4 py-3 text-gray-800 font-medium max-w-[160px] truncate">
                                {{ $cot->nombre }}
                            </td>

                            {{-- Cliente --}}
                            <td class="px-4 py-3 text-gray-500">
                                {{ $cot->cliente->nombre ?? '—' }}
                            </td>

                            {{-- Usuario --}}
                            <td class="px-4 py-3 text-gray-500">
                                {{ $cot->user->name ?? '—' }}
                            </td>

                            {{-- Total --}}
                            <td class="px-4 py-3 font-semibold text-gray-800">
                                Bs. {{ number_format($cot->total, 2) }}
                            </td>

                            {{-- Estado con badge y punto de color --}}
                            <td class="px-4 py-3">
                                @php
                                    $badges = [
                                        'borrador'  => ['bg-gray-100 text-gray-500 border border-gray-200',   'bg-gray-400'],
                                        'enviada'   => ['bg-blue-50 text-blue-700 border border-blue-200',    'bg-blue-500'],
                                        'aprobada'  => ['bg-green-50 text-green-700 border border-green-200', 'bg-green-500'],
                                        'rechazada' => ['bg-red-50 text-red-600 border border-red-200',       'bg-red-500'],
                                    ];
                                    [$badgeClass, $dotClass] = $badges[$cot->estado] ?? $badges['borrador'];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                                    {{ ucfirst($cot->estado) }}
                                </span>
                            </td>

                            {{-- Fecha --}}
                            <td class="px-4 py-3 text-gray-400 text-xs">
                                {{ $cot->created_at->format('d/m/Y') }}
                            </td>

                            {{-- Acciones --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">

                                    {{-- Ver --}}
                                    <a href="{{ route('cotizaciones.show', $cot->id) }}"
                                        class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 hover:text-blue-600 border border-gray-200 hover:border-blue-300 bg-white hover:bg-blue-50 px-2.5 py-1 rounded-lg transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver
                                    </a>

                                    {{-- PDF --}}
                                    <a href="{{ route('cotizaciones.pdf', $cot->id) }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-xs font-medium text-red-500 hover:text-red-700 border border-red-200 hover:border-red-300 bg-white hover:bg-red-50 px-2.5 py-1 rounded-lg transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M7 21H17a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        PDF
                                    </a>

                                    {{-- Eliminar --}}
                                    <button onclick="eliminarCotizacion({{ $cot->id }}, '{{ $cot->numero }}')"
                                        class="inline-flex items-center justify-center text-gray-300 hover:text-red-500 hover:bg-red-50 border border-transparent hover:border-red-200 w-7 h-7 rounded-lg transition"
                                        title="Eliminar">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-2 text-gray-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm text-gray-400">No hay cotizaciones registradas aún.</p>
                                    <a href="{{ route('cotizaciones.create') }}" class="text-sm text-blue-500 hover:text-blue-700 font-medium mt-1">
                                        Crear la primera cotización →
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($cotizaciones->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
                <p class="text-xs text-gray-400">
                    Mostrando {{ $cotizaciones->firstItem() }}–{{ $cotizaciones->lastItem() }} de {{ $cotizaciones->total() }} cotizaciones
                </p>
                <div class="text-sm">
                    {{ $cotizaciones->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    function eliminarCotizacion(id, numero) {
        if (!confirm(`¿Eliminar la cotización ${numero}? Esta acción no se puede deshacer.`)) return;

        fetch(`/cotizaciones/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al eliminar: ' + data.error);
            }
        })
        .catch(() => alert('Error de conexión al eliminar.'));
    }
</script>
@endpush