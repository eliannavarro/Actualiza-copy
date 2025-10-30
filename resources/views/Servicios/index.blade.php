@extends('layouts.app')

@section('title', 'Gestión de servicios')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/Users/index.css') }}">
@endsection

@section('content')
<div class="users-container">
    <div class="users-header">
        <h2>Gestión de servicios</h2>
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

    <table class="users-table">
        <thead>
            <tr>
                <th style="justify-content: center; display: flex;">
                    <div>
                        <button onclick="window.location.href='{{ route('servicio.create') }}'" class="btn btn-primary">
                            <i class='bx bx-plus'></i>
                        </button>
                    </div>
                </th>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($servicios as $servicio)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $servicio->nombre }}</td>
                    <td>{{ $servicio->descripcion }}</td>
                    <td>{{ $servicio->precio }}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-tertiary" style="width: unset; " onclick="window.location.href='{{ route('servicio.edit', $servicio->id) }}'">
                                <i class='bx bx-edit-alt'></i>
                            </button>

                            <form action="{{ route('servicio.destroy', $servicio->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-primary" onclick="return confirm('¿Está seguro de eliminar este servicio?')">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
