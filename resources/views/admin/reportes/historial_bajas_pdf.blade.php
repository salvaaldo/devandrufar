<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Bajas por Vencimiento</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
        }

        .header {
            background: linear-gradient(135deg, #1e293b, #334155);
            color: white;
            padding: 22px 30px;
            margin-bottom: 22px;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .company-name { font-size: 17px; font-weight: bold; }
        .company-sub  { font-size: 9.5px; color: rgba(255,255,255,0.65); margin-top: 3px; }
        .report-title { font-size: 13px; font-weight: bold; text-align: right; }
        .report-date  { font-size: 9px; color: rgba(255,255,255,0.65); margin-top: 3px; text-align: right; }

        .summary {
            margin: 0 30px 20px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #1e293b;
            border-radius: 6px;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .summary-number { font-size: 30px; font-weight: bold; color: #1e293b; line-height: 1; }
        .summary-label  { font-size: 12px; font-weight: bold; color: #1e293b; }
        .summary-desc   { font-size: 10px; color: #64748b; margin-top: 3px; }

        .table-wrap { margin: 0 30px; }

        table { width: 100%; border-collapse: collapse; }

        thead tr { background: #1e293b; color: white; }
        thead th {
            padding: 8px 10px;
            text-align: left;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        thead th.center { text-align: center; }

        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:nth-child(odd)  { background: #ffffff; }
        tbody tr { border-bottom: 1px solid #f1f5f9; }

        tbody td { padding: 8px 10px; font-size: 9.5px; color: #374151; vertical-align: top; }
        tbody td.center { text-align: center; }

        .product-name { font-weight: bold; color: #111827; font-size: 10px; }
        .product-pres { font-size: 8.5px; color: #9ca3af; margin-top: 2px; }

        .badge-code {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8.5px;
            font-weight: bold;
        }
        .badge-qty {
            display: inline-block;
            background: #fef2f2;
            color: #dc2626;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        .date-red  { color: #dc2626; font-weight: bold; }
        .date-gray { color: #64748b; }
        .motivo-badge {
            display: inline-block;
            background: #fef9c3;
            color: #854d0e;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8.5px;
            font-weight: bold;
        }
        .user-name { color: #3b82f6; font-weight: 600; }
        .obs-text  { font-size: 8.5px; color: #6b7280; font-style: italic; }

        .empty { text-align: center; padding: 40px; color: #94a3b8; font-size: 11px; }

        .footer {
            margin-top: 28px;
            padding: 12px 30px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #94a3b8;
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
            <div>
                <div class="report-title">Historial de Bajas por Vencimiento</div>
                <div class="report-date">Generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Resumen -->
    <div class="summary">
        <div class="summary-number">{{ $bajas->count() }}</div>
        <div>
            <div class="summary-label">Registros de Bajas</div>
            <div class="summary-desc">Medicamentos dados de baja por vencimiento</div>
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
                    <th class="center">Cant.</th>
                    <th>F. Vencimiento</th>
                    <th>F. Baja</th>
                    <th>Motivo</th>
                    <th>Responsable</th>
                    <th>Observación</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bajas as $index => $item)
                <tr>
                    <td style="color:#94a3b8; font-size:8.5px;">{{ $index + 1 }}</td>
                    <td>
                        <div class="product-name">{{ $item->producto->nombre }}</div>
                        @if($item->producto->presentacion)
                        <div class="product-pres">{{ $item->producto->presentacion }}</div>
                        @endif
                    </td>
                    <td><span class="badge-code">{{ $item->producto->codigo }}</span></td>
                    <td style="font-size:9px;">{{ $item->lote }}</td>
                    <td class="center"><span class="badge-qty">{{ $item->cantidad }}</span></td>
                    <td><span class="date-red">{{ $item->fecha_vencimiento->format('d/m/Y') }}</span></td>
                    <td><span class="date-gray">{{ $item->fecha_baja->format('d/m/Y') }}</span></td>
                    <td>
                        @if($item->motivo)
                        <span class="motivo-badge">{{ $item->motivo }}</span>
                        @else
                        <span style="color:#d1d5db">—</span>
                        @endif
                    </td>
                    <td><span class="user-name">{{ $item->user->name ?? '—' }}</span></td>
                    <td>
                        @if($item->observacion)
                        <span class="obs-text">{{ $item->observacion }}</span>
                        @else
                        <span style="color:#d1d5db">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="empty">No se encontraron registros de bajas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <span style="font-style:italic">Documento generado automáticamente por el sistema andrufar4</span>
        <span>Total: {{ $bajas->count() }} registro(s)</span>
    </div>

</body>
</html>