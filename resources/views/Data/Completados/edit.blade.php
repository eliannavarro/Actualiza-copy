<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Actualizar</title>

    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Datas/editDataUser.css') }}" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
</head>

<body>
    <div class="container">
        <h2> Actualizar datos</h2>

        <form id="signature-form" action="{{ route('completados.update', $data->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            {{-- CAMPOS DE LA AGENDA --}}
            <div class="mb-3">
                <label for="orden" class="form-label">Orden</label>
                <input type="text" class="form-control" id="orden" name="orden"
                    value="{{ old('orden') ?? $data->orden }}" disabled>
            </div>

            <div class="mb-3">
                <label for="nombres" class="form-label">Nombres</label>
                <input type="text" class="form-control" id="nombres" name="nombres"
                    value="{{ old('nombres') ?? $data->nombres }}" placeholder="nombres">
                @error('nombres')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Campo Cuenta contrato --}}
            <div class="mb-3">
                <label for="cuentaContrato" class="form-label">Cuenta contrato</label>
                <input type="text" class="form-control" id="cuentaContrato" name="cuentaContrato"
                    value="{{ old('cuentaContrato') ?? $data->cuentaContrato }}" placeholder="cuentaContrato">
                @error('cuentaContrato')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Campo Dirección --}}
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion"
                    value="{{ old('direccion') ?? $data->direccion }}" placeholder="dirección">
                @error('direccion')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Campo Ciclo --}}
            <div class="mb-3">
                <label for="ciclo" class="form-label">Ciclo</label>
                <select name="ciclo" id="ciclo" class="form-control">
                   @foreach ($ciclo as $item)
                        <option value="{{ $item }}" {{ old('ciclo', $data->ciclo) == $item ? 'selected' : '' }} placeholder="ciclo">
                            {{ $item }}
                        </option>
                    @endforeach
                </select>
                @error('ciclo')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Campo Nombre auditor --}}
            <div class="mb-3">
                <label for="nombre_auditor" class="form-label">Nombre auditor</label>
                <input type="tel" class="form-control" id="nombre_auditor" name="nombre_auditor"
                    value="{{ old('nombre_auditor') ?? $data->nombre_auditor }}" placeholder="nombre_auditor">
                @error('nombre_auditor')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Campo Causanl_obs --}}
            <div class="mb-3">
                <label for="causanl_obs" class="form-label">Causanl_obs</label>
                <input type="text" class="form-control" id="causanl_obs" name="causanl_obs"
                    value="{{ old('causanl_obs') ?? $data->causanl_obs }}" placeholder="causanl_obs">
                @error('causanl_obs')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Campo Obs_adic --}}
            <div class="mb-3">
                <label for="obs_adic" class="form-label">Obs_adic</label>
                <input type="text" class="form-control" id="obs_adic" name="obs_adic"
                    value="{{ old('obs_adic') ?? $data->obs_adic }}" placeholder="obs_adic">
                @error('Obs_adic')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- CAMPOS DE LA VISITA --}}
            <div class="form-group">
                <label for="lector">Lector:</label>
                <input type="text" name="lector" id="lector" class="form-control"
                    value="{{ old('lector', $data->lector) }}" maxlength="10" placeholder="lector">
                @error('lector')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="auditor">Auditor:</label>
                <input type="text" name="auditor" id="auditor" class="form-control"
                    value="{{ old('auditor', $data->auditor) }}" placeholder="auditor">
                @error('auditor')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="atendio_usuario">Atendio usuario:</label>
                <select name="atendio_usuario" id="atendio_usuario" class="form-control">
                    @foreach ($atendio_usuario as $item)
                        <option value="{{ $item }}" {{ old('atendio_usuario', $data->atendio_usuario) == $item ? 'selected' : '' }} placeholder="atendio_usuario">
                            {{ $item }}
                        </option>
                    @endforeach
                </select>
                @error('atendio_usuario')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="observacion_inspeccion">Observación:</label>
                <input type="text" name="observacion_inspeccion" id="observacion_inspeccion" class="form-control"
                    value="{{ old('observacion_inspeccion', $data->observacion_inspeccion) }}"
                    placeholder="Observación">
                @error('observacion_inspeccion')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Contenedor para centrar el botón de actualización -->
            <div class="btn-group">
                <button onclick="window.location.href='javascript:history.back()'" type="button"
                    class="btn btn-tertiary">
                    volver
                </button>

                <button type="submit" id="update-button" class="btn btn-primary">
                    Continuar
                </button>
            </div>
        </form>
    </div>

    <script>
        const checkbox = document.querySelector('.dark-mode-switch input[type="checkbox"]');
        const modeText = document.querySelector('.dark-mode-switch .mode-text');

        // Check for existing dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark');
        }
    </script>
</body>

</html>
