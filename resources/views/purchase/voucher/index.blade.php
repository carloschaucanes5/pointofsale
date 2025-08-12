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
                                    <div class="col-md-6">
                                        <div class="input-group mb-6">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                            <input type="text" class="form-control" name="searchText" placeholder="Buscar Facturas" value="{{$searchText}}" aria-label="campo busqueda" aria-describedby="button-addon2">
                                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="from" class="form-control" value="{{ $from }}">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="to" class="form-control" value="{{ $to }}">
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group mb-6">
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
            @if ($errors->has('error'))
                <div class="alert alert-danger">
                    {{ $errors->first('error') }}
                </div>
            @endif
            <div class="card-body">
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Número de Factura</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>valor</th>
                            <th>Responsable</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forEach($vouchers as $vou)
                        <tr>
                            <td>
                                <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#modal-view-{{$vou->id}}"><i class="bi bi-eye"></i></button>
                            </td>
                            <td>{{$vou->voucher_number}}</td>
                            <td>{{$vou->supplier_name}}</td>
                            <td>{{ \Carbon\Carbon::parse($vou->updated_at)->format('d/m/Y g:i A') }}</td>
                            <td>$ {{number_format($vou->total,2,',','.')}}</td>
                            <td>{{$vou->user_name}}</td>
                            <td>{{$vou->status_payment}}</td>

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


