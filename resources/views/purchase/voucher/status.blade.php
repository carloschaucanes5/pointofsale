@extends('layouts.admin')

@section('title', 'Mostrar Ingreso')

@section('content')
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Estado Factura</h3>
            </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered table-hover table-sm table-striped  small">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Factura</th>
                                        <th>Fecha</th>
                                        <th>Proveedor</th>
                                        <th>Cod</th>
                                        <th>Producto</th>
                                        <th>Laboratorio</th>
                                        <th>C. Inicial</th>
                                        <th>C. Actual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vouchers as $vou)
                                    <tr>
                                        <td><a href="#" data-bs-toggle="modal" data-bs-target="#modal-view-{{$vou->id}}" >{{$vou->voucher_number}}</a></td>
                                        <td>{{ \Carbon\Carbon::parse($vou->created_at)->format('d/m/Y')}}</td>
                                        <td>{{$vou->supplier}}</td>
                                        <td>{{$vou->code}}</td>
                                        <td>{{$vou->name}} {{$vou->concentration}} {{$vou->presentation}}</td>
                                        <td>{{$vou->laboratory}}</td>
                                        <td>{{$vou->inicial}}</td>
                                        <td>{{$vou->actual}}</td>
                                    </tr>
                                    @include("purchase.voucher.modal")
                                    @endforeach
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6">
                                            {{$vouchers->links()}}
                                        <td>
                                    <tr>
                                </tfoot>
                            </table>                  
                            <a href="#" class="btn btn-success">
                                <i class="bi bi-file-earmark-spreadsheet-fill"></i> Exportar
                            </a>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection

