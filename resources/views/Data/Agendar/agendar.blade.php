@extends('layouts.app') {{-- Usa la plantilla base si la tienes --}}

{{-- Uso de css --}}
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/Agendar/nuevo_registro.css') }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
@endsection

@section('content')
    <div class="container">
        <h2 class="my-4">Agendar Nuevo Registro</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Formulario --}}
        <div class="section">
            <form action="{{ isset($data) ? route('schedule.update', $data->id) : route('schedule.store') }}" method="POST"
                id="Agendar_inputs">
                @csrf
                @if (isset($data))
                    @method('PUT') {{-- Para actualizar si existe un ID --}}
                @endif

                {{-- Campo Nombre --}}
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del cliente</label>
                    <input type="text" class="form-control" id="nombres" name="nombres"
                        value="{{ $data->nombres ?? old('nombres') }}" placeholder="">
                    @error('nombres')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Cuenta Contrato --}}
                <div class="mb-3">
                    <label for="cuentaContrato" class="form-label">Cuenta contrato</label>
                    <input type="text" class="form-control" id="cuentaContrato" name="cuentaContrato"
                        value="{{ $data->cuentaContrato ?? old('cuentaContrato') }}" placeholder="">
                    @error('cuentaContrato')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Dirección --}}
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion"
                        value="{{ $data->direccion ?? old('direccion') }}" placeholder="">
                    @error('direccion')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Causanl_obs --}}
                <div class="mb-3">
                    <label for="causanl_obs" class="form-label">Causanl_obs</label>
                    <input type="text" class="form-control" id="causanl_obs" name="causanl_obs"
                        value="{{ $data->causanl_obs ?? old('causanl_obs') }}" placeholder="">
                    @error('causanl_obs')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Obs_adic --}}
                <div class="mb-3">
                    <label for="obs_adic" class="form-label">Obs_adic</label>
                    <input type="text" class="form-control" id="obs_adic" name="obs_adic"
                        value="{{ $data->obs_adic ?? old('obs_adic') }}" placeholder="">
                    @error('obs_adic')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ajustar el botón de registrar para que no se vea pegado --}}
                <br>
                <input type="hidden" name="total" id="total_con_iva_input" value="0">
                {{-- Botón de envío --}}
                <button type="submit" class="btn btn-primary">
                    {{ isset($data) ? 'Actualizar' : 'Registrar' }}
                </button>
            </form>
        </div>

@endsection
