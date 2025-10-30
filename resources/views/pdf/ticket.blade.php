<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ticket</title>
    <style>
         <?php echo file_get_contents(public_path('css/Pdf/ticket.css')); ?>
    </style>
</head>
<body>
    <p class="order-number">{{ $data->orden }}</p>
    <div class="header">
        @php
            $path = storage_path('app/public/img/LogoRib.png');
            $logoRib = file_get_contents($path);

            $LogoRibBase64 = base64_encode($logoRib); 

            $logoRib = 'data:image/png;base64,' . $LogoRibBase64;


            $path = storage_path('app/public/img/LogoAqualert.png');
            $logoAqualert = file_get_contents($path);

            $LogoAqualertBase64 = base64_encode($logoAqualert); 

            $logoAqualert = 'data:image/png;base64,' . $LogoAqualertBase64;
        @endphp


        <div class="logo-container">
            <img class="logo" src="{{ $logoRib }}" alt="Logo RIB">
            <img class="logo" src="{{ $logoAqualert }}" alt="Logo Aqualert"  >
        </div>


    </div>

    <div class="details">
        <ul>
            {{-- <li>Hora de inicio: {{ $data->updated_at ?? 'Desconocido' }}</li> --}}
            <li>Hora: {{ $data->updated_at ?? 'Desconocido' }}</li>
            <li>Cliente: {{ $data->nombres ?? 'Desconocido' }}</li>
            <li>Cédula / NIT: {{ $data->cedula ?? 'Desconocido' }}</li>
            <li>Dirección: {{ $data->direccion ?? 'Desconocido' }}</li>
            <li>Municipio: {{ ucwords(strtolower($data->municipio)) ?? 'Desconocido' }}</li>
            <li>Barrio: {{ $data->barrio ?? 'Desconocido' }}</li>
            <li>Teléfono: {{ $data->telefono ?? 'Desconocido' }}</li>
            <li>Correo: {{ $data->correo ?? 'Desconocido' }}</li>
        </ul>

        <br><br>
        <p>VISITA:</p>
        <ul>
            <li>No. Medidor:{{ $data->medidor }}</li>
            <li>Lectura: {{ $data->lectura }}</li>
            <li>Aforo: {{ $data->aforo }}</li>
            <li class="parrafo">Resultado: {{ $data->resultado }}</li>
            <li>Observación: {{ $data->observacion_inspeccion }}</li>
            <li>Ciclo: {{ $data->ciclo }}</li>
            <li>Punto Hidraulico: {{ $data->puntoHidraulico }}</li>
            <li>Número Personas: {{ $data->numeroPersonas }}</li>
            <li>Categoría: {{ $data->categoria }}</li>
        </ul>
    </div>

    <div class="signatures">
        <p>
            Firma Usuario:
            <img class="firma" src="{{ $data->firmaUsuario }}" alt="Firma Usuario" width="120" height="60">
        </p>
        <p>
            {{$data->user->name}}
            <img class="firma" src="{{ $data->firmaTecnico }}" alt="Firma Técnico" width="120" height="60">
        </p>
    </div>


    <br><br>
    
    <p class="parrafo">
        RIB Logísticas SAS utiliza y almacena sus datos personales,
        incluyendo su número de teléfono y correo electrónico, conforme 
        a la Ley 1581 de 2012. Esta información será utilizada únicamente 
        para fines relacionados con la prestación de nuestros servicios.
    </p>

    <br><br><br><br>
    
    <div class="footer">
        <p>Servicio especializado y garantizado.</p>
        <p>Gracias por su confianza</p>

    <br>
        <p>Teléfono de contácto</p>
        <p>310 2313007</p>
        <p>310 2316450</p>
        <p>www.Rib.com.co</p>
    </div>
</body>
</html>