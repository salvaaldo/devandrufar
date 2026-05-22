<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Bajas de Medicamentos</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; margin: 0; padding: 0; }
        .header { background: #1e293b; color: white; padding: 20px; text-align: center; }
        .company-name { font-size: 16px; font-weight: bold; margin-bottom: 5px; }
        .report-title { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
        .container { padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f1f5f9; color: #475569; padding: 8px; text-align: left; border-bottom: 2px solid #e2e8f0; font-size: 9px; text-transform: uppercase; }
        td { padding: 8px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        .date { color: #64748b; font-size: 9px; }
        .product-name { font-weight: bold; color: #111827; }
        .lote { font-family: monospace; background: #f8fafc; padding: 2px 4px; border-radius: 3px; }
        .footer { position: fixed; bottom: 20px; width: 100%; text-align: center; font-size: 8px; color: #94a3b8; }
        .obs { font-style: italic; color: #64748b; font-size: 9px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Importadora Andrufar S.R.L.</div>
        <div class="report-title">Historial Consolidado de Bajas de Medicamentos</div>
        <div style="font-size: 9px; margin-top: 5px; opacity: 0.8;">
            Generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }}
        </div>
    </div>

    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Fecha Baja</th>
                    <th>Producto</th>
                    <th>Lote</th>
                    <th>Cant.</th>
                    <th>Motivo / Observación</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bajas as $baja)
                <tr>
                    <td class="date">{{ $baja->fecha_baja->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="product-name">{{ $baja->producto->nombre }}</div>
                        <div style="font-size: 8px; color: #94a3b8;">{{ $baja->producto->codigo }}</div>
                    </td>
                    <td><span class="lote">{{ $baja->lote }}</span></td>
                    <td style="font-weight: bold; color: #dc2626;">{{ $baja->cantidad }}</td>
                    <td>
                        <div style="text-transform: capitalize; font-weight: 500;">{{ $baja->motivo }}</div>
                        @if($baja->observacion)
                            <div class="obs">"{{ $baja->observacion }}"</div>
                        @endif
                    </td>
                    <td>{{ $baja->user->name ?? 'Sistema' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        Documento de auditoría interna - Sistema Andrufar4 - Página 1
    </div>
</body>
</html>
