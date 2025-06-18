@extends('layouts.admin')

@section('title', 'Index')

@section('content')
    
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">INVENTARIO DISPONIBLE</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active"><a href="#">Inventario</a></li>
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
                            <form action="{{route('inventory.index')}}" method="get">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group mb-6">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                            <input type="text" class="form-control" name="searchText" placeholder="Buscar Productos" value="{{$searchText}}" aria-label="campo busqueda" aria-describedby="button-addon2">
                                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group mb-6">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-plus-circle-fill"></i></span>
                                            <a href="{{route('product.create')}}" class="btn btn-success">Nueva</a>

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
                            <th>Codigo Barras</th>
                            <th>Nombre</th>
                            <th>Cant.</th>
                            <th>Costo/U</th>
                            <th>Precio/U</th>
                            <th>Forma Venta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forEach($products as $pro)
                        <tr>
                            <td>
                                <a href="{{route('product.edit',$pro->id)}}" class="btn btn-warning btn-sm"><i class="bi bi-box-arrow-up"></i></a>
                                <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#modal-detail-{{$pro->id}}"><i class="bi bi-eye"></i></button>
                            </td>
                            <td>{{$pro->code}}</td>
                            <td>{{$pro->name}} {{$pro->concentration}} {{$pro->presentation}} Lab:{{$pro->laboratory}}</td>
                            <td>{{$pro->quantity}}</td>
                            <td>${{number_format($pro->purchase_price,2,",",".")}}</td>
                            <td>{{$pro->sale_price}}</td>
                            <td>{{$pro->form_sale}}</td>
                        </tr>
                        @include('store.product.modal')
                        @include('store.product.detail')
                        @endforeach
                    </tbody>
                </table>
                    {{$products->links('pagination::bootstrap-5')}}
            </div>
        </div>
    </section>
@endsection


