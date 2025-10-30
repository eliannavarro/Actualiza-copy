<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de Revisión Extraordinaria</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<body>
    <style>
        @font-face {
            font-family: 'CenturyGothic';
            src: url('{{ storage_path('fonts/CenturyGothic.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'CenturyGothic';
            src: url('{{ storage_path('fonts/GOTHICB.TTF') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        @font-face {
            font-family: 'CenturyGothic';
            src: url('{{ storage_path('fonts/GOTHICI.TTF') }}') format('truetype');
            font-weight: normal;
            font-style: italic;
        }

        @font-face {
            font-family: 'CenturyGothic';
            src: url('{{ storage_path('fonts/GOTHICBI.TTF') }}') format('truetype');
            font-weight: bold;
            font-style: italic;
        }

        /* Aplicar la fuente a toda la página */
        @page {
            font-family: 'CenturyGothic', sans-serif;
        }

        body {
            font-family: 'CenturyGothic', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        p {
            margin: 0;
            padding: 0;
            font-size: 13px;
            line-height: 1.5;
            text-align: justify;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .logo-cell {
            display: table-cell;
            width: 120px;
            vertical-align: middle;
        }

        .title-cell {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
            font-size: 20px;
            font-weight: bold;
        }

        .barcode-cell {
            display: table-cell;
            width: 100px;
            text-align: right;
            vertical-align: middle;
        }

        .logo-container {
            width: 110px;
            height: 60px;
            /* border: 1px solid #ccc; */
            text-align: center;
        }

        .logo {
            width: 115%;
            height: 60px;
        }

        .section-title {
            background-color: #c92020;
            color: white;
            padding: 5px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table td {
            padding: 3px;
            border: 1px solid #ddd;
        }

        .label {
            font-weight: bold;
        }

        .history-table th {
            background-color: #c92020;
            color: white;
            padding: 5px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .history-table td {
            text-align: center;
        }

        .barcode {
            font-family: "Courier New", monospace;
            font-size: 14px;
            letter-spacing: -1px;
        }
    </style>

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

    <img src="{{ $marcaAgua }}"
        style="width: auto; height: auto; opacity: 0.2; position: absolute; top: 18%; left: 50%; transform: translate(-50%, -50%);">

    <div class="header">
        <div class="logo-cell">
            <div class="logo-container">
                <img class="logo" src="{{ $logoRib }}" alt="Logo RIB">
            </div>
        </div>
        <div class="title-cell">CERTIFICADO DE REVISIÓN TECNICA</div>
        <div class="logo-cell">
            <div class="logo-container">
                <img class="logo" src="{{ $logoAqualert }}" alt="Logo Aqualert">
            </div>
        </div>
    </div>

    <div class="section-title">DATOS DE LA ORDEN</div>
    <table>
        <tr>
            <td width="15%" class="label">Orden:</td>
            <td width="35%">{{ $data->orden }}</td>
            <td width="15%" class="label">Cliente:</td>
            <td width="35%">{{ ucwords(strtolower($data->nombres)) }}</td>
        </tr>
        <tr>
            <td class="label">Dirección:</td>
            <td>{{ $data->direccion }}</td>
            <td class="label">Barrio:</td>
            <td>{{ $data->barrio }}</td>
        </tr>
        <tr>
            <td class="label">Municipio:</td>
            <td>{{ ucwords(strtolower($data->municipio)) ?? 'Desconocido' }}</td>
            <td class="label">Técnico:</td>
            <td>{{ ucfirst($data->user->name) }}</td>
        </tr>
        <tr>
            <td class="label">Cédula/NIT:</td>
            <td>{{ $data->cedula }}</td>
            <td class="label">Teléfono:</td>
            <td>{{ $data->telefono }}</td>
        </tr>
        <tr>
            <td class="label">Correo:</td>
            <td>{{ $data->correo }}</td>
            <td class="label">Categoría:</td>
            <td>{{ ucfirst($data->categoria) }}</td>
        </tr>

        <tr>
            <td class="label">Fecha Visita:</td>
            <td>{{ $data->updated_at->locale('es')->translatedFormat('d \d\e F \d\e Y') }}</td>
            <td class="label">Hora:</td>
            <td>{{ $data->updated_at->format('H:i') }}</td>
        </tr>
    </table>

    <div class="section-title">DATOS DE MEDICIÓN</div>
    <table>
        <tr>
            <td width="15%" class="label">No. Medidor:</td>
            <td width="35%">{{ $data->medidor }}</td>
            <td width="15%" class="label">Lectura:</td>
            <td width="35%">{{ $data->lectura }}</td>
        </tr>
        <tr>
            <td class="label">Aforo:</td>
            <td>{{ $data->aforo }}</td>
            <td width="40%" class="label">Puntos Hidráulicos:</td>
            <td>{{ $data->puntoHidraulico }}</td>
        </tr>
        <tr>
            <td width="38%" class="label">Personas en predio:</td>
            <td>{{ $data->numeroPersonas }}</td>
            <td class="label">Resultado:</td>
            <td>{{ $data->resultado }}</td>
        </tr>
        <tr>
            <td class="label">Ciclo:</td>
            <td>{{ $data->ciclo }}</td>
            <td class="label">Uso:</td>
            <td>{{ ucfirst($data->categoria) }}</td>
        </tr>
    </table>

    <div class="section-title">OBSERVACIONES</div>
    <p>{{ $data->observacion_inspeccion }}</p>

    <div class="section-title">FIRMAS</div>
    <table style="text-align:center;">
        <tr>
            <td>
                 <strong>Firma del Cliente</strong><br>
                    <img src="{{ $data->firmaUsuario }}" alt="Firma del Cliente" width="150"><br><br><br><br>
                    <strong>{{$data->nombres}}</strong>
            </td>
            <td>
                <strong>Firma del Tecnico</strong><br>
                
                <br>
                    <img src="{{ $data->firmaTecnico }}" alt="Firma del Técnico" width="150"><br>
                <strong>{{$data->user->name}}</strong>
            </td>
        </tr>
    </table>

    <div class="section-title">AVISOS DE PRIVACIDAD</div>
    <p><strong>Aviso de Privacidad:</strong> RIB LOGÍSTICAS S.A.S. informa que con ocasión de las inspecciones y/o
        visitas relacionadas con el cumplimiento de las obligaciones vinculadas a la prestación de servicios se podrán
        recolectar datos personales, sin perjuicio de otras finalidades legítimas. Cualquier duda sobre el tratamiento
        de datos personales puede comunicarse a habeasdata@rib.com.co</p>
</body>

</html>
