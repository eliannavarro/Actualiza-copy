@extends('layouts.app')

@section('title', 'Asignación de Operarios')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/Datas/asignar.css') }}">
@endsection

@section('content')
    <div class="assignment-container">
        <form action="{{ route('asignar.filtrar') }}" method="GET" class="mb-4">
            @csrf
            <div class="filters-section">
                <input type="text" name="buscador-nombre" class="filter-input" placeholder=" nombre..."
                    value="{{ request('buscador-nombre') }}">
                <input type="text" name="buscador-direccion" class="filter-input" placeholder=" direccion..."
                    value="{{ request('buscador-direccion') }}">
                <input type="text" name="buscador-cuentaContrato" class="filter-input" placeholder=" cuentaContrato..."
                    value="{{ request('buscador-cuentaContrato') }}">
                <input type="text" name="buscador-causanl_obs" class="filter-input" placeholder=" causanl_obs..."
                    value="{{ request('buscador-causanl_obs') }}">
                <input type="text" name="buscador-obs_adic" class="filter-input" placeholder=" obs_adic.."
                    value="{{ request('buscador-obs_adic') }}">
                <input type="text" name="buscador-ciclo" class="filter-input" placeholder=" ciclo..."
                    value="{{ request('buscador-ciclo') }}">
                <input type="text" name="buscador-nombre_auditor" class="filter-input" placeholder=" nombre_auditor.."
                    value="{{ request('buscador-nombre_auditor') }}">

                <!-- Campos ocultos para conservar los parámetros de orden -->
                <input type="hidden" name="sortBy" value="{{ $sortBy }}">
                <input type="hidden" name="direction" value="{{ $direction }}">

                <button type="submit" class="btn btn-tertiary">
                    <i class='bx bx-filter-alt' style="margin-right: 0.2rem;"></i>
                    <span>Filtrar</span>
                </button>

                <button class="btn btn-primary" id="abrirModal" type="button">
                    {{-- <i class='bx bx-user-plus'></i> --}}
                    Asignar
                </button>
            </div>
        </form>

        <!-- Mensajes de éxito y error -->
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                <i class="bx bx-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                <i class="bx bx-error"></i> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <i class="bx bx-error"></i> <strong>Se encontraron los siguientes errores:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('asignar.operario') }}" method="post">
            @csrf
            <div class="table-wrapper">
                <table class="assignment-table">
                    <thead>
                        <tr>
                            <th>
                                <label class="checkbox-wrapper">
                                    <input type="checkbox" id="seleccionarTodo">
                                    <span class="checkmark"></span>
                                </label>
                            </th>

                            <th>
                                @php
                                    $queryParams = request()->query();
                                    $queryParams['sortBy'] = 'nombres';
                                    $queryParams['direction'] =
                                        request('sortBy') == 'nombres' && request('direction') == 'asc'
                                            ? 'desc'
                                            : 'asc';
                                @endphp
                                <a href="{{ route(Route::currentRouteName(), $queryParams) }}">
                                    Nombres
                                    @if (request('sortBy') == 'nombres')
                                        <i
                                            class="bx {{ request('direction') == 'asc' ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                                    @endif
                                </a>
                            </th>

                            <th>
                                @php
                                    $queryParams = request()->query();
                                    $queryParams['sortBy'] = 'cuentaContrato';
                                    $queryParams['direction'] =
                                        request('sortBy') == 'cuentaContrato' && request('direction') == 'asc'
                                            ? 'desc'
                                            : 'asc';
                                @endphp
                                <a href="{{ route(Route::currentRouteName(), $queryParams) }}">
                                    cuentaContrato
                                    @if (request('sortBy') == 'cuentaContrato')
                                        <i
                                            class="bx {{ request('direction') == 'asc' ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                                    @endif
                                </a>
                            </th>

                            <th>
                                @php
                                    $queryParams = request()->query();
                                    $queryParams['sortBy'] = 'direccion';
                                    $queryParams['direction'] =
                                        request('sortBy') == 'direccion' && request('direction') == 'asc'
                                            ? 'desc'
                                            : 'asc';
                                @endphp
                                <a href="{{ route(Route::currentRouteName(), $queryParams) }}">
                                    Dirección
                                    @if (request('sortBy') == 'direccion')
                                        <i
                                            class="bx {{ request('direction') == 'asc' ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                                    @endif
                                </a>
                            </th>

                            <th>
                                @php
                                    $queryParams = request()->query();
                                    $queryParams['sortBy'] = 'causanl_obs';
                                    $queryParams['barrio'] =
                                        request('sortBy') == 'causanl_obs' && request('direction') == 'asc'
                                            ? 'desc'
                                            : 'asc';
                                @endphp
                                <a href="{{ route(Route::currentRouteName(), $queryParams) }}">
                                    Causanl_obs
                                    @if (request('sortBy') == 'causanl_obs')
                                        <i
                                            class="bx {{ request('direction') == 'asc' ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                                    @endif
                                </a>
                            </th>

                            <th>
                                @php
                                    $queryParams = request()->query();
                                    $queryParams['sortBy'] = 'obs_adic';
                                    $queryParams['direction'] =
                                        request('sortBy') == 'obs_adic' && request('direction') == 'asc'
                                            ? 'desc'
                                            : 'asc';
                                @endphp
                                <a href="{{ route(Route::currentRouteName(), $queryParams) }}">
                                    Teléfono
                                    @if (request('sortBy') == 'obs_adic')
                                        <i
                                            class="bx {{ request('direction') == 'asc' ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                @php
                                    $queryParams = request()->query();
                                    $queryParams['sortBy'] = 'ciclo';
                                    $queryParams['direction'] =
                                        request('sortBy') == 'ciclo' && request('direction') == 'asc' ? 'desc' : 'asc';
                                @endphp
                                <a href="{{ route(Route::currentRouteName(), $queryParams) }}">
                                    Ciclo
                                    @if (request('sortBy') == 'ciclo')
                                        <i
                                            class="bx {{ request('direction') == 'asc' ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                @php
                                    $queryParams = request()->query();
                                    $queryParams['sortBy'] = 'nombre_auditor';
                                    $queryParams['direction'] =
                                        request('sortBy') == 'nombre_auditor' && request('direction') == 'asc'
                                            ? 'desc'
                                            : 'asc';
                                @endphp
                                <a href="{{ route(Route::currentRouteName(), $queryParams) }}">
                                    Nombre auditor
                                    @if (request('sortBy') == 'nombre_auditor')
                                        <i
                                            class="bx {{ request('direction') == 'asc' ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                                    @endif
                                </a>
                            </th>

                            <th>
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $programacion)
                            <tr>
                                <td>
                                    <label class="checkbox-wrapper">
                                        <input type="checkbox" name="Programacion[]" value="{{ $programacion->id }}">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td class="table-cell-truncate">{{ $programacion->nombres }}</td>
                                <td class="table-cell-truncate">{{ $programacion->direccion }}</td>
                                <td>{{ $programacion->cuentaContrato }}</td>
                                <td>{{ $programacion->causanl_obs }}</td>
                                <td>{{ $programacion->obs_adic }}</td>
                                 <td>{{ $programacion->ciclo }}</td>
                                  <td>{{ $programacion->nombre_auditor }}</td>

                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('remision.generate', $programacion->id) }}" target="_blank"><i
                                                class='bx bxs-star' style="font-size:25px; color: #ad0000;"></i></a>

                                        <a href="#" onclick="eliminarVisita({{ $programacion->id }}); return false;"
                                            title="Eliminar visita">
                                            <i class='bx bx-trash' style="font-size:25px; color:red; cursor:pointer;"></i>
                                        </a>

                                        {{-- <form id="delete-form-{{ $programacion->id }}" action="{{ route('data.destroy', $programacion->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        
                                        <a href="#" onclick="event.preventDefault(); if(confirm('¿Está seguro de eliminar esta visita?')) { document.getElementById('delete-form-{{ $programacion->id }}').submit(); }">
                                            <i class='bx bx-trash' style="font-size:25px"></i>
                                        </a> --}}

                                        {{-- <button class="btn btn-tertiary" style="width: unset; " onclick="window.location.href=''">
                                            <i class='bx bx-edit-alt'></i>
                                        </button>
                                        --}}


                                        {{-- <form action="{{route('data.destroy',$programacion->id)}}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-primary" onclick="return confirm('¿Está seguro de eliminar esta visita?')">
                                                <i class='bx bx-trash' style="font-size:25px"></i>
                                            </button>
                                        </form>  --}}

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Modal de Asignación -->
            <div id="miModal" class="modal-assignment">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>
                            {{-- <i class='bx bx-user-plus'></i> --}}
                            Asignar Operario
                        </h2>
                        <button type="button" class="modal-close" title="Cerrar">
                            <i class='bx bx-x'></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="operario">
                                <i class='bx bx-user'></i>
                                Seleccionar operario:
                            </label>
                            <select class="form-control" name="operario" id="operario">
                                @foreach ($operarios as $operario)
                                    <option value="{{ $operario->id }}">
                                        {{ $operario->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class='bx bx-check'></i>
                            <span>Confirmar Asignación</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>





        <div class="pagination-container">
            {{-- @if ($data->hasPages()) --}}
            <div class="pagination-info">
                Mostrando {{ $data->firstItem() }} a {{ $data->lastItem() }} de {{ $data->total() }} registros
            </div>
            <ul class="pagination">
                {{-- Botón Previous --}}
                @if ($data->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class='bx bx-chevron-left'></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $data->appends(request()->except('page'))->previousPageUrl() }}"
                            rel="prev">
                            <i class='bx bx-chevron-left'></i>
                        </a>
                    </li>
                @endif

                @php
                    $start = $data->currentPage() - 2;
                    $end = $data->currentPage() + 2;
                    if ($start < 1) {
                        $start = 1;
                        $end = min(5, $data->lastPage());
                    }
                    if ($end > $data->lastPage()) {
                        $end = $data->lastPage();
                        $start = max(1, $end - 4);
                    }
                @endphp

                @if ($start > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ $data->appends(request()->except('page'))->url(1) }}">1</a>
                    </li>
                    @if ($start > 2)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                @endif

                @for ($i = $start; $i <= $end; $i++)
                    <li class="page-item {{ $data->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link"
                            href="{{ $data->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                @if ($end < $data->lastPage())
                    @if ($end < $data->lastPage() - 1)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                    <li class="page-item">
                        <a class="page-link"
                            href="{{ $data->appends(request()->except('page'))->url($data->lastPage()) }}">
                            {{ $data->lastPage() }}
                        </a>
                    </li>
                @endif

                {{-- Botón Next --}}
                @if ($data->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $data->appends(request()->except('page'))->nextPageUrl() }}"
                            rel="next">
                            <i class='bx bx-chevron-right'></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class='bx bx-chevron-right'></i>
                        </span>
                    </li>
                @endif
            </ul>
            {{-- @endif --}}
        </div>

    </div>

