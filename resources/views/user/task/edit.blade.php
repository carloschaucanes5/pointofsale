@extends('layouts.admin')

@section('title', 'Editar Tarea')

@section('content')
    <div class="col-md-6">
        <div class="card card-primary">

            <div class="card-header">
                <h3 class="card-title">Editar Tarea</h3>
            </div>

            {{-- MENSAJES DE ERROR --}}
            @if ($errors->any())

                <div class="alert alert-danger m-3">

                    <strong>Se encontraron los siguientes errores:</strong>

                    <ul class="mb-0 mt-2">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- MENSAJE DE ÉXITO --}}
            @if (session('success'))

                <div class="alert alert-success m-3">
                    {{ session('success') }}
                </div>

            @endif


            <form action="{{ route('user.task.update', $task->id) }}" method="POST" class="form">

                @csrf
                @method('PUT')

                <div class="card-body">

                    {{-- Asignar a --}}
                    <div class="form-group">
                        <label for="assignee_id">Asignar a:</label>

                        <select name="assignee_id" id="assignee_id" class="form-control">

                            @foreach ($users as $us)

                                <option value="{{ $us->id }}" {{ old('assignee_id', $task->assignee_id) == $us->id ? 'selected' : '' }}>

                                    {{ $us->name }}

                                </option>

                            @endforeach

                        </select>
                    </div>


                    {{-- Tarea --}}
                    <div class="form-group">
                        <label for="name">Tarea</label>

                        <select name="name" id="name" class="form-control">

                            @foreach ($tasks as $ta)

                                <option value="{{ $ta }}" {{ old('name', $task->name) == $ta ? 'selected' : '' }}>

                                    {{ $ta }}

                                </option>

                            @endforeach

                        </select>
                    </div>

                    {{-- Estado --}}
                    <div class="form-group">
                        <label for="status">Estado</label>

                        <select name="status" id="status" class="form-control">

                            @foreach ($status as $st)

                                <option value="{{ $st }}" {{ old('status', $task->status) == $st ? 'selected' : '' }}>

                                    {{ $st }}

                                </option>

                            @endforeach

                        </select>
                    </div>


                    {{-- Fecha inicial --}}
                    <div class="form-group">
                        <label for="start_at">F. Inicio</label>

                        <input type="date"
                            value="{{ old('start_at', $task->start_at ? \Carbon\Carbon::parse($task->start_at)->format('Y-m-d') : $fechaInicial) }}"
                            class="form-control" name="start_at" id="start_at">
                    </div>


                    {{-- Fecha final --}}
                    <div class="form-group">
                        <label for="end_at">F. Final</label>

                        <input type="date"
                            value="{{ old('end_at', $task->end_at ? \Carbon\Carbon::parse($task->end_at)->format('Y-m-d') : $fechaFinal) }}"
                            class="form-control" name="end_at" id="end_at">
                    </div>


                    {{-- Detalle --}}
                    <div class="form-group">
                        <label for="detail">Detalle</label>

                        <textarea id="detail" class="form-control" name="detail" cols="50"
                            rows="5">{{ old('detail', $task->detail) }}</textarea>
                    </div>

                </div>


                <div class="card-footer">

                    <button type="submit" class="btn btn-success me-1 mb-1">
                        Actualizar
                    </button>

                    <a href="{{ url()->previous() }}" class="btn btn-danger me-1 mb-1">
                        Cancelar
                    </a>

                </div>

            </form>

        </div>
    </div>
@endsection