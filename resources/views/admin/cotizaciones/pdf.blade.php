<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #030303;
        }

        .membretado {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .membretado img {
            width: 100%;
            height: 100%;
        }

        .contenido {
            margin-top: 130px;
            margin-left: 40px;
            margin-right: 40px;
            margin-bottom: 80px;
        }

        /* Número de cotización y fecha - esquina derecha */
        .cabecera-cot {
            text-align: right;
            margin-bottom: 15px;
        }

        .cabecera-cot .numero {
            font-size: 13px;
            font-weight: bold;
            color: #050505;
        }

        .cabecera-cot .fecha {
            font-size: 10px;
            color: #020202;
        }

        /* Datos del cliente */
        .cliente-box {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .cliente-box .label {
            font-weight: bold;
            color: #030303;
        }

        /* Título central */
        .titulo {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            color: #080808;
            text-transform: uppercase;
            margin-bottom: 5px;
            /* border-top: 2px solid #0066cc; */
            /* border-bottom: 2px solid #676b70; */
            padding: 5px 0;
        }

        .subtitulo {
            text-align: center;
            font-size: 9px;
            color: #070606;
            margin-bottom: 12px;
        }

        /* Tabla principal */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        thead tr {
            background-color: #8fc4f8;
            color: rgb(0, 0, 0);
        }

        thead th {
            padding: 5px 4px;
            text-align: center;
            font-size: 9px;
            border: 1px solid #0b0e11;
        }

        tbody tr:nth-child(even) {
            background-color: #000000;
        }

        tbody td {
            padding: 4px;
            border: 1px solid #000000;
            font-size: 9px;
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .font-bold {
            font-weight: bold;
        }

        /* Fila total */
        .fila-total td {
            background-color: #92c7fc;
            color: rgb(10, 10, 10);
            font-weight: bold;
            font-size: 10px;
            padding: 5px 4px;
            border: 1px solid #080808;
        }

        /* Pie de cotización */
        .pie {
            margin-top: 15px;
            display: table;
            width: 100%;
        }

        .pie-izq {
            display: table-cell;
            width: 60%;
            vertical-align: top;
            font-size: 9px;
            line-height: 1.8;
        }

        .pie-der {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: right;
            font-size: 9px;
            line-height: 1.8;
        }

        .pie {
            margin-top: 15px;
            display: table;
            width: 100%;
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <div class="membretado">
        <img src="{{ public_path('images/membretado.png') }}">
    </div>
    <div class="contenido">

        {{-- Número y fecha --}}
        <div class="cabecera-cot">
            <div class="numero">Cotización No: &nbsp;&nbsp; {{ $cotizacion->numero }}</div>
            <div class="fecha">El Alto,
                {{ \Carbon\Carbon::parse($cotizacion->created_at)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
            </div>
            <div class="fecha">Página: 1 / 1</div>
        </div>

        {{-- Cliente --}}
        <div class="cliente-box">
            <div><span class="label">Señores:</span></div>
            <div><strong>{{ strtoupper($cotizacion->cliente->nombre ?? '—') }}</strong></div>
            <div>Atención: A Quien corresponda</div>
            <div>Presente:</div>
        </div>

        {{-- Título --}}
        <div class="titulo">COTIZACIÓN DE MEDICAMENTOS y/o INSUMOS MÉDICOS</div>
        <div class="subtitulo">
            Mediante la presente tengo a bien presentarles la cotización para:
            {{ strtoupper($cotizacion->cliente->nombre ?? '—') }}
        </div>

        {{-- Tabla --}}
        <table>
            <thead>
                <tr>
                    <th style="width:4%">No</th>
                    <th style="width:9%">CÓDIGO</th>
                    <th style="width:7%">CANTIDAD</th>
                    <th style="width:22%">DESCRIPCIÓN DEL PRODUCTO O INSUMO HOSPITALARIO</th>
                    <th style="width:12%">CONCENTRACIÓN</th>
                    <th style="width:10%">FORMA FARMACÉUTICA</th>
                    <th style="width:7%">ORIGEN</th>
                    <th style="width:9%">MARCA</th>
                    <th style="width:10%">PRECIO UNITARIO</th>
                    <th style="width:10%">PRECIO TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cotizacion->detalles as $det)
                    <tr>
                        <td class="text-center">{{ $det->nro_item }}</td>
                        <td class="text-center">{{ $det->producto->codigo ?? '—' }}</td>
                        <td class="text-center font-bold">{{ $det->cantidad }}</td>
                        <td class="text-left">{{ strtoupper($det->producto->nombre ?? '—') }}</td>
                        <td class="text-center">{{ $det->producto->concentracion ?? '—' }}</td>
                        <td class="text-center">{{ $det->producto->forma_farmaceutica ?? '—' }}</td>
                        <td class="text-center">{{ $det->producto->origen ?? '—' }}</td>
                        <td class="text-center">{{ $det->producto->marca ?? '—' }}</td>
                        <td class="text-right">{{ number_format($det->precio_unitario, 2) }}</td>
                        <td class="text-right font-bold">{{ number_format($det->precio_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fila-total">
                    <td colspan="9" class="text-right">TOTAL Bs.:</td>
                    <td class="text-right">{{ number_format($cotizacion->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- Pie --}}
        <div class="pie">
            <div class="pie-izq">
                <strong>Forma de pago:</strong> DEPÓSITO ELECTRÓNICO<br>
                <strong>Validez de la cotización:</strong> 30 DÍAS<br>
                <strong>Observaciones:</strong> {{ $cotizacion->observacion ?? 'Ninguna' }}<br><br>
                Sin otro particular, es todo cuanto tenemos a bien ofrecerle(s) para los fines consiguientes.
            </div>
            <div class="pie-der">
                <strong>Tiempo de entrega:</strong> 3 DÍAS CALENDARIO<br>
                <strong>Punto de entrega:</strong> {{ strtoupper($cotizacion->cliente->nombre ?? '—') }}
            </div>
        </div>

        {{-- Firma --}}
        <div style="text-align: center; margin-top: 30px;">
            <img src="{{ public_path('images/firma.png') }}"
                style="width: 150px; height: auto; display: block; margin: 0 auto;">
            <div style="border-top: 1px solid #333; width: 200px; margin: 5px auto;"></div>
            <div><strong>ANDRUFAR S.R.L.</strong></div>
        </div>

    </div>
</body>

</html>
