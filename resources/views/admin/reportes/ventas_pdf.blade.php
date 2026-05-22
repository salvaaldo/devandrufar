<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Auditoría de Ventas</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; font-size: 11px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #3b82f6; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; color: #1e3a8a; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #666; font-size: 12px; }
        
        .summary-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; margin-bottom: 25px; border-radius: 8px; }
        .summary-box table { width: 100%; }
        .summary-label { font-weight: bold; color: #64748b; text-transform: uppercase; font-size: 9px; }
        .summary-value { font-size: 18px; font-weight: bold; color: #1e40af; }
        
        table.main { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.main th { background: #f1f5f9; color: #475569; padding: 10px; text-align: left; border-bottom: 2px solid #cbd5e1; text-transform: uppercase; font-size: 9px; }
        table.main td { padding: 10px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .status-badge { padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: bold; text-transform: uppercase; background: #e2e8f0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Andrufar S.R.L.</h1>
        <p>Reporte de Auditoría de Ventas - {{ $mes == 1 ? 'Enero' : ($mes == 2 ? 'Febrero' : ($mes == 3 ? 'Marzo' : ($mes == 4 ? 'Abril' : ($mes == 5 ? 'Mayo' : ($mes == 6 ? 'Junio' : ($mes == 7 ? 'Julio' : ($mes == 8 ? 'Agosto' : ($mes == 9 ? 'Septiembre' : ($mes == 10 ? 'Octubre' : ($mes == 11 ? 'Noviembre' : 'Diciembre')))))))))) }} {{ $anio }}</p>
    </div>

    <div class="summary-box">
        <table>
            <tr>
                <td width="50%">
                    <span class="summary-label">Total Generado en el Periodo:</span><br>
                    <span class="summary-value">Bs. {{ number_format($totalGeneral, 2) }}</span>
                </td>
                <td width="25%">
                    <span class="summary-label">Transacciones:</span><br>
                    <span class="font-bold" style="font-size: 14px;">{{ $ventas->count() }}</span>
                </td>
                <td width="25%">
                    <span class="summary-label">Fecha de Emisión:</span><br>
                    <span class="font-bold">{{ now()->format('d/m/Y H:i') }}</span>
                </td>
            </tr>
        </table>
    </div>

    <table class="main">
        <thead>
            <tr>
                <th width="12%">Fecha</th>
                <th width="15%">N° Cotización</th>
                <th width="30%">Cliente</th>
                <th width="15%">Vendedor</th>
                <th width="13%">Estado</th>
                <th width="15%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
                <tr>
                    <td>{{ $venta->created_at->format('d/m/Y') }}</td>
                    <td class="font-bold">{{ $venta->numero }}</td>
                    <td>
                        {{ $venta->cliente->nombre ?? 'Venta al Público' }}<br>
                        <span style="font-size: 8px; color: #777;">{{ $venta->cliente->nit_ci ?? '' }}</span>
                    </td>
                    <td>{{ $venta->user->name ?? 'Sistema' }}</td>
                    <td><span class="status-badge">{{ $venta->estado }}</span></td>
                    <td class="text-right font-bold">Bs. {{ number_format($venta->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f8fafc;">
                <td colspan="5" class="text-right font-bold" style="padding: 15px; font-size: 12px;">TOTAL ACUMULADO:</td>
                <td class="text-right font-bold" style="padding: 15px; font-size: 14px; color: #1e40af;">Bs. {{ number_format($totalGeneral, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Este documento es un reporte oficial de auditoría generado por el sistema Andrufar. Página 1 de 1
    </div>
</body>
</html>
