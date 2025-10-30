@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/Users/index.css') }}">
@endsection

@section('content')
<div class="users-container">
    <div class="users-header">
        <h2>Gestión de Usuarios</h2>

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
                        <button onclick="window.location.href='{{ route('users.create') }}'" class="btn btn-primary">
                            <i class='bx bx-user-plus'></i>
                        </button>
                    </div>
                </th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->rol }}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-tertiary" style="width: unset; " onclick="window.location.href='{{ route('users.edit', $user->id) }}'">
                                <i class='bx bx-edit-alt'></i>
                            </button>

                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-primary" onclick="return confirm('¿Está seguro de eliminar este usuario?')">
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
