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
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombres" name="nombres"
                        value="{{ $data->nombres ?? old('nombres') }}" placeholder="">
                    @error('nombres')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Cédula --}}
                <div class="mb-3">
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="text" class="form-control" id="cedula" name="cedula"
                        value="{{ $data->cedula ?? old('cedula') }}" placeholder="">
                    @error('cedula')
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

                {{-- Campo Barrio --}}
                <div class="mb-3">
                    <label for="barrio" class="form-label">Barrio</label>
                    <input type="text" class="form-control" id="barrio" name="barrio"
                        value="{{ $data->barrio ?? old('barrio') }}" placeholder="">
                    @error('barrio')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Teléfono --}}
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" id="telefono" name="telefono"
                        value="{{ $data->telefono ?? old('telefono') }}" placeholder="">
                    @error('telefono')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Correo --}}
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="correo" name="correo"
                        value="{{ $data->correo ?? old('correo') }}" placeholder="">
                    @error('correo')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Ciclo --}}
                <div class="mb-3">
                    <label for="ciclo" class="form-label">Ciclo</label>
                    <select name="ciclo" id="ciclo" class="form-control">
                        @foreach ($ciclos as $item)
                            <option value="" selected>Seleccione un ciclo</option>
                            <option value="{{ $item }}" {{ old('ciclo') }} placeholder="Ciclo">
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                    @error('ciclo')
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

        {{-- Modal de cotización --}}
        <div id="cotizacionModal" class="modal">
            <div class="modal-container">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Formulario de Cotización</h5>
                        <span class="close-modal">&times;</span>
                    </div>
                    <div class="modal-body">
                        <div class="items-container">
                            {{-- Fila oculta de template --}}
                            <div class="item-row d-none" id="item-template">
                                <div class="service-card item-line">
                                    <button type="button" class="remove-btn">&times;</button>

                                    <div class="field-row">
                                        <label>Servicio</label>
                                        <select class="servicio-select" data-placeholder="Seleccione servicio">
                                            <option value="" disabled selected>Seleccione un servicio</option>
                                            @foreach ($servicios as $s)
                                                <option value="{{ $s->id }}" data-precio="{{ $s->precio }}">
                                                    {{ $s->nombre }} – ${{ number_format($s->precio, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="field-row">
                                        <label>Descuento %</label>
                                        <input type="number" class="descuento-input" min="0" max="100"
                                            value="0">
                                    </div>

                                    <div class="field-row">
                                        <label>Subtotal</label>
                                        <div class="input-group">
                                            <span class="input-prefix">$</span>
                                            <input type="text" class="subtotal-input" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="btnAddItem">+ Agregar servicio</button>
                        <hr>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:1rem;">
                            <strong>Total general (IVA incluido (19%)):</strong>
                            <strong><span id="totalGeneral">$0,00</span></strong>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" id="btnCancelarCotizacion">Cancelar</button>
                        <button type="button" class="btn-primary" id="btnGuardarCotizacion">Guardar Cotización</button>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script>
        $(function() {
            const IVA_RATE = 0.19;
            const $template = $('#item-template').removeClass('d-none').clone();
            $('#item-template').remove();

            function recalcRow($row) {
                // Descuento entre 0 y 100
                let desc = parseFloat($row.find('.descuento-input').val()) || 0;
                desc = Math.min(100, Math.max(0, desc));
                $row.find('.descuento-input').val(desc);

                // Precio con descuento (sin IVA)
                const precio = parseFloat($row.find('.servicio-select option:selected').data('precio') || 0);
                const precioDesc = precio * (1 - desc / 100);

                // Mostramos el subtotal sin IVA
                $row.find('.subtotal-input').val(
                    new Intl.NumberFormat('es-CO', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(precioDesc)
                );

                // Devolvemos el subtotal neto para el cálculo global
                return precioDesc;
            }

            function updateAll() {
                let totalNeto = 0;
                $('.item-line').each(function() {
                    totalNeto += recalcRow($(this));
                });

                // Calculamos IVA sobre el total neto
                const ivaTotal = totalNeto * IVA_RATE;
                const totalConIva = totalNeto + ivaTotal;

                // Mostramos el total general incluyendo IVA
                $('#totalGeneral').text(
                    new Intl.NumberFormat('es-CO', {
                        minimumFractionDigits: 2
                    }).format(totalConIva)
                );
                $('#total_con_iva_input').val(totalConIva.toFixed(2));
            }

            function addItem() {
                const $row = $template.clone();
                $('.items-container').append($row);

                const $sel = $row.find('.servicio-select');
                $sel.select2({
                    dropdownParent: $row,
                    width: '100%'
                }).on('change', updateAll);

                $row.find('.descuento-input').on('input change', updateAll);
                $row.find('.remove-btn').click(() => {
                    $row.remove();
                    updateAll();
                });

                updateAll();
            }

            // Añade primera línea
            $('#btnAddItem').click(addItem).trigger('click');

            const $modal = $('#cotizacionModal');

            // Validación form principal + abrir modal
            $('#Agendar_inputs').on('submit', function(e) {
                let ok = true;
                ['nombres', 'cedula', 'direccion', 'barrio', 'telefono', 'correo', 'ciclo'].forEach(id => {
                    const el = document.getElementById(id);
                    if (!el || el.value.trim() === '') {
                        if (el) el.classList.add('is-invalid');
                        ok = false;
                    } else el.classList.remove('is-invalid');
                });
                if (!ok) return true;
                if ($('.item-line').length === 0) {
                    alert('Agrega al menos un servicio antes de cotizar.');
                    return false;
                }
                e.preventDefault();
                $modal.show();
                $('body').css('overflow', 'hidden');
            });

            // Cerrar modal
            $('#btnCancelarCotizacion, .close-modal').click(() => {
                $modal.hide();
                $('body').css('overflow', 'auto');
            });

            // Guardar cotización
            $('#btnGuardarCotizacion').click(function() {
                const items = [];
                let valid = true;
                $('.item-line').each(function() {
                    const $r = $(this);
                    const sid = $r.find('.servicio-select').val();
                    const desc = parseFloat($r.find('.descuento-input').val()) || 0;
                    const sub = parseFloat(
                        $r.find('.subtotal-input').val().replace(/\./g, '').replace(',', '.')
                    ) || 0;
                    if (!sid) valid = false;
                    items.push({
                        servicio_id: sid,
                        descuento: desc,
                        subtotal: sub
                    });
                });
                if (!valid) {
                    alert('Revisa que cada línea tenga un servicio seleccionado.');
                    return;
                }
                $('<input>').attr({
                    type: 'hidden',
                    name: 'cotizacion_items',
                    value: JSON.stringify(items)
                }).appendTo('#Agendar_inputs');
                $modal.hide();
                $('body').css('overflow', 'auto');
                $('#Agendar_inputs')[0].submit();
            });
        });
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
@endsection
