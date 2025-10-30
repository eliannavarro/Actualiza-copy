@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/Users/create_edit.css') }}">
@endsection

@section('content')
<div class="user-form-container">
    <div class="form-header">
        <h2>Editar servicio</h2>
    </div>

    <form action="{{ route('servicio.update', $servicio->id) }}" method="POST">
        @csrf
        @method('PUT')
    
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                id="nombre" name="nombre" value="{{ old('nombre', $servicio->nombre) }}" required>
            @error('nombre')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="descripcion">Descripcion</label>
            <input type="text" class="form-control @error('descripcion') is-invalid @enderror"
                id="descripcion" name="descripcion" value="{{ old('descripcion', $servicio->descripcion) }}" required>
            @error('descripcion')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="precio">Precio</label>
            <input type="text" class="form-control @error('precio') is-invalid @enderror"
                id="precio" name="precio" value="{{ old('precio', $servicio->precio) }}" required>
            @error('precio')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
    
    
        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('servicio.index') }}'">
                Cancelar
            </button>
            <button type="submit" class="btn btn-primary">
                <span>Guardar Cambios</span>
            </button>
        </div>
    </form>
</div>
@endsection
