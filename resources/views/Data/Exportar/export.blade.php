@extends('layouts.app')

@section('title', 'Exportar Órdenes Actualizadas')

@section('style')

    <link rel="stylesheet" href="{{ asset('css/Datas/asignar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Excel/export.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="header-container">
            <h2>Auditoria en terreno</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="content-container">
            <!-- Filtro de Ciclo -->
            <div class="form-group">
                <select name="ciclo" id="ciclo" onchange="fetchFilteredData(1); toggleTableVisibility();">
                    <option value="null" disabled selected>Selecciona un ciclo</option>
                    <option value=0> Mostrar todos los ciclos</option>
                    @foreach ($ciclos as $ciclo)
                        <option value="{{ $ciclo }}"
                            {{ request('ciclo') == $ciclo ? 'selected' : '' }}>
                            {{ $ciclo }}
                        </option>
                    @endforeach
                </select>

                <!-- Botón para Exportar a Excel -->
                <form id="exportForm" action="{{ route('export.excel') }}" method="GET">
                    <input type="hidden" name="ciclo" id="exportCiclo">
                    <button type="submit" class="btn btn-primary">Descargar Excel</button>
                </form>
            
            </div>



            <!-- Tabla de resultados -->
            <div id="table-wrapper" style="display:none;">
                <table class="assignment-table">
                    <thead>
                        <tr>
                            <th>Ciclo</th>
                            <th>Operario</th>
                            <th>Cliente</th>
                            <th>Cuenta contrato</th>
                            <th>Nombre auditor</th>
                            <th>Observación</th>
                        </tr>
                       
                    </thead>
                    <tbody id="table-body"></tbody>
                </table>
                <!-- Paginación -->
                <div class="pagination-container" id="pagination-container"></div>
            </div>
            <p id="no-results" style="display:none;">No se encontraron órdenes para el ciclo seleccionado.</p>
        </div>
    </div>

    <script>
        // Función para obtener datos filtrados y actualizar la tabla y la paginación
        function fetchFilteredData(page = 1) {
            var ciclo = document.getElementById("ciclo").value;

            // Actualizar el campo oculto para el formulario de exportación
            document.getElementById("exportCiclo").value = ciclo;

            // Realizar la solicitud AJAX al servidor
            fetch('{{ route('export.filtrar') }}?ciclo=' + ciclo + '&page=' + page, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    var tableWrapper = document.getElementById("table-wrapper");
                    var tableBody = document.getElementById("table-body");
                    var paginationContainer = document.getElementById("pagination-container");
                    var noResults = document.getElementById("no-results");

                    // Limpiar la tabla y paginación antes de agregar nuevos datos
                    tableBody.innerHTML = '';
                    paginationContainer.innerHTML = '';
                    noResults.style.display = 'none';

                    // Si no hay datos, mostrar mensaje
                    if (data.datas.data.length === 0) {
                        noResults.style.display = 'block';
                        tableWrapper.style.display = 'none';
                        return;
                    }

                    // Mostrar la tabla si hay datos
                    tableWrapper.style.display = 'block';

                    // Insertar los datos en la tabla
                    data.datas.data.forEach(item => {
                        var row = document.createElement("tr");
                        row.innerHTML = `
                        <td>${item.ciclo}</td>
                        <td>${item.user}</td>
                        <td>${item.nombres}</td>
                        <td class="table-cell-truncate">${item.cuentaContrato}</td>
                        <td>${item.nombre_auditor}</td>
                        <td>${item.observacion_inspeccion}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                    // <td class="table-cell-truncate">${item.nombre_cliente}</td>

                    // Mostrar la paginación
                    var paginationHtml = `
                    <div class="pagination-info">
                        Mostrando ${data.pagination.total > 0 ? data.datas.firstItem() : 0} a ${data.datas.lastItem()} de ${data.pagination.total} registros
                    </div>
                    <ul class="pagination">
                        ${generatePaginationLinks(data.pagination)}
                    </ul>
                `;
                    paginationContainer.innerHTML = paginationHtml;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        // Función para generar los enlaces de paginación
        function generatePaginationLinks(pagination) {
            let links = '';
            const currentPage = pagination.current_page;
            const lastPage = pagination.last_page;

            // Previous page button
            links += currentPage > 1 ?
                `<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="fetchFilteredData(${currentPage - 1})"><i class='bx bx-chevron-left'></i></a></li>` :
                `<li class="page-item disabled"><span class="page-link"><i class='bx bx-chevron-left'></i></span></li>`;

            // Page links
            for (let i = 1; i <= lastPage; i++) {
                links += i === currentPage ?
                    `<li class="page-item active"><span class="page-link">${i}</span></li>` :
                    `<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="fetchFilteredData(${i})">${i}</a></li>`;
            }

            // Next page button
            links += currentPage < lastPage ?
                `<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="fetchFilteredData(${currentPage + 1})"><i class='bx bx-chevron-right'></i></a></li>` :
                `<li class="page-item disabled"><span class="page-link"><i class='bx bx-chevron-right'></i></span></li>`;

            return links;
        }

        // Llamar a la función al cargar la página
        window.onload = function() {
            fetchFilteredData();
        }

        function toggleTableVisibility() {
            var ciclo = document.getElementById("ciclo").value;
            var tableWrapper = document.getElementById("table-wrapper");

            // Si el valor del ciclo es vacío o "Selecciona un ciclo", ocultamos la tabla
            if (!ciclo || ciclo === "0") {
                tableWrapper.style.display = "none";
            } else {
                tableWrapper.style.display = "block";
            }
        }
    </script>
@endsection
