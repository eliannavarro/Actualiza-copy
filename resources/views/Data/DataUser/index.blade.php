{{-- <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Órdenes Asignadas</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Datas/indexDataUser.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            font-weight: 700;
        }
        .btn-group form {
            display: inline-block;
            margin-right: 5px;
        }
        .btn {
            cursor: pointer;
        }
        .btn-tertiary {
            background-color: #007bff; /* Azul para destacar */
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
        }
        .btn-tertiary:hover {
            background-color: #0056b3;
        }
        .btn-update {
            background-color: #ffffff00; /* Amarillo para actualizar */
            color: rgb(255, 255, 255);
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
        }
        .btn-update:hover {
            background-color: #d11919;
        }
    </style>
</head>
<body>
    <div class="dark-mode-switch">
        <label class="switch">
            <input type="checkbox">
            <span class="slider round"></span>
        </label>
        <span class="mode-text">Dark mode</span>
    </div>
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="header-container">
            <h2>Órdenes Asignadas</h2>
        </div>
        @php
            // Ordenar los datos: pendientes primero, el resto abajo
            $dataOrdenada = $data->sortByDesc(function ($item) {
                return $item->estado === 'Pendiente' ? 1 : 0;
            });
        @endphp
        @if ($dataOrdenada->isEmpty())
            <p class="message">No hay órdenes asignadas para mostrar.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Fecha inicio</th>
                        <th>Operario</th>
                        <th>Cliente</th>
                        <th>Ciclo</th>
                        <th>Nombre auditor</th>
                        <th>Fecha entrega</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataOrdenada as $item)
                        <tr>
                            <td>{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') : '---' }}</td>
                            <td>{{ $item->nombres ?? '---' }}</td>
                            <td>{{ $item->cuentaContrato ?? '---' }}</td>
                            <td>{{ $item->ciclo ?? '---' }}</td>
                            <td>{{ $item->nombre_auditor ?? '---' }}</td>
                            <td>{{ $item->fecha_entrega ? \Carbon\Carbon::parse($item->fecha_entrega)->format('Y-m-d') : '---' }}</td>
                            <td>{{ $item->estado ?? 'Pendiente' }}</td>
                            <td>
                                @if ($item->estado === 'Pendiente')
                                    <form action="{{ route('asignados.edit', $item->id) }}" method="GET" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-tertiary">
                                            <i class="bx bx-edit"></i> Realizar
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('asignados.edit', $item->id) }}" method="GET" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-update">
                                        <i class="bx bx-refresh"></i> Actualizar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <div class="btn-group" style="margin-top: 1rem;">
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-log-out"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
    <script>
        const checkbox = document.querySelector('.dark-mode-switch input[type="checkbox"]');
        const modeText = document.querySelector('.dark-mode-switch .mode-text');
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark');
            checkbox.checked = true;
            modeText.textContent = 'Light mode';
        }
        checkbox.addEventListener('change', () => {
            document.body.classList.toggle('dark');
            localStorage.setItem('darkMode', document.body.classList.contains('dark'));
            modeText.textContent = document.body.classList.contains('dark') ? 'Light mode' : 'Dark mode';
        });
    </script>
</body>
</html> --}}


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Órdenes Asignadas</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Datas/indexDataUser.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            font-weight: 700;
        }
        .btn-group form {
            display: inline-block;
            margin-right: 5px;
        }
        .btn {
            cursor: pointer;
        }
        .btn-tertiary {
            background-color: #007bff; /* Azul para destacar */
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
        }
        .btn-tertiary:hover {
            background-color: #0056b3;
        }
        .btn-update {
            background-color: #ffffff00; /* Amarillo para actualizar */
            color: rgb(255, 255, 255);
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
        }
        .btn-update:hover {
            background-color: #d11919;
        }
    </style>
</head>
<body>
    <div class="dark-mode-switch">
        <label class="switch">
            <input type="checkbox">
            <span class="slider round"></span>
        </label>
        <span class="mode-text">Dark mode</span>
    </div>
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="header-container">
            <h2>Órdenes Asignadas</h2>
        </div>
        @php
            // Ordenar los datos: pendientes primero, el resto abajo
            $dataOrdenada = $data->sortByDesc(function ($item) {
                return $item->estado === 'Pendiente' ? 1 : 0;
            });
        @endphp
        @if ($dataOrdenada->isEmpty())
            <p class="message">No hay órdenes asignadas para mostrar.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Fecha inicio</th>
                        <th>Operario</th>
                        <th>Cliente</th>
                        <th>Ciclo</th>
                        <th>Nombre auditor</th>
                        <th>Fecha entrega</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataOrdenada as $item)
                        <tr>
                            <td>{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') : '---' }}</td>
                            <td>{{ $item->nombres ?? '---' }}</td>
                            <td>{{ $item->cuentaContrato ?? '---' }}</td>
                            <td>{{ $item->ciclo ?? '---' }}</td>
                            <td>{{ $item->nombre_auditor ?? '---' }}</td>
                            <td>{{ $item->fecha_entrega ? \Carbon\Carbon::parse($item->fecha_entrega)->format('Y-m-d') : '---' }}</td>
                            <td>{{ $item->estado ?? 'Pendiente' }}</td>
                            <td>
                                @if ($item->estado === 'Pendiente')
                                    <form action="{{ route('asignados.edit', $item->id) }}" method="GET" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-tertiary">
                                            <i class="bx bx-edit"></i> Realizar
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('asignados.edit', $item->id) }}" method="GET" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-update">
                                        <i class="bx bx-refresh"></i> Actualizar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <div class="btn-group" style="margin-top: 1rem;">
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-log-out"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
    <script>
        const checkbox = document.querySelector('.dark-mode-switch input[type="checkbox"]');
        const modeText = document.querySelector('.dark-mode-switch .mode-text');
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark');
            checkbox.checked = true;
            modeText.textContent = 'Light mode';
        }
        checkbox.addEventListener('change', () => {
            document.body.classList.toggle('dark');
            localStorage.setItem('darkMode', document.body.classList.contains('dark'));
            modeText.textContent = document.body.classList.contains('dark') ? 'Light mode' : 'Dark mode';
        });
    </script>
</body>
</html>