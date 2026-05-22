<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Medicamentos Vencidos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            padding: 24px 30px;
            margin-bottom: 24px;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: -0.3px;
        }
        .company-sub {
            font-size: 10px;
            color: rgba(255,255,255,0.75);
            margin-top: 3px;
        }
        .report-info {
            text-align: right;
        }
        .report-title {
            font-size: 13px;
            font-weight: bold;
        }
        .report-date {
            font-size: 9px;
            color: rgba(255,255,255,0.75);
            margin-top: 3px;
        }

        /* Summary box */
        .summary {
            margin: 0 30px 20px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .summary-number {
            font-size: 32px;
            font-weight: bold;
            color: #dc2626;
            line-height: 1;
        }
        .summary-label {
            font-size: 12px;
            font-weight: bold;
            color: #991b1b;
        }
        .summary-desc {
            font-size: 10px;
            color: #b91c1c;
            margin-top: 3px;
        }

        /* Table */
        .table-wrap { margin: 0 30px; }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead tr {
            background: #1e293b;
            color: white;
        }
        thead th {
            padding: 9px 12px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        thead th.center { text-align: center; }

        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:nth-child(odd)  { background: #ffffff; }
        tbody tr { border-bottom: 1px solid #f1f5f9; }

        tbody td {
            padding: 9px 12px;
            font-size: 10px;
            color: #374151;
        }
        tbody td.center { text-align: center; }

        .product-name { font-weight: bold; color: #111827; font-size: 10.5px; }
        .product-pres { font-size: 9px; color: #9ca3af; margin-top: 2px; }

        .badge-code {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-qty {
            display: inline-block;
            background: #fef2f2;
            color: #dc2626;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        .date-red {
            color: #dc2626;
            font-weight: bold;
        }

        /* Empty state */
        .empty {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
            font-size: 11px;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding: 14px 30px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            color: #94a3b8;
        }
        .footer-note {
            font-style: italic;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="header-top">
            <div>
                <div class="company-name">Importadora Andrufar S.R.L.</div>
                <div class="company-sub">Sistema de Control de Vencimiento de Medicamentos</div>
            </div>
            <div class="report-info">
                <div class="report-title">Reporte de Medicamentos Vencidos</div>
                <div class="report-date">Generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Resumen -->
    <div class="summary">
        <div class="summary-number">{{ $vencidos->count() }}</div>
        <div>
            <div class="summary-label">Medicamentos Vencidos</div>
            <div class="summary-desc">Estos productos requieren baja inmediata de la lista de productos</div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Código</th>
                    <th>Lote</th>
                    <th class="center">Cantidad</th>
                    <th>Fecha Vencimiento</th>
                    <th>Fecha Ingreso</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vencidos as $index => $item)
                <tr>
                    <td style="color:#94a3b8; font-size:9px;">{{ $index + 1 }}</td>
                    <td>
                        <div class="product-name">{{ $item->producto->nombre }}</div>
                        @if($item->producto->presentacion)
                        <div class="product-pres">{{ $item->producto->presentacion }}</div>
                        @endif
                    </td>
                    <td><span class="badge-code">{{ $item->producto->codigo }}</span></td>
                    <td style="font-size:9.5px; color:#374151;">{{ $item->lote }}</td>
                    <td class="center"><span class="badge-qty">{{ $item->cantidad }}</span></td>
                    <td><span class="date-red">{{ $item->fecha_vencimiento->format('d/m/Y') }}</span></td>
                    <td style="color:#64748b;">{{ $item->fecha_ingreso ? $item->fecha_ingreso->format('d/m/Y') : '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty">No se encontraron medicamentos vencidos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <span class="footer-note">Documento generado automáticamente por el sistema andrufar4</span>
        <span>Total: {{ $vencidos->count() }} registro(s)</span>
    </div>

</body>
</html>