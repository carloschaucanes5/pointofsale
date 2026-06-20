@extends('layouts.admin')

@section('title', 'Index')

@section('content')
   
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="m-0">LISTADO DE VENTAS</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active"><a href="#">Ventas</a></li>
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
                            <form action="{{route('sale.index')}}" method="get">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group mb-6">
                                            @php
                                            $placeholder = "Buscar Cliente";
                                            if($detail){
                                                $placeholder = "Buscar Producto";
                                            }
                                            @endphp
                                            <b>&nbsp;</b>
                                            <input type="text" class="form-control" id="searchText"  name="searchText" placeholder="{{ $placeholder }}" value="{{old('searchText',$texto)}}" aria-label="campo busqueda" aria-describedby="button-addon2">
                                            <button class="btn btn-outline-secondary bi bi-search btn-sm" type="submit" id="button-addon2"></button>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <b>Detalle</b><br/>
                                            <input type="checkbox" onchange="reloadOption(this.form)"  name="detail" @checked($detail)/>  
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <b  for="">Fecha Inicio</b><input class="form-control" type="date" value="{{old('start_date',$start_date)}}" name="start_date">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <b >Fecha Final</b><input class="form-control"  type="date" value="{{old('end_date',$end_date)}}" name="end_date">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        @if(!$detail)
                                            <div class="form-group table-sm">
                                                <b>Forma</b>
                                                <select id="payment_method" name="payment_method" class="form-control">
                                                    <option value=""></option>
                                                    @foreach($payment_methods as $method)
                                                        @if($method == $payment_method)
                                                            <option value="{{$method}}" selected>{{$method}}</option>
                                                        @else
                                                            <option value="{{$method}}">{{$method}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                      @endif
                                    </div>
                                    <div class="col-md-1">
                                        @if(!$detail)
                                            <div class="form-group">
                                                <b>Medio</b>
                                                <select id="form_payment" name="form_payment" class="form-control">
                                                    <option value="" @selected(old('form_payment', $form_payment) == "")></option>
                                                    <option value="contado" @selected(old('form_payment', $form_payment) == "contado")>contado</option>
                                                    <option value="credito" @selected(old('form_payment', $form_payment) == "credito")>credito</option>
                                                </select>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-1">
                                        <div class="input-group mb-6">
                                            <a href="{{route('cash_opening.create')}}" class="btn btn-warning bi bi-cash btn-sm" title="Apertura de Caja"></a>
                                            <!--a href="{{route('sale.create')}}" class="btn btn-success bi bi-plus" title="Nueva venta"></a-->
                                            <a href="#" onclick="validate_cash_opening()" class="btn btn-success bi bi-plus btn-sm" title="Nueva venta"></a>
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
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        {{$error}}
                    @endforeach
                </div>
            @endif
            <div class="card-body">
            </div>
            @if(!$detail)
            <div class="table-responsive">
                <table class="table table-hover mb-0 table-sm">
                    <thead>
                        <tr>
                            <th></th>
                            <th>POS</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>F.Pago</th>
                            <th>Medio</th>
                            <th>Responsable</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forEach($sales as $sale)
                        <tr>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" onclick="view_invoice({{$sale->id}})" data-bs-toggle="#"><i class="bi bi-receipt" title="imprimir factura"></i></button>
                                <a href="{{ route('sale.show', $sale->id) }}" title="Ver detalle" class="btn btn-warning btn-sm"  data-bs-toggle="#"><i class="bi bi-eye"></i></a>
                                @auth
                                    @if(Auth::user()->role=='admin')   
                                        <a  class="btn btn-warning btn-sm" title="Efectuar Devolución" href="{{route('sale.show',$sale->id)}}" ><i class="bi bi-arrow-return-left"></i></a>
                                    @endif
                                @endauth
                            </td>
                            <td>{{$sale->id}}</td>
                            <td>{{$sale->created_at}}</td>
                            <td>{{$sale->customer_name}}</td>
                            <td>${{number_format($sale->sale_total,0,",",".")}}</td>
                            <td>{{$sale->payment_form}}</td>
                            <td>{{$sale->method}}(${{number_format($sale->value,0,",",".")}})</td>
                            <td>{{$sale->user_name}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                    {{$sales->links('pagination::bootstrap-5')}}
            </div>
            @else

            <div class="table-responsive">
                <table class="table table-hover mb-0 table-sm">
                    <thead>
                        <tr>
                            <th></th>
                            <th>POS</th>
                            <th>Producto</th>
                            <th>Cant.</th>
                            <th>Precio</th>
                            <th>Desc</th>
                              <th>Total</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Responsable</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forEach($sales as $sale)
                        <tr>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" onclick="view_invoice({{$sale->id}})" data-bs-toggle="#"><i class="bi bi-receipt" title="imprimir factura"></i></button>
                                @auth
                                    @if(Auth::user()->role=='admin')   
                                        <a  class="btn btn-warning btn-sm" title="Efectuar Devolución" href="{{route('sale.show',$sale->id)}}" ><i class="bi bi-arrow-return-left"></i></a>
                                    @endif
                                @endauth
                                
                            </td>
                            <td>{{$sale->id}}</td>
                            <td><small>{{ $sale->name }}{{ $sale->concentration}} {{ $sale->presentation }}<b><small>({{$sale->code}})</small></b></small></td>
                            <td>{{$sale->quantity}}</td>
                            <td>${{number_format($sale->sale_price,0,",",".")}}</td>
                            <td>${{number_format($sale->discount,0,",",".")}}</td>
                            @php
                            $total_parcial = (floatval($sale->sale_price) - floatval($sale->discount))*(intval($sale->quantity));
                            @endphp
                            <td>${{number_format($total_parcial,0,",",".")}}</td>
                            <td>{{$sale->created_at}}</td>
                            <td>{{$sale->customer_name}}</td>
                            <td>{{$sale->user_name}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                    {{$sales->links('pagination::bootstrap-5')}}
            </div>
            @endif
           <table class="table table-hover mb-0 table-sm">
            <tr>
                <th>Total Venta</th>
                <td>
                    $ {{number_format($sum_total,2,',','.')}}
                </td>
                <th>Total Descuentos</th>
                <td>
                    $ {{number_format($total_discount,2,',','.')}}
                </td>
            </tr> 
           </table>
        </div>
    </section>
 @include('sale.sale.receipt')
@endsection

@push('scripts')
    <script>
        function view_invoice(sale_id){
                const body_details = document.querySelector("#details table tbody");
                const table_form_payment = document.querySelector("#table_method_payment tbody");
                body_details.innerHTML = "";
                table_form_payment.innerHTML = "";
                showSpinner();
                fetch("{{url('sale/sale/receipt')}}/" + encodeURIComponent(sale_id))
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        document.getElementById("sale_number").textContent = data.sale_id;  
                        generateInvoice(data.info_sale,data.detail_sale, data.form_payment);
                        const myModal = new bootstrap.Modal(document.getElementById('modal-receipt-invoice'));
                        myModal.show();  
                    }else {
                        Swal.fire({
                            title: 'Error',
                            text: 'No se pudo generar la factura, intentalo mas tarde',
                            icon: 'error',
                            buttons: true,
                            dangerMode: true,
                            timer: 3000
                        });
                    }
                })
                .catch(error => {
                   
                            Swal.fire({
                            title: 'Error',
                            text: error,
                            icon: 'error',
                            buttons: true,
                            dangerMode: true,
                            timer: 3000
                        });
                }).finally(()=>{
                     hideSpinner();
                });
        }

        function validate_cash_opening(){
                showSpinner();
                fetch("{{route('sale.cash_opening.validate_cash_opening',1)}}")
                .then(response => response.json())
                .then((data) => {
                    if(data.success) {
                        window.location.href = "{{route('sale.create')}}";
                    }else{
                        Swal.fire({
                            text: 'No has abierto caja aún.',
                            icon: 'warning',
                            timer: 3000
                        });
                    }
                })
                .catch(error => {
                            Swal.fire({
                            text: error,
                            icon: 'error',
                            timer: 3000
                        });
                }).finally(()=>{
                     hideSpinner();
                });
        }

        function reloadOption(form){
            document.getElementById('searchText').value = "";
            form.submit();
        }

    </script>
@endpush




