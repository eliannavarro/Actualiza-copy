@extends('layouts.app')

@section('title', 'Completados')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/Datas/asignar.css') }}">
@endsection

@section('content')
    <div class="assignment-container">

        <form action="{{ route('completados.filtrar') }}" method="GET" class="mb-4">
            @csrf
            <div class="filters-section">
                <input type="text" name="buscador-orden" class="filter-input" placeholder=" orden..."
                    value="{{ request('buscador-orden') }}">
                <input type="text" name="buscador-operario" class="filter-input" placeholder=" operario..."
                    value="{{ request('buscador-operario') }}">
                <input type="text" name="buscador-nombre" class="filter-input" placeholder=" nombres..."
                    value="{{ request('buscador-nombre') }}">
                <input type="text" name="buscador-direccion" class="filter-input" placeholder=" direccion..."
                    value="{{ request('buscador-direccion') }}">

                <!-- Campos ocultos para conservar los parámetros de orden -->
                <input type="hidden" name="sortBy" value="{{ $sortBy }}">
                <input type="hidden" name="direction" value="{{ $direction }}">

                <button type="submit" class="btn btn-tertiary">
                    <i class='bx bx-filter-alt' style="margin-right: 0.2rem;"></i>
                    <span>Filtrar</span>
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

        <div class="table-wrapper">
            <table class="assignment-table">
                <thead>
                    <tr>
                        <th>
                            @php
                                $queryParams = request()->query();
                                $queryParams['sortBy'] = 'orden';
                                $queryParams['direction'] =
                                    request('sortBy') == 'orden' && request('direction') == 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route(Route::currentRouteName(), $queryParams) }}">
                                Orden
                                @if (request('sortBy') == 'orden')
                                    <i
                                        class="bx {{ request('direction') == 'asc' ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                                @endif
                            </a>
                        </th>

                        <th>
                            @php
                                $queryParams = request()->query();
                                $queryParam['sortBy'] = 'operario';
                                $queryParams['direction'] =
                                    request('sortBy') == 'operario' && request('direction') == 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route(Route::currentRouteName(), $queryParams) }}">
                                Operario
                                @if (request('sortBy') == 'operario')
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
                                $queryParams['sortBy'] = 'nombres';
                                $queryParams['direction'] =
                                    request('sortBy') == 'nombres' && request('direction') == 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route(Route::currentRouteName(), $queryParams) }}">
                                Cliente
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
                                    request('sortBy') == 'cuentaContrato' && request('direction') == 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route(Route::currentRouteName(), $queryParams) }}">
                                Cuenta contrato
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
                                    request('sortBy') == 'direccion' && request('direction') == 'asc' ? 'desc' : 'asc';
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
                            @php
                                $queryParams = request()->query();
                                $queryParams['sortBy'] = 'medidor';
                                $queryParams['direction'] =
                                    request('sortBy') == 'medidor' && request('direction') == 'asc'
                                        ? 'desc'
                                        : 'asc';
                            @endphp
                            <a href="{{ route(Route::currentRouteName(), $queryParams) }}">
                                Numero del medidor
                                @if (request('sortBy') == 'medidor')
                                    <i
                                        class="bx {{ request('direction') == 'asc' ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                                @endif
                            </a>
                        </th>  

                        <th>
                            Excel
                        </th>

                        <th>
                            Editar
                        </th>

                        <th>
                            Eliminar registro
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                        <tr>
                            <td>{{ $data->orden }}</td>
                            <td>{{ $data->user->name }}</td>
                            <td>{{ $data->ciclo }}</td>
                            <td>{{ $data->nombres }}</td>
                            <td>{{ $data->cuentaContrato }}</td>
                            <td class="table-cell-truncate">{{ $data->direccion }}</td>
                            <td>{{ $data->nombre_auditor }}</td>
                            <td>{{ $data->medidor }}</td>
                            

                            <td><a href="{{ route('ticket.options', $data->id) }}"> <span
                                        style=" font-size: 30px; color: #ad0000; cursor: pointer;"
                                        class="material-symbols-outlined"> star_rate </span> </a></td>
                            <td><a href="{{ route('completados.edit', $data->id) }}"> <i class='bx bx-edit-alt'
                                        style="font-size:26px;"></i> </a></td>
                            <td>
                                <form action="{{ route('completados.destroy', $data->id) }}" method="POST"
                                    onsubmit="return confirm('¿Seguro que deseas eliminar este registro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none; border:none; cursor:pointer;">
                                        <i class='bx bx-trash' style="font-size:23px; color:red;"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{-- @if ($data->hasPages()) --}}
            <div class="pagination-info">
                Mostrando {{ $datas->firstItem() }} a {{ $datas->lastItem() }} de {{ $datas->total() }} registros
            </div>
            <ul class="pagination">
                {{-- Botón Previous --}}
                @if ($datas->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class='bx bx-chevron-left'></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $datas->appends(request()->except('page'))->previousPageUrl() }}"
                            rel="prev">
                            <i class='bx bx-chevron-left'></i>
                        </a>
                    </li>
                @endif

                @php
                    $start = $datas->currentPage() - 2;
                    $end = $datas->currentPage() + 2;
                    if ($start < 1) {
                        $start = 1;
                        $end = min(5, $datas->lastPage());
                    }
                    if ($end > $datas->lastPage()) {
                        $end = $datas->lastPage();
                        $start = max(1, $end - 4);
                    }
                @endphp

                @if ($start > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ $datas->appends(request()->except('page'))->url(1) }}">1</a>
                    </li>
                    @if ($start > 2)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                @endif

                @for ($i = $start; $i <= $end; $i++)
                    <li class="page-item {{ $datas->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link"
                            href="{{ $datas->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                @if ($end < $datas->lastPage())
                    @if ($end < $datas->lastPage() - 1)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                    <li class="page-item">
                        <a class="page-link"
                            href="{{ $datas->appends(request()->except('page'))->url($datas->lastPage()) }}">
                            {{ $datas->lastPage() }}
                        </a>
                    </li>
                @endif

                {{-- Botón Next --}}
                @if ($datas->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $datas->appends(request()->except('page'))->nextPageUrl() }}"
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
