<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de productos de empresa</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
        }

        .header {
            background: linear-gradient(135deg, #1e40af, #1e3a8a);
            color: white;
            padding: 30px;
            margin-bottom: 25px;
        }
        .header-top   { display: table; width: 100%; }
        .header-left  { display: table-cell; vertical-align: middle; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; }
        .company-name { font-size: 18px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .company-sub  { font-size: 10px; color: rgba(255,255,255,0.7); margin-top: 4px; }
        .report-title { font-size: 14px; font-weight: bold; }
        .report-date  { font-size: 9px; color: rgba(255,255,255,0.7); margin-top: 4px; }

        .summary-card {
            margin: 0 30px 25px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px 25px;
            display: table;
            width: calc(100% - 60px);
        }
        .summary-item { display: table-cell; vertical-align: middle; }
        .summary-label { font-size: 9px; color: #64748b; text-transform: uppercase; font-weight: bold; }
        .summary-value { font-size: 20px; font-weight: bold; color: #1e3a8a; }

        .table-wrap { margin: 0 30px; }

        table { width: 100%; border-collapse: collapse; }

        thead tr { background: #f1f5f9; }
        thead th {
            padding: 12px 10px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
            border-bottom: 2px solid #e2e8f0;
        }
        thead th.center { text-align: center; }

        tbody tr { border-bottom: 1px solid #f1f5f9; }
        tbody tr:nth-child(even) { background: #fcfdfe; }

        tbody td { padding: 12px 10px; font-size: 10px; color: #334155; vertical-align: middle; }
        tbody td.center { text-align: center; }

        .product-code { font-family: monospace; color: #1e40af; font-weight: bold; font-size: 10px; }
        .product-name { font-weight: bold; color: #0f172a; font-size: 11px; }
        .product-sub  { font-size: 9px; color: #64748b; margin-top: 2px; }

        .stock-badge {
            background: #dbeafe;
            color: #1e40af;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 11px;
            display: inline-block;
        }

        .empty { text-align: center; padding: 50px; color: #94a3b8; font-size: 12px; }

        .footer {
            margin-top: 40px;
            padding: 15px 30px;
            border-top: 1px solid #e2e8f0;
            display: table;
            width: 100%;
        }
        .footer-left  { display: table-cell; font-size: 9px; color: #94a3b8; }
        .footer-right { display: table-cell; text-align: right; font-size: 9px; color: #94a3b8; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-top">
            <div class="header-left">
                <div class="company-name">Andrufar S.R.L.</div>
                <div class="company-sub">Lista de productos de empresa</div>
            </div>
            <div class="header-right">
                <div class="report-title">Stock de Medicamentos</div>
                <div class="report-date">{{ now()->format('d/m/Y') }} · {{ now()->format('H:i') }}</div>
            </div>
        </div>
    </div>

    <div class="summary-card">
        <div class="summary-item" style="width: 50%;">
            <div class="summary-label">Medicamentos en Lista</div>
            <div class="summary-value">{{ $productos->count() }} ítems</div>
        </div>
        <div class="summary-item" style="text-align: right;">
            <div class="summary-label">Total Unidades en Stock</div>
            <div class="summary-value" style="color: #16a34a;">{{ number_format($productos->sum('stock_total'), 0) }}</div>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Código</th>
                    <th style="width: 60%;">Descripción del Medicamento</th>
                    <th style="width: 25%; text-align: right;">Unidades en Stock</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $prod)
                <tr>
                    <td><span class="product-code">{{ $prod->codigo }}</span></td>
                    <td>
                        <div class="product-name">{{ strtoupper($prod->nombre) }}</div>
                        <div class="product-sub">
                            {{ $prod->forma_farmaceutica }} · {{ $prod->concentracion }}
                            @if($prod->marca) · {{ $prod->marca }} @endif
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <span class="stock-badge">{{ number_format($prod->stock_total, 0) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="empty">No hay productos con stock registrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <div class="footer-left">Reporte de auditoría generado por el sistema de gestión Andrufar.</div>
        <div class="footer-right">Página 1 de 1</div>
    </div>

</body>
</html>
