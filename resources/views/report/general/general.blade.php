@extends('layouts.admin')

@section('title', 'Recepciones Facturas')

@section('content')
<style>
    .table th, .table td {
        font-size: 0.8rem;
    }
    .cel-spent {
        background-color: rgb(253, 169, 112) !important;
    }
    .cel-income{
        background-color: rgb(130, 247, 130) !important;
    }
</style>
<div class="container">
    <h3 class="mb-4">Balance  General</h3>

<form action="{{ route('report.cash_opening.general') }}" method="GET" class="row g-3 mb-4">
    <div class="col-md-2">
        <input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}">
    </div>
    <div class="col-md-2">
        <input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}">
    </div>
    <div class="col-md-3">
        <input type="text" name="text_search" class="form-control form-control-sm" value="{{ $text_search }}" placeholder="Buscar por detalle">
    </div>
    <div class="col-md-2">
        <select name="type_movement" class="form-select form-select-sm">
            <option value="">-- Tipo de Movimiento --</option>
            @foreach($types_movement as $type)
                <option value="{{ $type->id }}" @if(request('type_movement') == $type->id) selected @endif>{{ $type->name }}</option>
            @endforeach
        </select>
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
                <th>Tipo de Movimiento</th>
                <th>Detalle</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="3"><strong>Egresos</strong></td>
            </tr>
            @foreach($movements as $mov)
                @if($mov->type == 'egreso')
                    <tr>
                        <td>{{$mov->created_at}}</td>
                        <td>{{$mov->movement_type}}</td>
                        <td>{{$mov->description}}</td>
                        <td class="cel-spent">{{$mov->total}}</td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td colspan="3"><strong>Ingresos</strong></td>
            </tr>
            @foreach($movements as $mov)
                @if($mov->type == 'ingreso')
                    <tr>
                        <td>{{$mov->created_at}}</td>
                        <td>{{$mov->movement_type}}</td>
                        <td>{{$mov->description}}</td>
                        <td class="cel-income">{{$mov->total}}</td>
                    </tr>
                @endif
            @endforeach
                       
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>Totales</strong></td>
            </tr>
            <tr>
                <td><strong>Total Egresos</strong></td>
                <td class="cel-spent"><strong>{{ number_format($total_spent,2) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Total Ingresos</strong></td>
                <td class="cel-income"><strong>{{ number_format($total_income,2) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Balance</strong></td>
                <td><strong>{{ number_format($total_income + $total_spent,2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

</div>
@endsection