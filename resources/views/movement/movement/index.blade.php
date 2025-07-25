@extends('layouts.admin')

@section('title', 'Movimientos de Caja')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Listado de Movimientos</h3>

<form action="{{ route('movement.index') }}" method="GET" class="row g-3 mb-4">
    <div class="col-md-2">
        <input type="date" name="from" class="form-control" value="{{ $from }}">
    </div>
    <div class="col-md-2">
        <input type="date" name="to" class="form-control" value="{{ $to }}">
    </div>
    <div class="col-md-2">
        <select name="type" class="form-control" value="{{ $type }}">
            
            @if($type =='egreso')
                <option value="">Todos</option>
                <option value="egreso" selected>egreso</option>
                <option value="ingreso">ingreso</option>
            @elseif($type=="ingreso")
                <option value="">Todos</option>
                <option value="egreso">egreso</option>
                <option value="ingreso" selected>ingreso</option>
            @else
                <option value="" selected>Todos</option>
                <option value="egreso">egreso</option>
                <option value="ingreso">ingreso</option>
            @endif

        </select>
    </div>
    <div class="col-md-3">
        <button class="btn btn-outline-primary">Buscar</button>
    </div>
    <div class="col-md-3 text-end">
        <a href="{{ route('movement.create') }}" class="btn btn-success">+ Nuevo </a>
    </div>
</form>
    @if ($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Categoría</th>
                @foreach($payment_methods as $method)
                    <th>{{$method}}</th>
                @endforeach
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($movements as $movement)
                <tr>
                    <td>{{ $movement->created_at}}</td>
                    <td>
                        @if ($movement->type == 'ingreso')
                            <span class="badge bg-success">Ingreso</span>
                        @else
                            <span class="badge bg-danger">Egreso</span>
                        @endif
                    </td>
                    <td>{{ $movement->movement_type }}<small style="font-size: 12px;"> {{$movement->description}}</small></td>
                        @foreach($payment_methods as $method)
                            @if($method ==$movement->payment_method)
                                <td>${{ number_format($movement->amount, 2) }}</td>
                            @else
                                <td></td>
                            @endif
                        @endforeach

                    <td>{{ $movement->username ? $movement->username:'---' }}</td>
                </tr>
            @empty
            @endforelse
        </tbody>
    </table>
    {{-- Paginación --}}
    <div class="d-flex justify-content-between">
        {{ $paginator->links() }}
    </div>
</div>
@endsection