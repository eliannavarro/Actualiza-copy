
@extends('layouts.user')

@section('title', 'Órdenes Completadas')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/Datas/indexDataUser.css') }}">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            font-weight: 700;
        }
        .btn-group form {
            display: inline-block;
            margin-right: 5px;
        }
        .btn {
            cursor: pointer;
        }
        .btn-tertiary {
            background-color: #ffffff00;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
        }
        .btn-tertiary:hover {
            background-color: #ff0000;
        }
        
        .btn-update {
            background-color: #ffffff00;
            color: rgb(255, 255, 255);
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
        }
        .btn-update:hover {
            background-color: #d11919;
        }
    </style>
    <style>
/* Desplazamiento horizontal */
@media (max-width: 900px) {
    .container {
        overflow-x: auto; /* Permite deslizar horizontalmente */
    }

    table {
        min-width: 700px; /* Evita que se comprima demasiado */
        display: block;
        overflow-x: auto;
        white-space: nowrap;
        border: none !important; /* Quita bordes externos */
    }

    th, td {
        border: none !important; /* Quita bordes internos */
    }
}
    </style>

@endsection

@section('content')
<div class="container">
    <div class="header-container">
        <h2>Completados</h2>
    </div>

    @if ($datas->isEmpty())
        <p class="message">No hay órdenes completadas para mostrar.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Ciclo</th>
                    <th>Cliente</th>
                    <th>Cuenta contrato</th>
                    <th>Dirección</th>
                    <th>Nombre auditor</th>
                    <th>Fecha entrega</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datas as $item)
                    <tr>
                        <td>{{ $item->orden ?? '---' }}</td>
                        <td>{{ $item->ciclo ?? '---' }}</td>
                        <td>{{ $item->nombres ?? '---' }}</td>
                        <td>{{ $item->cuentaContrato ?? '---' }}</td>
                        <td>{{ $item->direccion ?? '---' }}</td>
                        <td>{{ $item->nombre_auditor ?? '---' }}</td>

                        {{-- Mostrar la fecha entrega formateada --}}
                        <td>
                            @if(!empty($item->fecha_entrega))
                                {{ \Carbon\Carbon::parse($item->fecha_entrega)->format('Y-m-d') }}
                            @elseif(!empty($item->updated_at))
                                {{ \Carbon\Carbon::parse($item->updated_at)->format('Y-m-d') }}
                            @else
                                ---
                            @endif
                        </td>

                        {{-- Mostrar estado como texto --}}
                        <td>
                            @if($item->estado == 1)
                                Completado
                            @else
                                ⚙️ {{ $item->estado }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
