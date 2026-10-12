@extends('layouts.admin')

@section('title', 'Editar Tarea')

@section('content')

    ```
    <div class="container-fluid">

        <div class="row">

            {{-- ================================================= --}}
            {{-- FORMULARIO EDITAR TAREA --}}
            {{-- ================================================= --}}
            <div class="col-12 col-lg-6 mb-4">

                <div class="card card-primary h-100">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit mr-2"></i>
                            Editar Tarea
                        </h3>
                    </div>

                    {{-- MENSAJES DE ERROR --}}
                    @if ($errors->any())

                        <div class="alert alert-danger m-3">

                            <strong>
                                Se encontraron los siguientes errores:
                            </strong>

                            <ul class="mb-0 mt-2">

                                @foreach ($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif

                    {{-- MENSAJE DE ÉXITO --}}
                    @if (session('success'))

                        <div class="alert alert-success m-3">
                            <i class="fas fa-check-circle mr-1"></i>
                            {{ session('success') }}
                        </div>

                    @endif

                    <form action="{{ route('user.task.update', $task->id) }}" method="POST">

                        @csrf
                        @method('PUT')

                        <div class="card-body">

                            {{-- Asignó --}}
                            <div class="form-group">

                                <label for="assigner_id">
                                    Asignó:
                                </label>

                                <select name="assigner_id" id="assigner_id" class="form-control" disabled>

                                    @foreach ($users as $us)

                                        <option value="{{ $us->id }}" {{ old('assigner_id', $task->assigner_id) == $us->id ? 'selected' : '' }}>

                                            {{ $us->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            {{-- Responsable --}}
                            <div class="form-group">

                                <label for="assignee_id">
                                    Responsable:
                                </label>

                                <select name="assignee_id" id="assignee_id" class="form-control" disabled>

                                    @foreach ($users as $use)

                                        <option value="{{ $use->id }}" {{ old('assignee_id', $task->assignee_id) == $use->id ? 'selected' : '' }}>

                                            {{ $use->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            {{-- Tarea --}}
                            <div class="form-group">

                                <label for="name">
                                    Tarea:
                                </label>

                                <select name="name" id="name" class="form-control" disabled>

                                    @foreach ($tasks as $ta)

                                        <option value="{{ $ta }}" {{ old('name', $task->name) == $ta ? 'selected' : '' }}>

                                            {{ $ta }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            {{-- Estado --}}
                            <div class="form-group">

                                <label for="status">
                                    Estado:
                                </label>

                                <select name="status" id="status" class="form-control" >

                                    @foreach ($status as $st)

                                        <option value="{{ $st }}" {{ old('status', $task->status) == $st ? 'selected' : '' }}>

                                            {{ $st }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            {{-- Fecha inicial --}}
                            <div class="form-group">

                                <label for="start_at">
                                    F. Inicio:
                                </label>

                                <input type="date"
                                    value="{{ old('start_at', $task->start_at ? \Carbon\Carbon::parse($task->start_at)->format('Y-m-d') : $fechaInicial) }}"
                                    class="form-control" name="start_at" id="start_at" disabled>

                            </div>

                            {{-- Fecha final --}}
                            <div class="form-group">

                                <label for="end_at">
                                    F. Final:
                                </label>

                                <input type="date"
                                    value="{{ old('end_at', $task->end_at ? \Carbon\Carbon::parse($task->end_at)->format('Y-m-d') : $fechaFinal) }}"
                                    class="form-control" name="end_at" id="end_at" disabled>

                            </div>

                            {{-- Detalle --}}
                            <div class="form-group">

                                <label for="detail">
                                    Detalle:
                                </label>

                                <textarea id="detail" class="form-control" name="detail" rows="3"
                                    placeholder="Ingrese el detalle de la tarea..." disabled>{{ old('detail', $task->detail) }}</textarea>

                            </div>

                        </div>

                        <div class="card-footer">

                            <button type="submit" class="btn btn-success mr-1">

                                <i class="fas fa-save mr-1"></i>
                                Actualizar

                            </button>

                            <a href="{{ url()->previous() }}" class="btn btn-danger">

                                <i class="fas fa-times mr-1"></i>
                                Cancelar

                            </a>

                        </div>

                    </form>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- PANEL DE RESPUESTAS --}}
            {{-- ================================================= --}}
            <div class="col-12 col-lg-6 mb-4">

                <div class="card card-success">

                    <div class="card-header">

                        <h3 class="card-title">
                            <i class="fas fa-comments mr-2"></i>
                            Respuestas de la Tarea
                        </h3>

                    </div>


                    {{-- ================================================= --}}
                    {{-- NUEVA RESPUESTA --}}
                    {{-- ================================================= --}}

                    <div class="card-body">

                        <div class="card card-outline card-primary mb-4">

                            <div class="card-header">

                                <h3 class="card-title">

                                    <i class="fas fa-plus-circle mr-2"></i>
                                    Nueva respuesta

                                </h3>

                            </div>


                            {{-- MENSAJE DE ÉXITO --}}
                            @if (session('success_response'))

                                <div class="alert alert-success m-3">

                                    <i class="fas fa-check-circle mr-1"></i>

                                    {{ session('success_response') }}

                                </div>

                            @endif


                            <form action="{{ route('user.task.response.store', $task->id) }}" method="POST"
                                enctype="multipart/form-data">

                                @csrf

                                <div class="card-body">

                                    {{-- Respuesta --}}
                                    <div class="form-group">

                                        <label for="response">

                                            <i class="fas fa-comment-alt mr-1"></i>
                                            Respuesta:

                                        </label>

                                        <textarea id="response" name="response" class="form-control" rows="4"
                                            placeholder="Ingrese la respuesta de la tarea..."
                                            required>{{ old('response') }}</textarea>

                                    </div>


                                    {{-- Documento adjunto --}}
                                    <div class="form-group">

                                        <label for="attachment">

                                            <i class="fas fa-paperclip mr-1"></i>
                                            Documento adjunto:

                                        </label>

                                        <input type="file" name="attachment" id="attachment" class="form-control"
                                            accept=".pdf,.doc,.docx,.xls,.xlsx">

                                        <small class="form-text text-muted">

                                            <i class="fas fa-info-circle mr-1"></i>

                                            Formatos permitidos:
                                            PDF, Word y Excel.
                                            Tamaño máximo: 10 MB.

                                        </small>

                                    </div>

                                </div>


                                <div class="card-footer">

                                    <button type="submit" class="btn btn-primary">

                                        <i class="fas fa-paper-plane mr-1"></i>
                                        Guardar respuesta

                                    </button>

                                </div>

                            </form>

                        </div>


                        {{-- ================================================= --}}
                        {{-- RESPUESTAS EXISTENTES --}}
                        {{-- ================================================= --}}

                        <div class="mb-3">


                            <h5 class="text-muted">
                                <i class="fas fa-history mr-1"></i>
                                Historial de respuestas
                            </h5>


                        </div>

                        {{-- CONTENEDOR CON SCROLL --}}

                        <div style="max-height: 400px;overflow-y: auto;padding-right: 8px;">

                            @forelse ($responses as $response)

                                <div class="card mb-3 shadow-sm">

                                    {{-- Cabecera respuesta --}}
                                    <div class="card-header bg-light">

                                        <div class="d-flex justify-content-between align-items-center">

                                            <div>

                                                <strong>

                                                    <i class="fas fa-user mr-1"></i>

                                                    {{ $response->username ?? 'Usuario desconocido' }}

                                                </strong>

                                            </div>

                                            <small class="text-muted">

                                                <i class="far fa-clock mr-1"></i>

                                                {{ \Carbon\Carbon::parse($response->created_at)->format('d/m/Y H:i') }}

                                            </small>

                                        </div>

                                    </div>


                                    {{-- Contenido respuesta --}}
                                    <div class="card-body">

                                        {{-- Texto de la respuesta --}}
                                        @if ($response->response)

                                            <div class="mb-3">

                                                <strong class="d-block mb-1">
                                                    Respuesta:
                                                </strong>

                                                <p class="mb-0">

                                                    {{ $response->response }}

                                                </p>

                                            </div>

                                        @endif


                                        {{-- Archivo adjunto --}}
                                        @if ($response->attachment_path)

                                            <div>

                                                <strong class="d-block mb-2">

                                                    <i class="fas fa-paperclip mr-1"></i>
                                                    Archivo adjunto:

                                                </strong>

                                                <a href="{{ route('user.task.response.download', $response->id) }}"
                                                    class="btn btn-sm btn-outline-primary">

                                                    <i class="fas fa-download mr-1"></i>
                                                    Descargar documento

                                                </a>

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            @empty

                                <div class="alert alert-info">

                                    <i class="fas fa-info-circle mr-1"></i>

                                    Esta tarea todavía no tiene respuestas.

                                </div>

                            @endforelse
                            ```

                        </div>


                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection