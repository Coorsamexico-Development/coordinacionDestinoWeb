<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        .header {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #337ab7;
            color: white;
            padding: 8px;
            border: 1px solid #ddd;
            text-transform: uppercase;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
            text-transform: uppercase;
        }

        .attachment-list {
            margin-top: 5px;
            padding-left: 20px;
        }

        .attachment-item {
            margin-bottom: 5px;
        }

        a {
            color: #337ab7;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="header">
        CORREO DE INCIDENCIAS<br>
        FORMATO UTILIZADO
    </div>

    <table>
        <thead>
            <tr>
                <th>TIPO DE RECHAZO</th>
                <th>CONFIRMACION</th>
                <th>O.C</th>
                <th>FACTURA</th>
                <th>FOLIO INTERNO</th>
                <th>FECHA DE REPORTE</th>
                <th>CLAVE DE PRODUCTO</th>
                <th>MATERIAL</th>
                <th>CANTIDAD</th>
                <th>UM</th>
                <th>UPC/SKU</th>
                <th>INCIDENCIA</th>
                <th>CARGA</th>
                <th>LINEA TRANSPORTISTA</th>
                <th>CLIENTE</th>
                <th>LOCALIDAD</th>
                <th>ORIGEN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incidenciasData as $item)
            <tr>
                <td>{{ $item['tipo_rechazo'] }}</td>
                <td>{{ $item['confirmacion'] }}</td>
                <td>{{ $item['oc'] }}</td>
                <td>{{ $item['factura'] }}</td>
                <td>{{ $item['folio_interno'] }}</td>
                <td>{{ $item['fecha_reporte'] }}</td>
                <td>{{ $item['clave_producto'] }}</td>
                <td>{{ $item['material'] }}</td>
                <td>{{ $item['cantidad'] }}</td>
                <td>{{ $item['um'] }}</td>
                <td>{{ $item['upc_sku'] }}</td>
                <td>{{ $item['incidencia_desc'] }}</td>
                <td>{{ $item['carga'] }}</td>
                <td>{{ $item['linea'] }}</td>
                <td>{{ $item['cliente'] }}</td>
                <td>{{ $item['localidad'] }}</td>
                <td>{{ $item['origen'] }}</td>
            </tr>
            @endforeach
            @if(empty($incidenciasData))
            <tr>
                <td>-</td>
                <td>{{ $confirmacion->confirmacion }}</td>
                <td colspan="13">No se registraron incidencias detalladas.</td>
            </tr>
            @endif
        </tbody>
    </table>

    @if($bitacoraUrl)
    <div class="section-title">ANEXAN BITÁCORA</div>
    <div class="attachment-list">
        <div class="attachment-item">
            <a href="{{ $bitacoraUrl }}">Descargar Bitácora</a>
        </div>
    </div>
    @endif

    <div class="section-title">EVIDENCIAS EN DADO CASO DE ALGÚN RECHAZO</div>
    <div class="attachment-list">
        @php $hasEvidencias = false; @endphp
        @foreach($incidenciasData as $item)
        @foreach($item['evidencias'] as $evidencia)
        @php $hasEvidencias = true; @endphp
        <div class="attachment-item">
            <a href="{{ $evidencia->evidencia }}">Evidencia OC: {{ $item['oc'] }} (Ver imagen)</a>
        </div>
        @endforeach
        @endforeach
        @if(!$hasEvidencias)
        <div>No hay evidencias adjuntas.</div>
        @endif
    </div>
</body>

</html>