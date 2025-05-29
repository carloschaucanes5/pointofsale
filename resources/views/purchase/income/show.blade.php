@extends('layouts.admin')

@section('title', 'Mostrar Ingreso')

@section('content')
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Detalle Ingreso</h3>
            </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="supplier_id"></label>
                            <p>{{$income->name}}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="voucher_type">Tipo de comprobante</label>
                                <p>{{$income->voucher_type}}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="voucher_number">Número de comprobante</label>
                                <p>{{$income->voucher_number}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-hover mb-1" id="detalles">
                                <thead >
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Compra</th>
                                        <th>Precio Venta</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Total:</th>
                                    <th id="total">$ {{number_format($income->total,2)}}</th>
                                </tfoot>
                                <tbody>
                                  @foreach($details as $det)
                                    <tr>
                                        <td>{{$det->article}}</td>
                                        <td>{{$det->quantity}}</td>
                                        <td>{{$det->purchase_price}}</td>
                                        <td>{{$det->sale_price}}</td>
                                        <td>{{number_format($det->quantity * $det->purchase_price,2)}}</td>
                                        <td></td>
                                    </tr>
                                  @endforeach
                                </tbody> 
                            </table>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li> {{-- Incluye el mensaje personalizado --}}
            @endforeach
        </ul>
    </div>
   @endif

@endsection

