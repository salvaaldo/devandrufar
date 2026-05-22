@extends('layouts.app')

@section('title', 'Alertas de Vencimiento')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

    .alertas-wrap { font-family: 'DM Sans', sans-serif; }

    /* Header */
    .alertas-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .alertas-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #111827;
        letter-spacing: -0.02em;
    }
    .alertas-subtitle {
        font-size: 0.82rem;
        color: #9ca3af;
        margin-top: 0.2rem;
        font-weight: 400;
    }
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 500;
        color: #374151;
        text-decoration: none;
        transition: all 0.15s;
    }
    .btn-back:hover { background: #f9fafb; border-color: #d1d5db; }

    /* Summary cards */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    .summary-card {
        border-radius: 14px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .summary-card::before {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 100px; height: 100px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
    }
    .summary-card.vencidos { background: linear-gradient(135deg, #dc2626, #b91c1c); }
    .summary-card.por-vencer { background: linear-gradient(135deg, #d97706, #b45309); }

    .card-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255,255,255,0.7);
        margin-bottom: 0.5rem;
    }
    .card-number {
        font-size: 3rem;
        font-weight: 700;
        color: #fff;
        line-height: 1;
        font-family: 'DM Mono', monospace;
    }
    .card-desc {
        font-size: 0.78rem;
        color: rgba(255,255,255,0.65);
        margin-top: 0.75rem;
    }

    /* Section */
    .section-block {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #f0f0f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .section-head-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .section-icon {
        width: 36px; height: 36px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
    }
    .section-icon.red { background: #fef2f2; }
    .section-icon.yellow { background: #fffbeb; }
    .section-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #111827;
    }
    .section-count {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-top: 0.1rem;
    }
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.9rem;
        border-radius: 7px;
        font-size: 0.78rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.15s;
    }
    .btn-action.red { background: #fef2f2; color: #dc2626; }
    .btn-action.red:hover { background: #fee2e2; }
    .btn-action.yellow { background: #fffbeb; color: #d97706; }
    .btn-action.yellow:hover { background: #fef3c7; }

    /* Table */
    .data-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    .data-table thead tr { background: #fafafa; }
    .data-table th {
        padding: 0.75rem 1.25rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #9ca3af;
        border-bottom: 1px solid #f0f0f0;
    }
    .data-table th.center { text-align: center; }
    .data-table td {
        padding: 0.9rem 1.25rem;
        border-bottom: 1px solid #f9fafb;
        color: #374151;
    }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr:hover { background: #fafafa; }

    .product-name { font-weight: 600; color: #111827; font-size: 0.85rem; }
    .product-pres { font-size: 0.75rem; color: #9ca3af; margin-top: 2px; }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        font-family: 'DM Mono', monospace;
    }
    .badge-gray { background: #f3f4f6; color: #374151; }
    .badge-red { background: #fef2f2; color: #dc2626; }
    .badge-yellow { background: #fffbeb; color: #d97706; }
    .badge-orange { background: #fff7ed; color: #ea580c; }
    .badge-green { background: #f0fdf4; color: #16a34a; }

    .date-cell {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.83rem;
        font-family: 'DM Mono', monospace;
    }
    .date-cell.red { color: #dc2626; }
    .date-cell.yellow { color: #d97706; }

    /* Empty state */
    .empty-state {
        padding: 3.5rem 1.5rem;
        text-align: center;
    }
    .empty-icon {
        width: 56px; height: 56px;
        background: #f0fdf4;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem;
    }
    .empty-title { font-size: 0.95rem; font-weight: 600; color: #374151; }
    .empty-desc { font-size: 0.8rem; color: #9ca3af; margin-top: 0.3rem; }
</style>

<div class="alertas-wrap">

    <!-- Header -->
    <div class="alertas-header">
        <div>
            <h2 class="alertas-title">Sistema de Alertas</h2>
            <p class="alertas-subtitle">Monitoreo de fechas de vencimiento</p>
        </div>
        <div style="display:flex; align-items:center; gap:0.6rem;">
            <a href="{{ route('alertas.pdf') }}" target="_blank" class="btn-back" style="background:#dc2626; color:#fff; border-color:#dc2626;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportar PDF
            </a>
            <a href="{{ route('dashboard') }}" class="btn-back">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver al Dashboard
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid">
        <div class="summary-card vencidos">
            <p class="card-label">Medicamentos Vencidos</p>
            <p class="card-number">{{ $conteo['vencidos'] }}</p>
            <p class="card-desc">Requieren acción inmediata — dar de baja del sistema</p>
        </div>
        <div class="summary-card por-vencer">
            <p class="card-label">Por Vencer</p>
            <p class="card-number">{{ $conteo['por_vencer'] }}</p>
            <p class="card-desc">Próximos 90 días — planificar rotación de stock</p>
        </div>
    </div>

    <!-- Tabla Vencidos -->
    <div class="section-block">
        <div class="section-head">
            <div class="section-head-left">
                <div class="section-icon red">
                    <svg width="18" height="18" fill="none" stroke="#dc2626" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="section-title">Medicamentos Vencidos</p>
                    <p class="section-count">{{ $conteo['vencidos'] }} productos requieren atención urgente</p>
                </div>
            </div>
            @if($conteo['vencidos'] > 0)
            <a href="{{ route('historial-bajas.index') }}" class="btn-action red">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Registrar Bajas
            </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Código</th>
                        <th>Lote</th>
                        <th class="center">Cantidad</th>
                        <th>Fecha Vencimiento</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vencidos as $item)
                    <tr>
                        <td>
                            <p class="product-name">{{ $item->producto->nombre }}</p>
                            @if($item->producto->presentacion)
                            <p class="product-pres">{{ $item->producto->presentacion }}</p>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-gray">{{ $item->producto->codigo }}</span>
                        </td>
                        <td>
                            <span style="font-size:0.83rem; font-family:'DM Mono',monospace; color:#374151;">{{ $item->lote }}</span>
                        </td>
                        <td class="center">
                            <span class="badge badge-red">{{ $item->cantidad }}</span>
                        </td>
                        <td>
                            <div class="date-cell red">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $item->fecha_vencimiento->format('d/m/Y') }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg width="24" height="24" fill="none" stroke="#16a34a" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <p class="empty-title">¡Sin medicamentos vencidos!</p>
                                <p class="empty-desc">No hay productos vencidos en este momento</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla Por Vencer -->
    <div class="section-block">
        <div class="section-head">
            <div class="section-head-left">
                <div class="section-icon yellow">
                    <svg width="18" height="18" fill="none" stroke="#d97706" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="section-title">Por Vencer en los Próximos 90 Días</p>
                    <p class="section-count">{{ $conteo['por_vencer'] }} productos requieren seguimiento</p>
                </div>
            </div>
            @if($conteo['por_vencer'] > 0)
            <a href="{{ route('inventario.index') }}" class="btn-action yellow">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                </svg>
                Ver Productos
            </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Código</th>
                        <th>Lote</th>
                        <th class="center">Cantidad</th>
                        <th>Fecha Vencimiento</th>
                        <th class="center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($porVencer as $item)
                    <tr>
                        <td>
                            <p class="product-name">{{ $item->producto->nombre }}</p>
                            @if($item->producto->presentacion)
                            <p class="product-pres">{{ $item->producto->presentacion }}</p>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-gray">{{ $item->producto->codigo }}</span>
                        </td>
                        <td>
                            <span style="font-size:0.83rem; font-family:'DM Mono',monospace; color:#374151;">{{ $item->lote }}</span>
                        </td>
                        <td class="center">
                            <span class="badge badge-yellow">{{ $item->cantidad }}</span>
                        </td>
                        <td>
                            <div class="date-cell yellow">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $item->fecha_vencimiento->format('d/m/Y') }}
                            </div>
                        </td>
                        <td class="center">
                            @php
                                $dias = now()->startOfDay()->diffInDays($item->fecha_vencimiento->startOfDay(), false);
                            @endphp
                            @if($dias < 0)
                                <span class="badge badge-red">Vencido</span>
                            @elseif($dias <= 7)
                                <span class="badge badge-red">{{ $dias }}d restantes</span>
                            @elseif($dias <= 15)
                                <span class="badge badge-orange">{{ $dias }}d restantes</span>
                            @else
                                <span class="badge badge-yellow">{{ $dias }}d restantes</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg width="24" height="24" fill="none" stroke="#16a34a" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <p class="empty-title">Todo bajo control</p>
                                <p class="empty-desc">No hay productos de empresa próximos a vencer en los próximos 30 días</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection