@extends('layouts.admin')

@section('title', 'Aperturas de Caja')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">LISTADO DE APERTURAS DE CAJA</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Aperturas de Caja</li>
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
                        <form action="{{ route('cash_opening.index') }}" method="get">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-group mb-6">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" name="searchText" placeholder="Buscar apertura" value="{{ $searchText ?? '' }}">
                                        <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-group mb-6">
                                        <span class="input-group-text"><i class="bi bi-plus-circle-fill"></i></span>
                                        <a href="{{ route('cash_opening.create') }}" class="btn btn-success">Nueva Apertura</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success mx-3">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card-content">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Caja</th>
                                    <th>Monto Inicial</th>
                                    <th>Ubicación</th>
                                    <th>Observaciones</th>
                                    <th>Estado</th>
                                    <th>Fecha Apertura</th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($openings as $open)
                                <tr>
                                    <td>
                                        <a href="{{ route('cash_opening.edit', $open->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pen"></i></a>
                                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-delete-{{ $open->id }}"><i class="bi bi-trash"></i></button>
                                    </td>
                                    <td>{{ $open->cashbox_name }}</td>
                                    <td>${{ number_format($open->start_amount, 0, ',', '.') }}</td>
                                    <td>{{ $open->location }}</td>
                                    <td>{{ $open->observations }}</td>
                                    <td>
                                        <span class="badge bg-{{ $open->status == 'open' ? 'success' : 'secondary' }}">{{ ucfirst($open->status) }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($open->opened_at)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $open->user->name ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $openings->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


