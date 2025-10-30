@extends('layouts.app')

@section('title', 'Subir Archivo Excel')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/Excel/import.css') }}">

@endsection

@section('content')
    <div class="card">
        <div class="card-header text-center">
            <h1>Subir Archivo Excel</h1>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form id="uploadForm" action="{{ route('import.import') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="custom-file">
                    <input type="file" id="fileInput" name="file" class="custom-file-input">
                    <label for="fileInput" id="fileLabel" class="custom-file-label">
                        <i class='bx bxs-file-import'></i>
                        Seleccionar archivo...
                    </label>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-primary"
                        onclick="confirmAction('Reemplazar', '{{ route('import.replace') }}')">
                        Reemplazar</button>
                    <button type="button" class="btn btn-secondary"
                        onclick="confirmAction('Agregar', '{{ route('import.add') }}')">
                        Agregar</button>
                    <a href="{{ route('home') }}" class="btn btn-tertiary">
                        Volver
                    </a>
                </div>
            </form>
        </div>
        <div class="info-container">
            <p class="info-primary">
                <strong>Reemplazar:</strong> Sustituye los registros existentes con los nuevos datos del archivo Excel.
            </p>
            <p class="info-success">
                <strong>Agregar:</strong> Añade nuevos registros al sistema basados en el archivo Excel sin afectar los existentes.
                Si el archivo Excel contiene un registro con un número de cuenta existente, no será tomado en cuenta.
            </p>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        function confirmAction(action, url) {
            if (confirm(`¿Estás seguro de que deseas ${action} los datos? Esta acción no se puede deshacer.`)) {
                const form = document.getElementById('uploadForm');
                form.action = url;
                form.submit();
            }
        }
    </script>
    <script>
        document.getElementById('fileInput').addEventListener('change', function(event) {
            const fileName = event.target.files[0] ? event.target.files[0].name : "Seleccionar archivo";
            document.getElementById('fileLabel').textContent = fileName;
        });
    </script>
@endsection
