@extends('layouts.admin')

@section('title', 'Recepciones Facturas')

@section('content')
<div class="container">
    <h3 class="mb-4">Productos Vendidos</h3>

<form action="{{ route('report.sale.sold_products') }}" method="GET" class="row g-3 mb-4">
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
                <th>Cod.</th>
                <th>Producto</th>
                <th>Laboratorio</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $pro)
            <tr>
                <td>{{$pro->code}}</td>
                <td>{{$pro->name}} {{$pro->concentration}} {{$pro->presentation}}</td>
                <td>{{$pro->laboratory}}</td>
                <td>{{$pro->total_sold}}</td>
            </tr>
            @endforeach
            
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7">
                    {{$products->links()}}
                <td>
            <tr>
        </tfoot>
    </table>

</div>
@endsection