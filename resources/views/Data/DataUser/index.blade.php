@extends('layouts.user')

@section('title', 'Órdenes Asignadas')

@section('style')
<link rel="stylesheet" href="{{ asset('css/Datas/indexDataUser.css') }}">
@endsection

@section('content')
<div class="container">

    {{-- ✅ Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="header-container">
    <h2>Órdenes Pendientes</h2>

{{-- 🔍 Buscador + Ver Pendientes --}}
<div style="display: flex; justify-content: flex-end; margin-bottom: 15px; gap: 8px; flex-wrap: wrap;">


    {{-- 🔄 Botón para ir a completados --}}
    <a
        href="{{ route('datauser.verCompletados') }}"
        style="
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 6px;
            color: #ca1717;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease;
        "
        onmouseover="this.style.backgroundColor='#f0f0f0'"
        onmouseout="this.style.backgroundColor='#fff'">
        <i class='bx bx-list-check' style="font-size:18px"></i>
        Ver Completados
    </a>
</div>

    
    {{-- 🧾 Tabla de datos --}}
    @if ($data->isEmpty())
        <p class="message">No hay órdenes asignadas para mostrar.</p>
    @else
        <table class="table-vertical">
            <tbody>
                @foreach ($data as $item)
                    <tr><td colspan="5"><strong>Fecha inicio:</strong> {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') : '---' }}</td></tr>
                    <tr><td colspan="5"><strong>Ciclo:</strong> {{ $item->ciclo ?? '---' }}</td></tr>
                    <tr><td colspan="5"><strong>Cliente:</strong> {{ $item->nombres ?? '---' }}</td></tr>
                    <tr><td colspan="5"><strong>Nombre auditor:</strong> {{ $item->nombre_auditor ?? '---' }}</td></tr>
                    <tr><td colspan="5"><strong>Dirección:</strong> {{ $item->direccion ?? '---' }}</td></tr>
                    <tr><td colspan="5"><strong>Numero medidor:</strong> {{ $item->medidor ?? '---' }}</td></tr>
                    <tr>
                        <td colspan="5">
                            <strong>Estado:</strong>
                            @if ($item->estado == 1)
                                <span style="color: green; font-weight: bold;">Completado</span>
                            @else
                                <span style="color: red; font-weight: bold;">Pendiente</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <form action="{{ route('asignados.edit', $item) }}" method="GET">
                                <button type="submit" class="btn btn-tertiary">
                                    <i class='bx bx-edit'></i> Actualizar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- paginación --}}
        @if ($data->hasPages())
            <div class="pagination" style="margin-top: 15px; text-align:center;">
                {{ $data->links('pagination::bootstrap-5') }}
            </div>
        @endif
    @endif
</div>
@endsection
