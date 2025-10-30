@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/Users/create_edit.css') }}">
@endsection

@section('content')
<div class="user-form-container">
    <div class="form-header">
        <h2>Crear nuevo servicio</h2>
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

    <form action="{{ route('servicio.store') }}" method="POST">
        @csrf
    
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}">
            @error('nombre')
                <div class="alert alert-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="form-group">
            <label for="descripcion">Descripcion</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" value="{{ old('descripcion') }}">
            @error('descripcion')
                <div class="alert alert-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="form-group">
            <label for="precio">Precio</label>
            <input type="precio" class="form-control" id="precio" name="precio" value="{{ old('precio') }}">
            @error('precio')
                <div class="alert alert-danger mt-1">{{ $message }}</div>
            @enderror
        </div>        
    
        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('servicio.index') }}'">
                Atrás
            </button>
            <button type="submit" class="btn btn-primary">
                <span>Guardar servicio</span>
            </button>
        </div>
    </form>
</div>
@endsection
