
@extends('layouts.user')

@section('title', 'Órdenes Asignadas')

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
            background-color: #ffffff00; /* Transparente */
            color: rgb(255, 255, 255);
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
        }
        .btn-update:hover {
            background-color: #d11919;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="header-container">
            <h2>Pendientes</h2>
        </div>
        @php
            $dataOrdenada = $data->sortByDesc(function ($item) {
                return $item->estado === 'Pendiente' ? 1 : 0;
            });
        @endphp
        @if ($dataOrdenada->isEmpty())
            <p class="message">No hay órdenes asignadas para mostrar.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Fecha inicio</th>
                        <th>Ciclo</th>
                        <th>Cliente</th>
                        <th>Nombre auditor</th>
                        <th>Direccion</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataOrdenada as $item)
                        <tr>
                            <td>{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') : '---' }}</td>
                            <td>{{ $item->ciclo ?? '---' }}</td>
                            <td>{{ $item->nombres ?? '---' }}</td>
                            <td>{{ $item->nombre_auditor ?? '---' }}</td>
                            <td>{{ $item->direccion ?? '---' }}</td>
                            <td>{{ $item->estado ?? 'Pendiente' }}</td>
                            <td>
                                @if ($item->estado === 'Pendiente')
                                    <form action="{{ route('asignados.edit', $item->id) }}" method="GET" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-tertiary">
                                            <i class="bx bx-edit"></i> pendiente
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('asignados.edit', $item->id) }}" method="GET" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-update">
                                        <i class="bx bx-refresh"></i> Realizar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        
    </div>
@endsection