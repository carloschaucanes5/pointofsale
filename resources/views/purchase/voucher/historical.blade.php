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
                    <h1 class="m-0">FACTURAS HISTORICAS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active"><a href="#">Facturas Historicas</a></li>
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
                            <form action="{{route('purchase.voucher.historical')}}" method="get">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group mb-6">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                            <input type="text" class="form-control" name="searchText" placeholder="Buscar Facturas" value="{{$searchText}}" aria-label="campo busqueda" aria-describedby="button-addon2">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="from" class="form-control" value="{{ $from }}">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="to" class="form-control" value="{{ $to }}">
                                    </div>
                                    <div class="col-md-2">
                                        <select name="status_payment" class="form-select" aria-label="Default select example" value="{{ $status_payment }}" >
                                            <option value="" selected>Estado</option>
                                            <option value="credito" @if($status_payment == 'credito') selected @endif>Credito</option>
                                            <option value="contado" @if($status_payment == 'contado') selected @endif>Contado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="method_payment" class="form-select" aria-label="Metodo" >
                                            <option value="" selected>Medio</option>
                                            @forEach($payment_methods as $met)
                                                <option value="{{$met}}" @if(isset($method_payment_selected) && $method_payment_selected == $met) selected @endif>{{$met}}</option>
                                            @endforEach
                                        </select>

                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Buscar</button>
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
                            <th>Factura</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>valor</th>
                            <th>Responsable</th>
                            <th>Caja</th>
                            <th>Monto</th>
                            <th>Medio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forEach($vouchers as $vou)
                        <tr>
                            <td>
                                <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#modal-view-{{$vou->id}}"><i class="bi bi-eye"></i></button>
                                @if($vou->payment_method =='credito')
                                    <button type="button" data-voucher='@json($vou)'' onclick = "pay_voucher(this)" class="btn btn-success btn-sm"><i class="bi bi-credit-card"></i></button>
                                @endif
                            </td>
                            <td>{{$vou->voucher_number}}</td>
                            <td>{{$vou->supplier_name}}</td>
                            <td>{{ \Carbon\Carbon::parse($vou->updated_at)->format('d/m/Y g:i A') }}</td>
                            <td>$ {{number_format($vou->total,2,',','.')}}</td>
                            <td>{{$vou->user_name}}</td>
                            <td>{{$vou->cash_name}}</td>
                            <td>$ {{number_format($vou->paid_amount,2,',','.')}}</td>
                            <td>{{$vou->payment_method}}</td>

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

@section('scripts')
<script>
    function pay_voucher(ele){

        //en un modal de Swal seleccionar el medio de pago vienen en esta variable $payment_methods
        //solo seleccionar el metodo de pago e internamente pagar el monto amoubnt
       var datos = ele.getAttribute('data-voucher');
       //console.log(datos);
       var voucher = JSON.parse(datos);
       var payment_methods = @json($payment_methods);
       //ya no van caja
         var html_select = '<div > <select id="method_payment" class="swal2-input form-control"><option value="" selected>Selecciona el medio de pago</option>';  
            for(var i=0; i<payment_methods.length; i++){
                if(payment_methods[i] != 'credito'){
                    html_select += '<option value="'+payment_methods[i]+'">'+payment_methods[i]+'</option>';
                }  
            }
        //aumentemos slect de caja menor
         html_select += '</select></div><div>';
         html_select += '<select id="cash_id" class="swal2-input form-control">';
         html_select += '<option value="" >Selecciona la Caja</option>';  
         for(var j=0; j< @json($cashes).length; j++){
             html_select += '<option value="'+@json($cashes)[j].id+'">'+@json($cashes)[j].name+'</option>';
         }
                
            html_select += '</select></div>';
       Swal.fire({
        title: 'Pagar Factura',
        html: html_select,
        showCancelButton: true,
        confirmButtonText: 'Pagar',
        cancelButtonText: 'Cancelar'
       }).then((result) => {
        if (result.isConfirmed) {
            var method_payment = document.getElementById('method_payment').value;
            if(method_payment == ''){
                Swal.fire('Error','Debe seleccionar un medio de pago','error');
                return;
            }
            var  cash_selected = document.getElementById('cash_id').value;
            if(cash_selected  == ''){
                Swal.fire('Error','Debe seleccionar una caja sobre la cual realizara el pago','error');
                return;
            }
            //enviar a la ruta purchase.voucher.pay
            //id payment_method paid_amount
            var url = '{{ route("purchase.voucher.pay") }}';

            //redireccionar con metodo post
            var form = document.createElement('form');  
            form.method = 'POST';
            form.action = url;
            var token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = '{{ csrf_token() }}';
            form.appendChild(token);
            var input_id = document.createElement('input');
            input_id.type = 'hidden';
            input_id.name = 'voucher_id';
            input_id.value = voucher.id;
            form.appendChild(input_id);
            var input_method = document.createElement('input');
            input_method.type = 'hidden';
            input_method.name = 'method_payment';
            input_method.value = method_payment;
            form.appendChild(input_method);
            var input_cash = document.createElement('input');
            input_cash.type = 'hidden';
            input_cash.name = 'cash_old';
            input_cash.value = voucher.cash_id
            form.appendChild(input_cash);
            var input_cash1 = document.createElement('input');
            input_cash1.type = 'hidden';
            input_cash1.name = 'cash_now';
            input_cash1.value = document.getElementById("cash_id").value;
            form.appendChild(input_cash1);
            var input_amount = document.createElement('input');
            input_amount.type = 'hidden';
            input_amount.name = 'amount';
            input_amount.value = voucher.paid_amount;
            form.appendChild(input_amount);

            var input_payment_voucher = document.createElement('input');
            input_payment_voucher.type = 'hidden';
            input_payment_voucher.name = 'payment_voucher_id';
            input_payment_voucher.value = voucher.payment_voucher_id;
            form.appendChild(input_payment_voucher);

            document.body.appendChild(form);
            form.submit();
        }
       });
    }
</script>

