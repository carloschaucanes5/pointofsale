@extends('layouts.admin')

@section('title', 'Index')

@section('content')
    <style>
    .table th, .table td {
        font-size: 0.8rem;
    }
</style>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Salidas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active"><a href="#">Salidas</a></li>
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
                        <div class="col-xl-12" >
                            <form action="{{route('store.inventory.outs')}}" method="get">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group mb-6">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                            <input type="text" class="form-control" name="searchText" placeholder="Buscar Productos" value="{{$searchText}}" aria-label="campo busqueda" aria-describedby="button-addon2">
                                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Buscar</button>
                                        </div>
                                    </div>
                                   
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <input type="date" name="start_date" class="form-control" value="{{old('start_date',$start_date)}}">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <input type="date"  name="end_date" class="form-control" value="{{old('end_date',$end_date)}}">
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
                            <th>Fecha</th>
                            <th>CB</th>
                            <th>Nombre</th>
                            <th>Cant.</th>
                            <th>Costo/U</th>
                            <th>Precio/U</th>
                            <th>Forma Venta</th>
                            <th>Detalle</th>
                            <th>Responsable</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forEach($outs_detail as $incd)
                        <tr>
                            <td>{{$incd->created_at}}</td>
                            <td>{{$incd->code}}</td>
                            <td>{{$incd->name}} {{$incd->concentration}} {{$incd->presentation}} Lab:{{$incd->laboratory}}</td>
                            <td>{{$incd->quantity_out}}</td>
                            <td>${{number_format($incd->purchase_price,2,",",".")}}</td>
                            <td>{{$incd->sale_price}}</td>
                            <td>{{$incd->form_sale}}</td>
                            <td>{{$incd->description}}</td>
                            <td>{{$incd->user_name}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                    {{$outs_detail->links('pagination::bootstrap-5')}}
            </div>
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif
        </div>
    </section>
@endsection


