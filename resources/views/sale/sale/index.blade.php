@extends('layouts.admin')

@section('title', 'Index')

@section('content')
   
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">LISTADO DE VENTAS</h1>
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
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                        <div class="input-group mb-6">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                            <input type="text" class="form-control"  name="searchText" placeholder="Buscar Venta" value="{{old('searchText',$texto)}}" aria-label="campo busqueda" aria-describedby="button-addon2">
                                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                        <div class="input-group">
                                            <label  for="start_date">Fecha Inicio</label><input class="form-control" type="date" value="{{old('start_date',$start_date)}}" name="start_date">
                                            <label for="end_date">Fecha Final</label><input class="form-control"  type="date" value="{{old('end_date',$end_date)}}" name="end_date">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <div class="input-group mb-6">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-plus-circle-fill"></i></span>
                                            <a href="{{route('sale.create')}}" class="btn btn-success">Nuevo</a>

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
                            <th>POS</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>F.Pago</th>
                            <th>Responsable</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forEach($sales as $sale)
                        <tr>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" onclick="view_invoice({{$sale->id}})" data-bs-toggle="#"><i class="bi bi-eye"></i></button>
                                <a  class="btn btn-warning btn-sm" title="Efectuar Devolución" href="{{route('sale.show',$sale->id)}}" ><i class="bi bi-arrow-return-left"></i></a>
                            </td>
                            <td>{{$sale->id}}</td>
                            <td>{{$sale->created_at}}</td>
                            <td>{{$sale->customer_name}}</td>
                            <td>${{number_format($sale->sale_total,0,",",".")}}</td>
                            <td>{{$sale->payment_form}}</td>
                            <td>{{$sale->user_name}}</td>
                        </tr>
                        @endforeach
                    </tbody> 
                </table>
                    {{$sales->links('pagination::bootstrap-5')}}
            </div>
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

    </script>
@endpush




