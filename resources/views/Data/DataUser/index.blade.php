<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Asignados</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Datas/indexDataUser.css') }}">

    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

    <div class="dark-mode-switch">
        <label class="switch">
            <input type="checkbox" />
            <span class="slider round"></span>
        </label>
        <span class="mode-text">Dark mode</span>
    </div>


    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="header-container">
            <h2>Órdenes Asignadas</h2>
        </div>

        @if ($data->isEmpty())
            <p class="message">No hay órdenes asignadas para mostrar.</p>
        @else
            <table class="table-vertical">
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td class="table-cell-truncate" colspan="5"><strong>Orden:</strong>
                                {{ $item->orden }}
                            </td>
                        </tr>
                        <tr>
                            <td class="table-cell-truncate" colspan="5"><strong>Nombres:</strong>
                                {{ $item->nombres }}
                            </td>
                        </tr>
                        <tr>
                            <td class="table-cell-truncate" colspan="5"><strong>Cuenta contrato:</strong>
                                {{ $item->cuentaContrato }}
                            </td>
                        </tr>
                        <tr>
                            <td class="table-cell-truncate" colspan="5"><strong>Dirección:</strong>
                                {{ $item->direccion }}
                            </td>
                        </tr>
                        <tr>
                            <td class="table-cell-truncate" colspan="5"><strong>Causanl_obs:</strong>
                                {{ $item->causanl_obs }}
                            </td>
                        </tr>
                        <tr>
                            <td class="table-cell-truncate" colspan="5"><strong>Obs_adic:</strong>
                                {{ $item->obs_adic }}
                            </td>
                        </tr>
                        <tr>
                            <td class="table-cell-truncate" colspan="5"><strong>Ciclo</strong>
                                {{ $item->ciclo }}
                            </td>
                        </tr>
                        <tr>
                            <td class="table-cell-truncate" colspan="5"><strong>Nombre auditor</strong>
                                {{ $item->nombre_auditor }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    {{-- Botón de retroceso a la página anterior --}}
                    @if ($data->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $data->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    @endif

                    {{-- Paginación, solo mostrar 5 enlaces de página --}}
                    @php
                        $start = max(1, $data->currentPage() - 1);
                        $end = min($data->lastPage(), $data->currentPage() + 1);
                    @endphp

                    @for ($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $i == $data->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    {{-- Botón de avance a la página siguiente --}}
                    @if ($data->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $data->nextPageUrl() }}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                    @endif
                </ul>
            </nav>
        @endif

        <div class="btn-group">
            @if (!$data->isEmpty())
                <form action="{{ route('asignados.edit', $item) }}" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-tertiary">
                        <i class='bx bx-edit'></i>
                        Realizar
                    </button>
                </form>
            @endif

            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class='bx bx-log-out'></i>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>


    <script>
        const checkbox = document.querySelector('.dark-mode-switch input[type="checkbox"]');
        const modeText = document.querySelector('.dark-mode-switch .mode-text');

        // Check for existing dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark');
            checkbox.checked = true;
            modeText.textContent = 'Light mode';
        }

        checkbox.addEventListener('change', () => {
            document.body.classList.toggle('dark');

            if (document.body.classList.contains('dark')) {
                localStorage.setItem('darkMode', 'true');
                modeText.textContent = 'Light mode';
            } else {
                localStorage.setItem('darkMode', 'false');
                modeText.textContent = 'Dark mode';
            }
        });
    </script>
</body>

</html>
