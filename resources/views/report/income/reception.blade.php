@extends('layouts.admin')

@section('title', 'Recepciones Facturas')

@section('content')
<div class="container">
    <h3 class="mb-4">Recepción de Facturas</h3>

<form action="{{ route('report.income.reception') }}" method="GET" class="row g-3 mb-4">
    <div class="col-md-3">
        <input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}">
    </div>
    <div class="col-md-3">
        <input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}">
    </div>

    <div class="col-md-3">
        <button class="btn btn-outline-primary btn-sm">Buscar</button>
    </div>
</form>
    @if ($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif
    <table class="table table-bordered table-hover table-sm table-striped">
        <thead class="table-dark">
            <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Categoría</th>
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vouchers as $voucher)
            
            @endforeach

        </tbody>
    </table>

</div>
@endsection