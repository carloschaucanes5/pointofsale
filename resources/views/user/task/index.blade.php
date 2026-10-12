@extends('layouts.admin')

@section('title', 'Index')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">TAREAS POR USUARIO</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active"><a href="#">Tareas</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row" id="table-hover-row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-xl-12">
                            <form action="{{route('user.task.index')}}" method="get">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group mb-6">
                                            <span class="input-group-text" id="basic-addon1"><i
                                                    class="bi bi-search"></i></span>
                                            <!--input type="text" class="form-control" name="searchText"
                                                placeholder="Buscar Usuario" value="{{$searchText}}"
                                                aria-label="campo busqueda" aria-describedby="button-addon2"-->
                                            <select name="searchText" class="form-control">
                                                @foreach ($users as $us )
                                                    <option value="{{ $us->name }}"></option>
                                                @endforeach
                                            </select>

                                            <button class="btn btn-outline-secondary" type="submit"
                                                id="button-addon2">Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group mb-6">
                                            <span class="input-group-text" id="basic-addon1"><i
                                                    class="bi bi-plus-circle-fill"></i></span>
                                            <a href="{{route('user.task.create')}}" class="btn btn-success">Nueva</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-content">
            <div class="card-body">
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Creada</th>
                            <th>Asigna</th>
                            <th>Responsable</th>
                            <th>Tarea</th>
                            <th>Estado</th>
                            <th>F. Inicio</th>
                            <th>F. Fin</th>
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forEach($tasks as $task)
                            <tr>
                                <td>
                                    @if($users_id == $task->users_id)
                                        <a href="{{route('user.task.edit',$task->task_id)}}" class="btn btn-warning btn-sm"><i class="bi bi-pen"></i></a>
                                    @endif
                                    <!--button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-delete-{{$task->task_id}}"><i class="bi bi-trash"></i></button-->
                                </td>
                                <td>{{ date('d/m/Y', strtotime($task->created_at)) }}</td>
                                <td>{{$task->assigner_name}}</td>
                                <td>{{$task->assignee_name}}</td>
                                <td>{{$task->task_name}}</td>
                                <td>
                                    @if($task->status == 'ASIGNADA')
                                        <span class="badge rounded-pill bg-warning text-dark">
                                            ASIGNADA
                                        </span>
                                    @elseif($task->status == 'PROCESANDO')
                                        <span class="badge rounded-pill bg-info text-dark">
                                            PROCESANDO
                                        </span>
                                    @elseif($task->status == 'TERMINADA')
                                        <span class="badge rounded-pill bg-success">
                                            TERMINADA
                                        </span>
                                    @elseif($task->status == 'CANCELADA')
                                        <span class="badge rounded-pill bg-danger">
                                            CANCELADA
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary">
                                            {{ $task->status }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{ date('d/m/Y', strtotime($task->start_at)) }}</td>
                                <td>{{ date('d/m/Y', strtotime($task->end_at)) }}</td>
                                <td>{{$task->detail}}</td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
                {{$tasks->links('pagination::bootstrap-5')}}
            </div>
        </div>
    </section>
@endsection