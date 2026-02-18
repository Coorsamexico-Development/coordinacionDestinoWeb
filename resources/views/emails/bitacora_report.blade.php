<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { margin-bottom: 20px; font-weight: bold; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #1a4d8c; color: white; padding: 8px; border: 1px solid #000; text-align: left; }
        td { padding: 8px; border: 1px solid #000; }
        .row-bg { background-color: #dce6f1; }
        .section-title { font-weight: bold; margin-top: 20px; }
        .attachment-list { margin-top: 5px; }
        .attachment-item { margin-bottom: 5px; }
        a { color: #1a4d8c; text-decoration: none; }
    </style>
</head>
<body>
    <div class="header">MANIOBRA FORMATO QUE SE UTILIZA</div>

    <table>
        <thead>
            <tr>
                <th>CONCEPTO</th>
                <th>DETALLE</th>
            </tr>
        </thead>
        <tbody>
            <!-- Static Fields -->
            <tr class="row-bg">
                <td>Carga</td>
                <td>General</td> <!-- Placeholder/Static per request context? Or verify where 'Carga' comes from. Assuming General for now or empty -->
            </tr>
            <tr>
                <td>Cedis</td>
                <td>{{ $confirmacion->ubicacion->nombre_ubicacion ?? '' }}</td>
            </tr>
            <tr class="row-bg">
                <td>Confirmación</td>
                <td>{{ $confirmacion->confirmacion }}</td>
            </tr>
            <tr>
                <td>Origen</td>
                <td>{{ $confirmacion->cliente->nombre ?? '' }}</td>
            </tr>
            <tr class="row-bg">
                <td>Plataforma</td>
                <td>{{ $confirmacion->plataforma_id }}</td> 
            </tr>
            <tr>
                <td>Línea de transporte</td>
                <td>{{ $confirmacion->lineaTransporte->nombre ?? '' }}</td>
            </tr>
            
            <!-- Dynamic Fields from Bitacora -->
            <tr class="row-bg">
                <td>Motivo de maniobra</td>
                <td>{{ $bitacoraData['Motivo de maniobra']['valor'] ?? '' }}</td>
            </tr>
            <tr>
                <td>Nombre del personal de Walmart que solicitó la maniobra</td>
                <td>{{ $bitacoraData['Nombre del personal de Walmart que solicitó la maniobra']['valor'] ?? '' }}</td>
            </tr>
            <tr class="row-bg">
                <td>Coordinador COORSA que solicita maniobra</td>
                <td>{{ $bitacoraData['Coordinador COORSA que solicita maniobra']['valor'] ?? '' }}</td>
            </tr>
            <tr>
                <td>NÚMERO DE TARIMAS/CAJAS</td>
                <td>{{ $bitacoraData['NÚMERO DE TARIMAS/CAJAS']['valor'] ?? '' }}</td>
            </tr>
            <tr class="row-bg">
                <td>Costo por tarima/Costo por caja</td>
                <td>{{ $bitacoraData['Costo por tarima/Costo por caja']['valor'] ?? '' }}</td>
            </tr>
            <tr>
                <td>Costo total</td>
                <td>{{ $bitacoraData['Costo total']['valor'] ?? '' }}</td>
            </tr>
            <tr class="row-bg">
                <td>Maniobra autorizada por</td>
                <td>{{ $bitacoraData['Maniobra autorizada por']['valor'] ?? '' }}</td>
            </tr>
            <tr>
                <td>Autorización Transporte</td>
                <td>{{ $bitacoraData['Autorización Transporte']['valor'] ?? '' }}</td>
            </tr>
            <tr class="row-bg">
                <td>INFORMACIÓN DEL PAGO (PISO/ CUENTA BANCARIA)</td>
                <td>{{ $bitacoraData['INFORMACIÓN DEL PAGO (PISO/ CUENTA BANCARIA)']['valor'] ?? '' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">ANEXAN EVIDENCIAS</div>
    <div class="attachment-list">
        @if(isset($bitacoraData['EVIDENCIAS']) && !empty($bitacoraData['EVIDENCIAS']['valores']))
            @foreach($bitacoraData['EVIDENCIAS']['valores'] as $index => $url)
            <div class="attachment-item">
                <a href="{{ $url }}">Evidencia {{ $index + 1 }} (Ver imagen)</a>
            </div>
            @endforeach
        @else
            <div>No hay evidencias adjuntas.</div>
        @endif
    </div>
</body>
</html>
