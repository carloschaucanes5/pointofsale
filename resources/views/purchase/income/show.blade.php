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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="supplier_id">Proveedor</label>
                                <p>{{$income->name}}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="voucher_number">Número de comprobante</label>
                                <p>{{$income->voucher_number}}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label for="users_id">Usuario</label>
                            <p>{{$income->users_name}}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label for="users_id">Valor Factura</label>
                            <p>{{number_format($income->total,2,",",".")}}</p>
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
                                        <th>Subtotal</th>
                                        <th>Precio Venta</th>
                                        <th>Forma Venta</th>
                                        <th>F. Venc.</th>
                                        <th>Lote</th>
                                        <th>Invima</th>
                                    </tr>
                                </thead>
                                <tbody>
                                 @php
                                    $sum = 0;
                                 @endphp
                                  @foreach($details as $det)
                                  @php
                                    $sum = $sum + ($det->quantity * $det->purchase_price);
                                  @endphp
                                    <tr>
                                        <td>{{$det->article}} {{$det->concentration}} {{$det->presentation}}</td>
                                        <td>{{$det->quantity}}</td>
                                        <td>$ {{number_format($det->purchase_price,2,",",".")}}</td>
                                        <td>$ {{number_format($det->quantity * $det->purchase_price,2,",",".")}}</td>
                                        <td>$ {{number_format($det->sale_price,0,",",".")}}</td>
                                        <td>{{$det->form_sale}}</td>
                                        <td>{{$det->expiration_date}}</td>
                                        <td>{{$det->lote}}</td>
                                        <td>{{$det->invima}}</td>
                                    </tr>
                                  @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Total:</th>
                                    <th id="total">{{number_format($sum,2)}}</th>
                                    <th></th>
                                    </tr>
                                </tfoot> 
                            </table>
                                <a href="{{ route('purchase.income.export',$income_id) }}" class="btn btn-success">
                                    <i class="bi bi-file-earmark-spreadsheet-fill"></i> Exportar
                                </a>
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