@endsection

@section('scripts')
    <script>
        // ------------------------ CHECKBOX ------------------------
        document.getElementById('seleccionarTodo').addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('input[name="Programacion[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });


        // Selección por zona con Shift
        document.addEventListener('DOMContentLoaded', function() {
            let lastChecked = null;
            const checkboxes = document.querySelectorAll('input[name="Programacion[]"]');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('click', function(e) {
                    if (!lastChecked) {
                        lastChecked = this;
                        return;
                    }

                    if (e.shiftKey) {
                        let inBetween = false;
                        checkboxes.forEach(currentCheckbox => {
                            if (currentCheckbox === this || currentCheckbox ===
                                lastChecked) {
                                inBetween = !inBetween;
                            }
                            if (inBetween) {
                                currentCheckbox.checked = lastChecked.checked;
                            }
                        });
                    }
                    lastChecked = this;
                });
            });
        });

        // ------------------------ MODAL ------------------------
        const modal = document.getElementById('miModal');
        const btnAbrir = document.getElementById('abrirModal');
        const btnCerrar = document.querySelector('.modal-close');

        btnAbrir.onclick = function() {
            modal.style.display = "flex";
        }

        btnCerrar.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>


    <script>
        function eliminarVisita(id) {
            if (!confirm('¿Está seguro de eliminar esta visita?')) return;

            fetch(`/visita/eliminar/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => {
                    if (response.ok) {
                        alert('Visita eliminada correctamente');
                        location.reload();
                    } else {
                        alert('Hubo un error al eliminar la visita.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al intentar eliminar.');
                });
        }
    </script>
@endsection
