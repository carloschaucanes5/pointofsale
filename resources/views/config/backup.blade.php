@extends('layouts.admin')

@section('title', 'Index')

@section('content')
<div class="card">
    <div class="card-body">

        {{-- EXPORTAR --}}
        <h4>EXPORTAR BASE DE DATOS</h4>
        <form action="{{ route('config.exportar') }}" method="GET">
            <button type="submit" class="btn btn-primary">
                Descargar SQL
            </button>
        </form>

        <hr>

        {{-- IMPORTAR --}}
        <h4>IMPORTAR  BASE DE DATOS</h4>
        <form action="{{ route('config.importar') }}"
              method="POST"
              enctype="multipart/form-data"
              onsubmit="return confirm('Esto reemplazará la información actual de la base de datos. ¿Deseas continuar?')">

            @csrf

            <div class="mb-3">
                <label class="form-label">
                    Seleccionar archivo SQL
                </label>

                <input type="file"
                       name="backup"
                       class="form-control"
                       accept=".sql"
                       required>
            </div>

            <button type="submit" class="btn btn-danger">
                Importar SQL
            </button>
        </form>

        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif

    </div>
</div>

 
@endsection