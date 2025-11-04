@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/Users/create_edit.css') }}">
@endsection

@section('content')
<div class="user-form-container">
    <div class="form-header">
        <h2>Crear Nuevo Usuario</h2>
    </div>

    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
    
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="alert alert-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
            @error('email')
                <div class="alert alert-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password">
            @error('password')
                <div class="alert alert-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="form-group">
            <label for="password_confirmation">Confirmar Contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>
        
        <div class="form-group">
            <label for="rol">Rol</label>
            <select class="form-control form-select" id="rol" name="rol">
                <option value="">Seleccionar rol</option>
                <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Administrador</option>
                <option value="user" {{ old('rol') == 'user' ? 'selected' : '' }}>Usuario</option>
            </select>
            @error('rol')
                <div class="alert alert-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('users.index') }}'">
                Cancelar
            </button>
            <button type="submit" class="btn btn-primary">
                <span>Crear Usuario</span>
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rolSelect = document.getElementById('rol');
        const firmaContainer = document.getElementById('firma-container');
        
        rolSelect.addEventListener('change', function() {
            if (this.value === 'user') {
                firmaContainer.style.display = 'block';
            } else {
                firmaContainer.style.display = 'none';
            }
        });
    });
</script>
@endsection
