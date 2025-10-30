@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/Users/create_edit.css') }}">
@endsection

@section('content')
<div class="user-form-container">
    <div class="form-header">
        <h2>Editar Usuario</h2>
    </div>

    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
    
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                id="name" name="name" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                id="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="form-group">
            <label for="password">Nueva Contraseña (dejar en blanco para mantener la actual)</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                id="password" name="password">
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="form-group">
            <label for="password_confirmation">Confirmar Nueva Contraseña</label>
            <input type="password" class="form-control"
                id="password_confirmation" name="password_confirmation">
        </div>
    
        <div class="form-group">
            <label for="rol">Rol</label>
            <select class="form-control form-select @error('rol') is-invalid @enderror"
                id="rol" name="rol" required>
                <option value="">Seleccionar rol</option>
                <option value="admin" {{ old('rol', $user->rol) == 'admin' ? 'selected' : '' }}>Administrador</option>
                <option value="user" {{ old('rol', $user->rol) == 'user' ? 'selected' : '' }}>Usuario</option>
            </select>
            @error('rol')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="form-group" id="firma-container" style="{{ old('rol', $user->rol) == 'user' ? '' : 'display: none;' }}">
            <label for="firma">Firma</label>
            
            @if ($user->firma_path)
                <div class="current-signature mb-2">
                    <p>Firma actual:</p>
                    <img src="{{ Storage::url($user->firma_path) }}" alt="Firma actual" style="max-width: 200px; max-height: 100px;">
                </div>
            @endif
            
            <input type="file" class="form-control @error('firma') is-invalid @enderror" 
                id="firma" name="firma" accept="image/*">
            <small class="form-text text-muted">Subir una nueva firma reemplazará la actual</small>
            
            @error('firma')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('users.index') }}'">
                Cancelar
            </button>
            <button type="submit" class="btn btn-primary">
                <span>Guardar Cambios</span>
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
