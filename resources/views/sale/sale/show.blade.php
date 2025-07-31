@extends('layouts.admin')

@section('title', 'Mostrar Ingreso')

@section('content')

    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Detalle Venta</h3>
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
                                <p>{{$sale->created_at}}</p>
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
                                    <th id="total">$ {{number_format($total->sale_total,0)}}</th>
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
                                            @if($det->quantity > 0)
                                            <a  class="btn btn-warning btn-sm" title="Efectuar Devolución" data-bs-toggle="modal" data-bs-target="#modal-return-{{$det->income_detail_id}}"><i class="bi bi-arrow-return-left">Devolver</i></a> 
                                            @endif
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
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Detalle Devolución</h3>
            </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-hover mb-1" id="detalles">
                                <thead >
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Producto</th>
                                        <th>Laboratorio</th>
                                        <th>F.V</th>
                                        <th>Cantidad</th>
                                        <th>Total</th>
                                        <th>Responsable</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Total:</th>
                                    <th id="total">$ {{number_format($sum_return_total,0)}}</th>
                                    <td></td>
                                    <td></td> 
                                </tfoot>
                                <tbody>
                                    
                                  @foreach($return_sales as $ret)
                                    <tr>
                                        <td>{{$ret->created_at}}</td>
                                        <td>{{$ret->article}} {{$det->concentration}} {{$det->presentation}}</td>
                                        <td>{{$ret->laboratory}}</td>
                                        <td>{{$ret->form_sale}}</td>
                                        <td>{{$ret->quantity}}</td>
                                        <td>{{$ret->return_total}}</td>
                                        <td>{{$ret->user_name}}
                                    </tr>
                                    @include('sale.sale.return')
                                  @endforeach
                                </tbody> 
                            </table>
                        </div>
                    </div>
                </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Metodos de Pago</h3>
            </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-hover mb-1" id="detalles">
                                <thead >
                                    <tr>
                                        <th>Metodo</th>
                                        <th>valor</th>
                                        <th>opciones</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <th>Total:</th>
                                    <th id="total">$ {{number_format($total->sale_total,0)}}</th>
                                    
                                </tfoot>
                                <tbody>
                                    
                                  @foreach($payment_methods as $met)
                                    <tr>
                                        <td>{{$met->method}}</td>
                                        <td>{{$met->value}}</td>
                                        @if($met->method == 'credito')
                                            <td><button type="button" class="btn btn-success btn-sm" onclick="pay_credit({{$sale->id}},{{$met->id}},{{$met->value}})"  data-bs-toggle="#"><i class="bi bi-credit-card" ></i>Pagar</button></td>
                                        @else
                                            <td></td>
                                        @endif
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

<script>
    function pay_credit(sale_id, payment_id, value) {
        Swal.fire({
            title: 'Confirmar Pago',
            text: "¿Está seguro de que desea pagar el crédito?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, pagar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('sale.pay_credit') }}",
                    type: "POST",
                    data: {
                        sale_id: sale_id,
                        payment_id: payment_id,
                        value: value,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire(
                            'Pagado!',
                            'El pago se ha realizado correctamente.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'No se pudo realizar el pago. Inténtelo de nuevo.',
                            'error'
                        );
                    }
                });
            }
        });
    }
<script>

