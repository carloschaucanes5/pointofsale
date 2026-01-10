@extends('layouts.admin')

@section('title', 'Recepciones Facturas')

@section('content')
<div class="container">
    <h3 class="mb-4">Productos Vendidos</h3>

<form action="{{ route('report.sale.sold_products') }}" method="GET" class="row g-3 mb-4">
    <div class="col-md-2">
        <label>Fecha Inicio:</label>
        <input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}">
    </div>
    <div class="col-md-2">
        <label>Fecha Fin:</label>
        <input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}">
    </div>

    <div class="col-md-3">
        <label>Abastecer dias:</label>
        <input type="number" name="pedir"  class="form-control form-control-sm" value="{{$pedir}}">
    </div>

    <div class="col-md-4">
        <label>Buscar Texto:</label>
        <input type="text" name="text_search" class="form-control form-control-sm" value="{{$text_search}}">
    </div>

    <div class="col-md-1">
        <button class="btn btn-outline-primary btn-sm">Generar</button>
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
                <th>Forma Venta</th>
                <th>Ventas</th>
                <th>Existencias</th>
                <th>Pedir</th>
                <th>Costo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $pro)
            @php
                $clase = "";
                if($pro->existencias <= (0.05*$pro->ventas))
                   $clase="table-danger";
                elseif($pro->existencias > (0.05*$pro->ventas)  && $pro->existencias <= (0.2*$pro->ventas))
                    $clase="table-warning";
                else
                    $clase="table-success";

            @endphp
            <tr class="{{$clase}}">
                <td>{{$pro->code}}</td>
                <td>{{$pro->name}} {{$pro->concentration}} {{$pro->presentation}}</td>
                <td>{{$pro->laboratory}}</td>
                <td>{{$pro->form_sale}}</td>
                <td title="ventas">{{$pro->ventas}}</td>
                <td title="existencias">{{$pro->existencias}}</td>
                @php
                    $pe = 0;
                    $pe1 = 0;
                    $dias = $dias > 0 ? $dias : 1;
                    $ventas = $pro->ventas ?? 0;
                    $existencias = $pro->existencias ?? 0;
                    $pedir = $pedir ?? 0;
                    $pe = ($ventas * $pedir) / $dias;
                    $pe1 = $pe - $existencias;
                @endphp
                <td title="pedir">{{number_format($pe1,0,',','.')}}</td>
                <td title="costo">{{number_format($pro->costo,0,',','.')}}</td>
            </tr>
            @endforeach
            
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-center">
                    {{$products->links()}}
                <td>
            <tr>
        </tfoot>
    </table>

</div>
@endsection