@extends('layouts.app')

@section('title', 'Cotización ' . $cotizacion->numero)

@section('content')
<div class="p-6">

    {{-- Encabezado --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('cotizaciones.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $cotizacion->numero }}</h1>
                <p class="text-sm text-gray-500">{{ $cotizacion->nombre }}</p>
            </div>
        </div>
        @php
            $colores = [
                'borrador'  => 'bg-gray-100 text-gray-600',
                'enviada'   => 'bg-blue-100 text-blue-700',
                'aprobada'  => 'bg-green-100 text-green-700',
                'rechazada' => 'bg-red-100 text-red-700',
                'anulada'   => 'bg-gray-800 text-gray-200',
            ];
            $color = $colores[$cotizacion->estado] ?? 'bg-gray-100 text-gray-600';
        @endphp
        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $color }}">
            {{ ucfirst($cotizacion->estado) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Info lateral --}}
        <div class="space-y-4">

            {{-- Datos generales --}}
            <div class="bg-white rounded-xl shadow p-5 space-y-3 text-sm">
                <h2 class="font-semibold text-gray-700 border-b pb-2">Datos Generales</h2>

                <div class="flex justify-between">
                    <span class="text-gray-500">N° Cotización</span>
                    <span class="font-mono font-bold text-blue-700">{{ $cotizacion->numero }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Fecha</span>
                    <span class="text-gray-700">{{ $cotizacion->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Creado por</span>
                    <span class="text-gray-700">{{ $cotizacion->user->name ?? '—' }}</span>
                </div>
            </div>

            {{-- Cliente --}}
            <div class="bg-white rounded-xl shadow p-5 space-y-3 text-sm">
                <h2 class="font-semibold text-gray-700 border-b pb-2">Cliente</h2>
                <div class="flex justify-between">
                    <span class="text-gray-500">Nombre</span>
                    <span class="text-gray-800 font-medium">{{ $cotizacion->cliente->nombre ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">NIT</span>
                    <span class="text-gray-700">{{ $cotizacion->cliente->nit ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Teléfono</span>
                    <span class="text-gray-700">{{ $cotizacion->cliente->telefono ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Dirección</span>
                    <span class="text-gray-700 text-right max-w-xs">{{ $cotizacion->cliente->direccion ?? '—' }}</span>
                </div>
            </div>

            {{-- Total --}}
            <div class="bg-blue-600 rounded-xl shadow p-5 text-white">
                <p class="text-sm font-medium opacity-80">Total Cotización</p>
                <p class="text-3xl font-bold mt-1">Bs. {{ number_format($cotizacion->total, 2) }}</p>
                <p class="text-xs opacity-70 mt-1">{{ $cotizacion->detalles->count() }} ítem(s)</p>
            </div>

            {{-- Observación --}}
            @if($cotizacion->observacion)
            <div class="bg-white rounded-xl shadow p-5 text-sm">
                <h2 class="font-semibold text-gray-700 border-b pb-2 mb-2">Observación</h2>
                <p class="text-gray-600">{{ $cotizacion->observacion }}</p>
            </div>
            @endif

        </div>

        {{-- Tabla de detalles --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-700">Medicamentos Cotizados</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-gray-500 font-semibold">N°</th>
                                <th class="px-3 py-2 text-left text-gray-500 font-semibold">Código</th>
                                <th class="px-3 py-2 text-left text-gray-500 font-semibold">Medicamento</th>
                                <th class="px-3 py-2 text-left text-gray-500 font-semibold">Concentración</th>
                                <th class="px-3 py-2 text-left text-gray-500 font-semibold">Forma</th>
                                <th class="px-3 py-2 text-left text-gray-500 font-semibold">Origen</th>
                                <th class="px-3 py-2 text-left text-gray-500 font-semibold">Marca</th>
                                <th class="px-3 py-2 text-left text-gray-500 font-semibold">Lote</th>
                                <th class="px-3 py-2 text-right text-gray-500 font-semibold">Cantidad</th>
                                <th class="px-3 py-2 text-right text-gray-500 font-semibold">P. Unit.</th>
                                <th class="px-3 py-2 text-right text-gray-500 font-semibold">P. Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($cotizacion->detalles as $det)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 font-semibold text-gray-500">{{ $det->nro_item }}</td>
                                <td class="px-3 py-2 font-mono text-blue-700">{{ $det->producto->codigo ?? '—' }}</td>
                                <td class="px-3 py-2 text-gray-800 font-medium">{{ $det->producto->nombre ?? '—' }}</td>
                                <td class="px-3 py-2 text-gray-600">{{ $det->producto->concentracion ?? '—' }}</td>
                                <td class="px-3 py-2 text-gray-600">{{ $det->producto->forma_farmaceutica ?? '—' }}</td>
                                <td class="px-3 py-2 text-gray-600">{{ $det->producto->origen ?? '—' }}</td>
                                <td class="px-3 py-2 text-gray-600">{{ $det->producto->marca ?? '—' }}</td>
                                <td class="px-3 py-2 text-gray-600">{{ $det->inventario->lote ?? '—' }}</td>
                                <td class="px-3 py-2 text-right font-semibold">{{ $det->cantidad }}</td>
                                <td class="px-3 py-2 text-right">Bs. {{ number_format($det->precio_unitario, 2) }}</td>
                                <td class="px-3 py-2 text-right font-bold text-green-700">Bs. {{ number_format($det->precio_total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                            <tr>
                                <td colspan="10" class="px-3 py-3 text-right font-bold text-gray-700">TOTAL</td>
                                <td class="px-3 py-3 text-right font-bold text-green-700 text-sm">
                                    Bs. {{ number_format($cotizacion->total, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection