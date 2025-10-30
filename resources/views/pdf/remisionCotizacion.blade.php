<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>RIB LOGISTICAS S.A.S. - PRE-FACTURA</title>

<style>
    @font-face {
        font-family: 'CenturyGothic';
        src: url('{{ storage_path("fonts/CenturyGothic.ttf") }}') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    @font-face {
        font-family: 'CenturyGothic';
        src: url('{{ storage_path("fonts/GOTHICB.TTF") }}') format('truetype');
        font-weight: bold;
        font-style: normal;
    }

    @font-face {
        font-family: 'CenturyGothic';
        src: url('{{ storage_path("fonts/GOTHICI.TTF") }}') format('truetype');
        font-weight: normal;
        font-style: italic;
    }

    @font-face {
        font-family: 'CenturyGothic';
        src: url('{{ storage_path("fonts/GOTHICBI.TTF") }}') format('truetype');
        font-weight: bold;
        font-style: italic;
    }

    /* Aplicar la fuente a toda la página */
    @page {
        font-family: 'CenturyGothic', sans-serif;
    }

    body {
        font-family: 'CenturyGothic', sans-serif;
        margin: 0;
        padding: 10px;
        font-size: 12px;
    }
    .header {
        width: 100%;
        border-top: 7px solid #ad0000;
        background-color: #E0E0E0;
        padding: 10px;
    }
    .logo-left {
        text-align: left;
        vertical-align: top;
        width: 27%;
    }
    .logo-center {
        text-align: center;
        vertical-align: middle;
        font-size: 24px;
        font-weight: bold;
    }
    .pre-factura {
        text-align: right;
        vertical-align: top;
        /* width: 30%; */
        color: #888;
    }
    .info-table {
        width: 100%;
        margin-top: 10px;
        border-collapse: collapse;
    }
    .datos-cliente {
        width: 98.5%;
        background-color: #E0E0E0;
        padding: 5px;
        margin-top: 15px;
        text-align: center;
        font-weight: bold;
    }
    .cliente-info {
        font-size: 11px;
        width: 100%;
        border-collapse: collapse;
        margin-top: 5px;
    }
    .cliente-info td {
        padding: 5px;
        border: 1px solid #ddd;
    }
    .label {
        font-weight: bold;
    }
    .factura-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    .factura-table th {
        background-color: #ad0000;
        color: white;
        /* padding: 5px; */
        text-align: center;
        border: 1px solid #ad0000;
        font-size: 11px;
    }
    .factura-table td {
        padding: 5px;
        border: 1px solid #ccc;
        height: 20px;
    }
    .total-section {
        width: 100%;
        margin-top: 15px;
    }
    .totales {
        /* float: right; */
        width: 50%;
        float: left;
        box-sizing: border-box;
    }
    .totales-table {
        width: 100%;
        border-collapse: collapse;
    }
    .totales-table td {
        padding: 3px;
        text-align: right;
        border: 1px solid #ccc;
    }
    .totales-table .neto {
        font-weight: bold;
        background-color: #ad0000;
        color: white;
    }
    .watermark {
        position: absolute;
        top: 50%;
        left: 25%;
        opacity: 0.2;
        z-index: -1;
    }

    .factura-table tbody tr:nth-child(odd) {
        background-color: #ffffff; /* Blanco */
    }

    .factura-table tbody tr:nth-child(even) {
        background-color: #E0E0E0; /* Gris claro */
    }
</style>

</head>

<body style="border-bottom: 7px solid #ad0000;">
    @php
        $path = storage_path('app/public/img/LogoRib.png');
        $logoRib = file_get_contents($path);

        $LogoRibBase64 = base64_encode($logoRib); 

        $logoRib = 'data:image/png;base64,' . $LogoRibBase64;


        $path = storage_path('app/public/img/LogoAqualert.png');
        $logoAqualert = file_get_contents($path);

        $LogoAqualertBase64 = base64_encode($logoAqualert); 

        $logoAqualert = 'data:image/png;base64,' . $LogoAqualertBase64;

        $marcaAgua = storage_path('app/public/img/LogoAqualert.png');

        $marcaAgua = file_get_contents($marcaAgua);

        $marca = base64_encode($marcaAgua); 

        $marcaAgua = 'data:image/png;base64,' . $marca;
    @endphp

    <img src="{{ $marcaAgua }}" style="width: auto; height: auto; opacity: 0.2; position: absolute; top: 40%; left: 50%; transform: translate(-50%, -50%);">

    <table class="header">
        <tr>
            <td class="logo-left">
                <strong>RIB LOGISTICAS S.A.S.</strong>
                
                <br>
                Carrera 60 #58 - 84<br>
                (+57) (605) 343 0002 - Ext. 119<br>
                <a href="mailto:aqualert@rib.com.co">aqualert@rib.com.co</a><br>
                <a href="http://rib.com.co">http://rib.com.co</a>
            </td>
            <td class="logo-center">
                <img class="logo" src="{{ $logoRib }}" alt="Logo RIB" width="200px" height="100px">
            </td>
            <td class="pre-factura" style="text-align: center;">
                <strong style="font-size: large;">REMISIÓN</strong><br>

                <div>
                    <div style="border-bottom: 1px solid #596167; width: 100%; color: #4f687d;">Fecha</div>
                    {{ $data->created_at->translatedFormat('d \d\e F \d\e Y') }}
                    <div style="border-bottom: 1px solid #7d7d7d; width: 100%; color: #4f687d;">N° de Cotización</div>
                    {{ \Illuminate\Support\Str::after($data->orden, 'OR-') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="datos-cliente">
        Datos Cliente
    </div>

    <table class="cliente-info">
        <tr>
            <td width="33.3%"><span class="label">NOMBRE: </span>{{$data->nombres}}</td>
            <td width="33.3%"><span class="label">C.C./NIT: </span>{{$data->cedula}}</td>
            <td width="33.3%"><span class="label">TELÉFONO: </span>{{$data->telefono}}</td>
        </tr>
        <tr>
            <td><span class="label">DIRECCIÓN: </span>{{$data->direccion}}</td>
            <td><span class="label">MUNICIPIO Y BARRIO: </span>{{$data->municipio}} / {{$data->barrio}}</td>
            <td><span class="label">CORREO: </span> {{$data->correo}}</td>
        </tr>
    </table>

    <table class="factura-table">
        <thead>
            <tr>
                <th width="10%">C.SERVICIO</th>
                <th width="45%">DESCRIPCIÓN</th>
                <th width="10%">CANT.</th>
                <th width="15%">P. UNITARIO</th>
                <th width="12%">% Descuento</th>
                <th width="20%">SUBTOTALES</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sinIva = 0;
                $sumaUnitarios = 0;
                $descuento = 0;
            @endphp


            @foreach($data->detalleVisita as $detalle)
                <tr>
                    <td align="center">{{ $detalle->id ?? '-' }}</td>
                    
                    <td>{{ $detalle->servicio->descripcion ?? 'Sin descripción' }}</td>
                    
                    <td align="center">{{ $detalle->cantidad ?? 1 }}</td>
                    
                    <td align="right">${{ number_format($detalle->servicio->precio ?? 0, 2, ',', '.') }}</td>
                    
                    <td align="right">{{$detalle->descuento ?? 0 }} %</td>

                    <td align="right">
                        ${{ number_format($detalle->subtotal , 2, ',', '.') }}
                    </td>
                </tr>

                {{$sinIva = $sinIva + $detalle->subtotal;}} 


                {{$sumaUnitarios = $sumaUnitarios + $detalle->servicio->precio}} 
            @endforeach
                
            @php
                $descuento =  $sumaUnitarios - $sinIva;
            @endphp
        </tbody>
    </table>

    {{-- MARCA DE AGUA --}}

    <table style="margin: -3; width: 101%;">
        {{-- <img src="/api/placehold/150/50" alt="Líder de Proyecto e innovación" /> --}}
        <td style=" height: 100px; text-align: center; vertical-align: middle;">
            {{-- <img class="logo" src="{{ $data->firma }}" alt="Líder de Proyecto e innovación" style="width: 200px;"> --}}
            {{-- <div style="margin-top: -10px; border-bottom: 1px solid black; width: 80%; margin-left: auto; margin-right: auto;"></div>
            <p style="text-align: center; font-size: 12px; font-weight: bold;">Líder de Proyecto e innovación</p> --}}
        </td>


        <td style="width: 283.8px;">
            <table class="totales-table">
                <tr>
                    <td width="157.5px">Total antes de IVA</td>
                    <td>${{ number_format($sinIva , 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total descontado</td>
                    <td>${{ number_format($descuento , 2, ',', '.') }}</td>
                </tr>
                <tr>
                    @php
                        $valorBase = $data->total / 1.19;
                        $iva = $data->total - $valorBase;
                    @endphp
                    <td>Impuestos (IVA)</td>
                    <td>${{ number_format($iva, 2, ',', '.') }}</td>
                </tr>
                <tr class="neto">
                    <td>Total (IVA incluido)</td>
                    <td>${{ number_format($data->total , 2, ',', '.') }}</td>
                </tr>
            </table>
        </td>
    </table>
    
    <div>
        <p><strong>Nota:</strong> Esta remisión/cotización para el servicio de Detección de Fugas con Equipos de Geófono tiene una validez de 30 días calendario a partir de la fecha de emisión. Vencido este plazo, se requerirá una nueva remisión o cotización debido a posibles variaciones en condiciones y precios.</p>
    </div>

    <div class="firma" style="width: 350px; text-align: center; vertical-align: middle; margin-top: 2rem; position: absolute; top: 90%; left: 50%; transform: translate(-50%, -50%);  ">
        <img class="logo" src="{{ $data->firma }}" alt="Líder de Proyecto e innovación" style="width: 200px;">
        <div style="margin-top: -10px; border-bottom: 1px solid black; width: 80%; margin-left: auto; margin-right: auto;"></div>
        <p style="text-align: center; font-size: 12px; font-weight: bold;">Líder de Proyecto e innovación</p>
    </div>
</body>
</html>
