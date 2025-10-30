@extends('layouts.app')

@section('title', 'Inicio')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endsection

@section('content')
    <div class="container">

        <div class="card" onclick="window.location.href='{{route('servicio.index')}}'">
            <div class="card-header">
                <h2>Servicios</h2>
            </div>
            <div class="card-body">
                <p>Gestiona los servicios que hay disponibles.</p>
            </div>
        </div>

        <div class="card" style="opacity: 0.3">
            <div class="card-header">
                <h2>Zonas</h2>
            </div>
            <div class="card-body">
                <p>Gestiona todas las zonas que están agregadas.</p>
            </div>
        </div>

        {{-- <div class="card">
            <div class="card-header">
                <h2>Inicio</h2>
            </div>
            <div class="card-body">
                <p>Bienvenido a la aplicación de asignación de tareas.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Inicio</h2>
            </div>
            <div class="card-body">
                <p>Bienvenido a la aplicación de asignación de tareas.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Inicio</h2>
            </div>
            <div class="card-body">
                <p>Bienvenido a la aplicación de asignación de tareas.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Inicio</h2>
            </div>
            <div class="card-body">
                <p>Bienvenido a la aplicación de asignación de tareas.</p>
            </div>
        </div> --}}
    </div>
@endsection
