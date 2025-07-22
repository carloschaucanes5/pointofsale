@extends('layouts.admin')

@section('title', 'Movimientos de Caja')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Listado de Movimientos</h3>

<form action="{{ route('movement.index') }}" method="GET" class="row g-3 mb-4">
    <div class="col-md-3">
        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
    </div>
    <div class="col-md-3">
        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
    </div>
    <div class="col-md-3">
        <button class="btn btn-outline-primary">Filtrar</button>
    </div>
    <div class="col-md-3 text-end">
        <a href="{{ route('movement.create') }}" class="btn btn-success">+ Nuevo Movimiento</a>
    </div>
</form>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Categoría</th>
                <th>Monto</th>
                <th>Método</th>
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($movements as $movement)
                <tr>
                    <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        @if ($movement->type == 'income')
                            <span class="badge bg-success">Ingreso</span>
                        @else
                            <span class="badge bg-danger">Egreso</span>
                        @endif
                    </td>
                    <td>{{ $movement->category }}</td>
                    <td>${{ number_format($movement->amount, 2) }}</td>
                    <td>{{ $movement->payment_method }}</td>
                    <td>{{ $movement->user->name ?? '---' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay movimientos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{-- Paginación --}}
    <div class="d-flex justify-content-center">
        {{ $movements->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection