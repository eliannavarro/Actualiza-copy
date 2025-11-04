<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Actualizar</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Datas/editDataUser.css') }}" rel="stylesheet">


    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="container">
        <h2> Actualizar datos</h2>

        <form id="signature-form" action="{{ route('asignados.update', $data->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- CAMPOS DE LA AGENDA --}}

            <div class="form-group">
                <label for="orden">Orden:</label>
                <input type="text" name="orden" id="orden" class="form-control"
                    value="{{ old('nombre_cliente', $data->orden) }}" disabled>
            </div>
            <div class="form-group">
                <label for="correo">Nombre auditor:</label>
                <input type="text" id="nombre_auditor" name="nombre_auditor" class="form-control"
                    value="{{ $data->nombre_auditor }}" disabled>
            </div>

            <div class="form-group">
                <label for="ciclo">Ciclo:</label>
                <input type="text" name="ciclo" class="form-control" value="{{ $data->ciclo }}" disabled>
            </div>


            <div class="form-group">
                <label for="nombre_cliente">Nombre Cliente:</label>
                <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control"
                    value="{{ old('nombre_cliente', $data->nombres) }}" disabled>
            </div>


            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" class="form-control"
                    value="{{ $data->direccion }}" disabled>
            </div>

            <div class="form-group">
                <label for="barrio">Cuenta contrato:</label>
                <input type="text" id="cuentaContrato" name="cuentaContrato" class="form-control"
                    value="{{ $data->cuentaContrato }}" disabled>
            </div>

            <div class="form-group">
                <label for="telefono">Causanl_obs:</label>
                <input type="text" id="causanl_obs" name="causanl_obs" class="form-control"
                    value="{{ $data->causanl_obs }}" disabled>
            </div>

            <div class="form-group">
                <label for="correo">Obs_adic:</label>
                <input type="text" id="correo" name="obs_adic" class="form-control" value="{{ $data->obs_adic }}"
                    disabled>
            </div>


            {{-- CAMPOS DE LA VISITA --}}

            <div class="form-group">
                <label for="lectura">Lectura</label>
                <label for="lectura">Lector:</label>
                <input type="text" name="lector" id="lector" class="form-control" value="{{ old('lector') }}"
                    maxlength="10">
                @error('lector')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="aforo">Auditor:</label>
                <input type="text" name="auditor" id="auditor" class="form-control" value="{{ old('auditor') }}">
                @error('auditor')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="categoria">Atendio usuario:</label>
                <select name="atendio_usuario" id="atendio_usuario" class="form-control">
                    <option value="">Seleccione</option>
                    <option value="si" {{ old('si') == 'si' ? 'selected' : '' }}>si</option>
                    <option value="no" {{ old('no') == 'no' ? 'selected' : '' }}>no</option>

                </select>
                @error('atendio_usuario')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="observacion_inspeccion">Observación:</label>
                <input type="text" name="observacion_inspeccion" id="observacion_inspeccion" class="form-control"
                    value="{{ old('observacion_inspeccion') }}">

                @error('observacion_inspeccion')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="foto">Alfanumerica:</label>
                <input type="file" name="foto" id="foto" class="form-control">
                @error('foto')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Reemplaza el botón con un checkbox y un texto -->
            <div class="form-group checkbox-label">
                <input type="checkbox" name="yes" required {{ old('yes') ? 'checked' : '' }}>
                <div class="terminos">
                    <label class="terminos">Autoriza a RIB Logísticas SAS a utilizar y
                        almacenar
                        sus datos personales, incluyendo su número de teléfono y correo electrónico, conforme a la Ley
                        1581
                        de
                        2012. Esta información será utilizada únicamente para fines relacionados con la prestación de
                        nuestros
                        servicios.</label>
                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
</body>

</html>
