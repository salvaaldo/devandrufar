<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Alertas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
        }

        .header {
            background: linear-gradient(135deg, #7c1d1d, #dc2626);
            color: white;
            padding: 22px 30px;
            margin-bottom: 22px;
        }
        .header-top   { display: table; width: 100%; }
        .header-left  { display: table-cell; vertical-align: middle; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; }
        .company-name { font-size: 17px; font-weight: bold; }
        .company-sub  { font-size: 9.5px; color: rgba(255,255,255,0.65); margin-top: 3px; }
        .report-title { font-size: 13px; font-weight: bold; }
        .report-date  { font-size: 9px; color: rgba(255,255,255,0.65); margin-top: 3px; }

        /* Stats */
        .stats-row { display: table; width: calc(100% - 60px); margin: 0 30px 20px; }
        .stat-card {
            display: table-cell;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            vertical-align: middle;
            text-align: center;
        }
        .stat-card:first-child { margin-right: 10px; border-left: 4px solid #dc2626; }
        .stat-card:last-child  { border-left: 4px solid #f59e0b; }
        .stat-number { font-size: 26px; font-weight: bold; color: #1e293b; }
        .stat-label  { font-size: 8.5px; color: #64748b; margin-top: 3px; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Section title */
        .section-title {
            margin: 0 30px 10px;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .section-vencidos  { background: #fee2e2; color: #991b1b; }
        .section-porvencer { background: #fef9c3; color: #854d0e; margin-top: 24px; }

        .table-wrap { margin: 0 30px; }

        table { width: 100%; border-collapse: collapse; }

        thead tr { color: white; }
        thead.head-rojo tr  { background: #991b1b; }
        thead.head-ambar tr { background: #92400e; }
        thead th {
            padding: 8px 8px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        thead th.center { text-align: center; }

        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:nth-child(odd)  { background: #ffffff; }
        tbody tr { border-bottom: 1px solid #f1f5f9; }

        tbody td { padding: 7px 8px; font-size: 9px; color: #374151; vertical-align: middle; }
        tbody td.center { text-align: center; }

        .product-name { font-weight: bold; color: #111827; font-size: 9.5px; }
        .product-sub  { font-size: 8px; color: #9ca3af; margin-top: 2px; }

        .badge-code {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-qty-red {
            display: inline-block;
            background: #fef2f2;
            color: #dc2626;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-qty-amber {
            display: inline-block;
            background: #fffbeb;
            color: #b45309;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }

        .date-red   { color: #dc2626; font-weight: bold; }
        .date-amber { color: #b45309; font-weight: bold; }

        .dias-badge-red {
            display: inline-block;
            background: #fef2f2;
            color: #dc2626;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8.5px;
            font-weight: bold;
        }
        .dias-badge-amber {
            display: inline-block;
            background: #fffbeb;
            color: #b45309;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8.5px;
            font-weight: bold;
        }

        .empty { text-align: center; padding: 20px; color: #94a3b8; font-size: 10px; }

        .footer {
            margin-top: 28px;
            padding: 12px 30px;
            border-top: 1px solid #e2e8f0;
            display: table;
            width: 100%;
        }
        .footer-left  { display: table-cell; font-size: 9px; color: #94a3b8; font-style: italic; }
        .footer-right { display: table-cell; text-align: right; font-size: 9px; color: #94a3b8; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="header-top">
            <div class="header-left">
                <div class="company-name">Importadora Andrufar S.R.L.</div>
                <div class="company-sub">Sistema de Control de Vencimiento de Medicamentos</div>
            </div>
            <div class="header-right">
                <div class="report-title">Reporte de Alertas de Vencimiento</div>
                <div class="report-date">Generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card" style="border-left: 4px solid #dc2626; margin-right: 15px;">
            <div class="stat-number" style="color:#dc2626;">{{ $vencidos->count() }}</div>
            <div class="stat-label">Medicamentos Vencidos</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #f59e0b;">
            <div class="stat-number" style="color:#b45309;">{{ $porVencer->count() }}</div>
            <div class="stat-label">Próximos a Vencer (≤ 90 días)</div>
        </div>
    </div>

    <!-- ===== VENCIDOS ===== -->
    <div class="section-title section-vencidos">⚠ Medicamentos Vencidos</div>
    <div class="table-wrap">
        <table>
            <thead class="head-rojo">
                <tr>
                    <th style="width:4%">#</th>
                    <th style="width:10%">Código</th>
                    <th style="width:30%">Producto</th>
                    <th style="width:12%">Lote</th>
                    <th style="width:9%" class="center">Cant.</th>
                    <th style="width:13%">F. Vencimiento</th>
                    <th style="width:12%" class="center">Días Vencido</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vencidos as $index => $item)
                <tr>
                    <td style="color:#94a3b8; font-size:8px;">{{ $index + 1 }}</td>
                    <td><span class="badge-code">{{ $item->producto->codigo ?? '—' }}</span></td>
                    <td>
                        <div class="product-name">{{ strtoupper($item->producto->nombre ?? '—') }}</div>
                        @if($item->producto->concentracion ?? false)
                        <div class="product-sub">{{ $item->producto->concentracion }} · {{ $item->producto->forma_farmaceutica }}</div>
                        @endif
                    </td>
                    <td style="font-size:9px;">{{ $item->lote }}</td>
                    <td class="center"><span class="badge-qty-red">{{ $item->cantidad }}</span></td>
                    <td><span class="date-red">{{ $item->fecha_vencimiento->format('d/m/Y') }}</span></td>
                    <td class="center">
                        @php $dias = (int) \Carbon\Carbon::today()->diffInDays($item->fecha_vencimiento, false) * -1; @endphp
                        <span class="dias-badge-red">{{ $dias }} día(s)</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty">No hay medicamentos vencidos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ===== POR VENCER ===== -->
    <div class="section-title section-porvencer">⏰ Próximos a Vencer (dentro de 90 días)</div>
    <div class="table-wrap">
        <table>
            <thead class="head-ambar">
                <tr>
                    <th style="width:4%">#</th>
                    <th style="width:10%">Código</th>
                    <th style="width:30%">Producto</th>
                    <th style="width:12%">Lote</th>
                    <th style="width:9%" class="center">Cant.</th>
                    <th style="width:13%">F. Vencimiento</th>
                    <th style="width:12%" class="center">Días Restantes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($porVencer as $index => $item)
                <tr>
                    <td style="color:#94a3b8; font-size:8px;">{{ $index + 1 }}</td>
                    <td><span class="badge-code">{{ $item->producto->codigo ?? '—' }}</span></td>
                    <td>
                        <div class="product-name">{{ strtoupper($item->producto->nombre ?? '—') }}</div>
                        @if($item->producto->concentracion ?? false)
                        <div class="product-sub">{{ $item->producto->concentracion }} · {{ $item->producto->forma_farmaceutica }}</div>
                        @endif
                    </td>
                    <td style="font-size:9px;">{{ $item->lote }}</td>
                    <td class="center"><span class="badge-qty-amber">{{ $item->cantidad }}</span></td>
                    <td><span class="date-amber">{{ $item->fecha_vencimiento->format('d/m/Y') }}</span></td>
                    <td class="center">
                        @php $dias = (int) \Carbon\Carbon::today()->diffInDays($item->fecha_vencimiento, false); @endphp
                        <span class="dias-badge-amber">{{ $dias }} día(s)</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty">No hay medicamentos próximos a vencer.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-left">Documento generado automáticamente por el sistema Andrufar</div>
        <div class="footer-right">
            Vencidos: {{ $vencidos->count() }} | Por vencer: {{ $porVencer->count() }}
        </div>
    </div>

</body>
</html>
