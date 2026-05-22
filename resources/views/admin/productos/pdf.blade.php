<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
        }

        .header {
            background: linear-gradient(135deg, #1e3a5f, #2563eb);
            color: white;
            padding: 22px 30px;
            margin-bottom: 22px;
        }
        .header-top {
            display: table;
            width: 100%;
        }
        .header-left  { display: table-cell; vertical-align: middle; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; }
        .company-name { font-size: 17px; font-weight: bold; }
        .company-sub  { font-size: 9.5px; color: rgba(255,255,255,0.65); margin-top: 3px; }
        .report-title { font-size: 13px; font-weight: bold; }
        .report-date  { font-size: 9px; color: rgba(255,255,255,0.65); margin-top: 3px; }

        .summary {
            margin: 0 30px 20px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-left: 4px solid #2563eb;
            border-radius: 6px;
            padding: 12px 18px;
            display: table;
            width: calc(100% - 60px);
        }
        .summary-number { font-size: 30px; font-weight: bold; color: #1e3a5f; line-height: 1; display: table-cell; vertical-align: middle; width: 60px; }
        .summary-text   { display: table-cell; vertical-align: middle; padding-left: 12px; }
        .summary-label  { font-size: 12px; font-weight: bold; color: #1e3a5f; }
        .summary-desc   { font-size: 10px; color: #64748b; margin-top: 3px; }

        .table-wrap { margin: 0 30px; }

        table { width: 100%; border-collapse: collapse; }

        thead tr { background: #1e3a5f; color: white; }
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
            background: #eff6ff;
            color: #1d4ed8;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-price {
            display: inline-block;
            background: #f0fdf4;
            color: #15803d;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-origen {
            display: inline-block;
            background: #faf5ff;
            color: #7e22ce;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
        }

        .empty { text-align: center; padding: 40px; color: #94a3b8; font-size: 11px; }

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
                <div class="company-sub">Sistema de Gestión de Medicamentos e Insumos</div>
            </div>
            <div class="header-right">
                <div class="report-title">Catálogo de Productos</div>
                <div class="report-date">Generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Resumen -->
    <div class="summary">
        <div class="summary-number">{{ $productos->count() }}</div>
        <div class="summary-text">
            <div class="summary-label">Productos Registrados</div>
            <div class="summary-desc">Catálogo completo de productos con precios referenciales</div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:4%">#</th>
                    <th style="width:10%">Código</th>
                    <th style="width:28%">Nombre del Producto</th>
                    <th style="width:14%">Concentración</th>
                    <th style="width:13%">Forma Farm.</th>
                    <th style="width:10%">Origen</th>
                    <th style="width:10%">Marca</th>
                    <th style="width:11%" class="center">Precio Ref.</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $index => $producto)
                <tr>
                    <td style="color:#94a3b8; font-size:8px;">{{ $index + 1 }}</td>
                    <td><span class="badge-code">{{ $producto->codigo ?? '—' }}</span></td>
                    <td>
                        <div class="product-name">{{ strtoupper($producto->nombre) }}</div>
                        @if($producto->medicamento)
                        <div class="product-sub">LINAME: {{ $producto->medicamento->nombre }}</div>
                        @endif
                    </td>
                    <td style="font-size:8.5px;">{{ $producto->concentracion ?? '—' }}</td>
                    <td style="font-size:8.5px;">{{ $producto->forma_farmaceutica ?? '—' }}</td>
                    <td>
                        @if($producto->origen)
                        <span class="badge-origen">{{ $producto->origen }}</span>
                        @else
                        <span style="color:#d1d5db">—</span>
                        @endif
                    </td>
                    <td style="font-size:8.5px;">{{ $producto->marca ?? '—' }}</td>
                    <td class="center">
                        @if($producto->precio_referencial)
                        <span class="badge-price">Bs. {{ number_format($producto->precio_referencial, 2) }}</span>
                        @else
                        <span style="color:#d1d5db">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="empty">No se encontraron productos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-left">Documento generado automáticamente por el sistema Andrufar</div>
        <div class="footer-right">Total: {{ $productos->count() }} producto(s)</div>
    </div>

</body>
</html>
