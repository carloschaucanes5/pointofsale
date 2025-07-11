@extends('layouts.admin')

@section('title', 'Mostrar Ingreso')

@section('content')

    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Detalle Venta (Devolución)</h3>
            </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                            <label>VENTA</label>
                            <p>POS{{$sale->id}}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label>Cliente:</label>
                            <p>{{$sale->customer_name}}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha:</label>
                                <p>{{$sale->updated_at}}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Forma de pago</label>
                                <p>{{$sale->payment_form}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-hover mb-1" id="detalles">
                                <thead >
                                    <tr>
                                        <th>Producto</th>
                                        <th>Laboratorio</th>
                                        <th>Cantidad</th>
                                        <th>Precio Venta</th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>
                                        <th>F.V</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Total:</th>
                                    <th id="total">$ {{number_format($sale->sale_total,2)}}</th>
                                    <td></td>
                                    <td></td> 
                                </tfoot>
                                <tbody>
                                    
                                  @foreach($details as $det)
                                    <tr>
                                        <td>{{$det->article}} {{$det->concentration}} {{$det->presentation}}</td>
                                        <td>{{$det->laboratory}}</td>
                                        <td>{{$det->quantity}}</td>
                                        <td>{{$det->sale_price}}</td>
                                        <td>{{$det->discount}}</td>
                                        <td>{{number_format(($det->quantity * $det->sale_price)-$det->discount,2)}}</td>
                                        <td>{{$det->form_sale}}</td>
                                       <td>
                                            <a  class="btn btn-warning btn-sm" title="Efectuar Devolución" data-bs-toggle="modal" data-bs-target="#modal-return-{{$det->income_detail_id}}"><i class="bi bi-arrow-return-left"></i></a> 
                                       </td> 
                                    </tr>
                                    @include('sale.sale.return')
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

