@extends('layouts.admin')

@section('title', 'Index')

@section('content')
    
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">LISTADO DE FACTURAS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active"><a href="#">Facturas</a></li>
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
                            <form action="{{route('voucher.index')}}" method="get">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group mb-6">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                            <input type="text" class="form-control" name="searchText" placeholder="Buscar Facturas" value="{{$searchText}}" aria-label="campo busqueda" aria-describedby="button-addon2">
                                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group mb-6">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-plus-circle-fill"></i></span>
                                            <a href="{{route('voucher.create')}}" class="btn btn-success">Nueva</a>

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
                            <th>Id</th>
                            <th>Fecha</th>
                            <th>Número de Factura</th>
                            <th>valor</th>
                            <th>Descripcion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forEach($vouchers as $vou)
                        <tr>
                            <td>
                                <a href="{{route('voucher.edit',$vou->id)}}" class="btn btn-warning btn-sm"><i class="bi bi-pen"></i></a>
                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-delete-{{$vou->id}}"><i class="bi bi-trash"></i></button>
                            </td>
                            <td>{{$vou->id}}</td>
                            <td>{{$vou->updated_at}}</td>
                            <td>{{$vou->voucher_number}}</td>
                             <td>$ {{number_format($vou->total,2,',','.')}}</td>
                            <td>{{$vou->description}}</td>
                        </tr>
                        @include("purchase.voucher.modal")
                        @endforeach
                    </tbody>
                </table>
                    {{$vouchers->links('pagination::bootstrap-5')}}
            </div>
        </div>
    </section>
@endsection


