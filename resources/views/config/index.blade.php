@extends('layouts.admin')

@section('title', 'Configuración')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-gear"></i>
                Configuración
            </h3>
            <span class="text-muted">
                Administración de parámetros del sistema
            </span>
        </div>

        <a href="{{ route('config.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Nueva configuración
        </a>

    </div>


    {{-- Mensaje --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Errores --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <strong>
                Parámetros del sistema
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table  table-hover ">

                    <thead class="table-dark">

                        <tr>

                            <th>
                                Key
                            </th>

                            <th>
                                Valor
                            </th>

                            <th>
                                Alias
                            </th>

                            <th
                                class="text-center">
                                Acciones
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($configs as $config)

                            <tr>

                                <td>

                                    <code>
                                        {{ $config->key }}
                                    </code>

                                </td>


                                <td>

                                    {{ Str::limit($config->value, 30) }}

                                </td>


                                <td>

                                    @if($config->alias)

                                        {{ $config->alias }}

                                    @else

                                        <span class="text-muted">
                                            Sin alias
                                        </span>

                                    @endif

                                </td>


                                <td class="text-center">

                                    <div class="btn-group">

                                        {{-- Editar --}}
                                        <a href="{{ route('config.edit', $config->key) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Editar">

                                            <i class="bi bi-pencil"></i>

                                        </a>
                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4"
                                    class="text-center py-5 text-muted">

                                    <i class="bi bi-database-x fs-2 d-block mb-2"></i>

                                    No hay configuraciones registradas.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
