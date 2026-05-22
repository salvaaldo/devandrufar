@extends('layouts.app')

@section('title', 'Reporte de Auditoría de Ventas')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Auditoría de Ventas</h1>
            <p class="text-sm text-gray-500 mt-1">Análisis detallado de ingresos y movimientos del sistema.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('reportes.ventas') }}" class="flex items-center gap-2">
                <select name="mes" class="bg-white border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 shadow-sm transition-all hover:border-gray-300">
                    @php
                        $meses = [
                            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                        ];
                    @endphp
                    @foreach($meses as $num => $nombre)
                        <option value="{{ $num }}" {{ $mes == $num ? 'selected' : '' }}>{{ $nombre }}</option>
                    @endforeach
                </select>
                
                <select name="anio" class="bg-white border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 shadow-sm transition-all hover:border-gray-300">
                    @for($i = now()->year; $i >= 2024; $i--)
                        <option value="{{ $i }}" {{ $anio == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded-xl transition shadow-md hover:shadow-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Filtrar
                </button>
            </form>

            <a href="{{ route('reportes.ventas.pdf', ['mes' => $mes, 'anio' => $anio]) }}" target="_blank"
                class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 px-5 rounded-xl transition shadow-md hover:shadow-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M7 21H17a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                PDF Oficial
            </a>
        </div>
    </div>

    {{-- Dashboard Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-6 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mb-1">Total Generado ({{ $meses[$mes] }})</p>
            <h3 class="text-4xl font-extrabold text-white tracking-tight">Bs. {{ number_format($totalGeneral, 2) }}</h3>
            <div class="mt-4 flex items-center text-blue-100 text-sm">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                <span>Ingresos brutos acumulados</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative group overflow-hidden">
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Transacciones</p>
            <h3 class="text-4xl font-extrabold text-gray-900 tracking-tight">{{ $ventas->count() }}</h3>
            <div class="mt-4 flex items-center text-green-500 text-sm font-medium">
                <span>Ventas registradas con éxito</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative group overflow-hidden">
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Promedio por Venta</p>
            <h3 class="text-4xl font-extrabold text-gray-900 tracking-tight">Bs. {{ $ventas->count() > 0 ? number_format($totalGeneral / $ventas->count(), 2) : '0.00' }}</h3>
            <div class="mt-4 flex items-center text-indigo-500 text-sm font-medium">
                <span>Ticket promedio mensual</span>
            </div>
        </div>
    </div>

    {{-- User Performance Section --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between bg-indigo-50/30">
            <h2 class="text-lg font-bold text-indigo-900">Productividad por Vendedor</h2>
            <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">Rendimiento en tiempo real</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/30">
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Usuario / Vendedor</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Hoy</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Esta Semana</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Este Mes</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Estatus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($usuariosStats as $u)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $u->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium uppercase">{{ $u->rol }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $u->total_dia > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }} font-bold text-sm">
                                {{ $u->total_dia }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg {{ $u->total_semana > 0 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-400' }} font-bold text-sm">
                                {{ $u->total_semana }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg {{ $u->total_mes > 0 ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-400' }} font-bold text-sm border border-indigo-100">
                                {{ $u->total_mes }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($u->total_dia > 0)
                                <span class="relative flex h-2 w-2 ml-auto">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                </span>
                                <span class="text-[10px] font-bold text-green-600 uppercase tracking-tighter">Activo hoy</span>
                            @else
                                <span class="text-[10px] font-bold text-gray-300 uppercase tracking-tighter">Sin actividad</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Main Audit Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-800">Desglose de Auditoría</h2>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase tracking-tighter">Historial completo</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">Fecha / Hora</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">N° Venta</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">Cliente</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">Vendedor</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">Estado</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 text-right">Monto Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($ventas as $venta)
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $venta->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $venta->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 font-mono text-blue-600 text-sm font-bold tracking-tight">
                                {{ $venta->numero }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-800 font-semibold">{{ $venta->cliente->nombre ?? 'Venta al Público' }}</div>
                                <div class="text-xs text-gray-400">{{ $venta->cliente->nit_ci ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-[10px] font-bold">
                                        {{ strtoupper(substr($venta->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="text-sm text-gray-600">{{ $venta->user->name ?? 'Sistema' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'aprobada'  => 'bg-green-100 text-green-700',
                                        'enviada'   => 'bg-blue-100 text-blue-700',
                                        'borrador'  => 'bg-gray-100 text-gray-600',
                                        'rechazada' => 'bg-red-100 text-red-700',
                                    ];
                                    $class = $statusClasses[$venta->estado] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-tighter {{ $class }}">
                                    {{ $venta->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-gray-900 text-sm">
                                Bs. {{ number_format($venta->total, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="text-gray-400 font-medium">No se encontraron movimientos en este periodo.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($ventas->count() > 0)
                    <tfoot>
                        <tr class="bg-gray-50/50 border-t-2 border-gray-100">
                            <td colspan="5" class="px-6 py-5 text-right text-xs font-black uppercase tracking-widest text-gray-400">Total Acumulado Auditoría:</td>
                            <td class="px-6 py-5 text-right text-xl font-black text-blue-700 tracking-tight">Bs. {{ number_format($totalGeneral, 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
