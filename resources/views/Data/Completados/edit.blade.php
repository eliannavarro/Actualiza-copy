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

        <form id="signature-form" action="{{route('completados.update', $data->id)}}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            {{-- CAMPOS DE LA AGENDA --}}
            <div class="mb-3">
                <label for="orden" class="form-label">orden</label>
                <input type="text" class="form-control" id="orden" name="orden"
                    value="{{ old('orden') ?? $data->orden}}" disabled>
            </div>

            <div class="mb-3">
                <label for="nombres" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombres" name="nombres"
                    value="{{ old('nombres') ?? $data->nombres}}" placeholder="nombre">
                @error('nombres')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Campo Cédula --}}
            <div class="mb-3">
                <label for="cedula" class="form-label">Cédula</label>
                <input type="text" class="form-control" id="cedula" name="cedula"
                    value="{{  old('cedula') ??  $data->cedula }}" placeholder="Cédula">
                @error('cedula')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Campo Dirección --}}
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion"
                    value="{{ old('direccion') ?? $data->direccion  }}" placeholder="Dirección">
                @error('direccion')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Campo Barrio --}}
            <div class="mb-3">
                <label for="barrio" class="form-label">Barrio</label>
                <input type="text" class="form-control" id="barrio" name="barrio"
                    value="{{ old('barrio') ?? $data->barrio }}" placeholder="Barrio">
                @error('barrio')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Campo Teléfono --}}
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="tel" class="form-control" id="telefono" name="telefono"
                    value="{{ old('telefono') ?? $data->telefono  }}" placeholder="Teléfono">
                @error('telefono')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Campo Correo --}}
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="text" class="form-control" id="correo" name="correo"
                    value="{{ old('correo') ?? $data->correo  }}" placeholder="Correo">
                @error('correo')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Campo Ciclo --}}
            <div class="mb-3">
                <label for="ciclo" class="form-label">Ciclo</label>
                <select name="ciclo" id="ciclo" class="form-control">
                    @foreach ($ciclos as $item)
                        <option value="{{ $item }}" {{ old('ciclo', $data->ciclo) == $item ? 'selected' : '' }} placeholder="Ciclo">
                            {{ $item }}
                        </option>
                    @endforeach
                </select>
                @error('ciclo')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            {{-- CAMPOS DE LA VISITA --}}
            <div class="form-group">
                <label for="numeroPersonas">Número de personas:</label>
                <input type="text" name="numeroPersonas" id="numeroPersonas" class="form-control"
                    value="{{ old('numeroPersonas', $data->numeroPersonas) }}" maxlength="10" placeholder="Número de personas">
                @error('numeroPersonas')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <select name="categoria" id="categoria" class="form-control">
                    <option value="">Seleccione la categoría de la inspección</option>
                    <option value="residencial" {{ old('categoria', $data->categoria) == 'residencial' ? 'selected' : '' }}>Residencial</option>
                    <option value="comercial" {{ old('categoria', $data->categoria) == 'comercial' ? 'selected' : '' }}>Comercial</option>
                    <option value="industrial" {{ old('categoria', $data->categoria) == 'industrial' ? 'selected' : '' }}>Industrial</option>
                </select>
                @error('categoria')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="puntoHidraulico">Punto hidráulico:</label>
                <input type="text" name="puntoHidraulico" id="puntoHidraulico" class="form-control"
                    value="{{ old('puntoHidraulico', $data->puntoHidraulico) }}" placeholder="Puntos hihdráulicos">
                @error('puntoHidraulico')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="medidor">Medidor:</label>
                <input type="text" name="medidor" id="medidor" class="form-control"
                    value="{{ old('medidor', $data->medidor) }}" placeholder="Medidor">
                @error('medidor')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="lectura">Lectura:</label>
                <input type="text" name="lectura" id="lectura" class="form-control"
                    value="{{ old('lectura', $data->lectura) }}" maxlength="10" placeholder="Lectura">
                @error('lectura')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="aforo">Aforo:</label>
                <input type="text" name="aforo" id="aforo" class="form-control"
                    value="{{ old('aforo', $data->aforo) }}" placeholder="Aforo">
                @error('aforo')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="observacion_inspeccion">Observación:</label>
                <input type="text" name="observacion_inspeccion" id="observacion_inspeccion" class="form-control"
                    value="{{ old('observacion_inspeccion', $data->observacion_inspeccion) }}" placeholder="Observación">
                @error('observacion_inspeccion')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="resultado">Resultado:</label>
                <select name="resultado" id="resultado" class="form-control">
                    @foreach ($resultados as $item)
                        <option value="{{ $item }}" {{ old('resultado', $data->resultado) == $item ? 'selected' : '' }} placeholder="Resultado">
                            {{ $item }}
                        </option>
                    @endforeach
                </select>
                @error('resultado')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Contenedor para centrar el botón de actualización -->
            <div class="btn-group">
                <button onclick="window.location.href='javascript:history.back()'"
                    type="button" class="btn btn-tertiary">
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

    <script>
        $(document).ready(function() {
            $('#ciclo').select2({
                placeholder: "Selecciona o escribe un ciclo",
                allowClear: true,
                width: '100%',
                // Matcher personalizado para búsqueda sin distinguir mayúsculas
                matcher: function(params, data) {
                    // Si no hay término de búsqueda, devolver todos los elementos
                    if ($.trim(params.term) === '') {
                        return data;
                    }
                    
                    // Si no hay texto, no mostrar
                    if (typeof data.text === 'undefined') {
                        return null;
                    }
                    
                    // Convertir a minúsculas para comparación
                    var termLower = params.term.toLowerCase();
                    var textLower = data.text.toLowerCase();
                    
                    // Verificar si contiene el término
                    if (textLower.indexOf(termLower) > -1) {
                        return data;
                    }
                    
                    return null;
                }
            });
        });
    </script>
</body>

</html>
