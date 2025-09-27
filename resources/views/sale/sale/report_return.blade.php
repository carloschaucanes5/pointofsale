@extends('layouts.admin')

@section('title', 'Index')

@section('content')
   
<style>
    .table th, .table td {
        font-size: 0.8rem;
    }
    .cel-spent {
        background-color: rgb(253, 169, 112) !important;
    }
    .cel-income{
        background-color: rgb(130, 247, 130) !important;
    }
</style>

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">LISTADO DE DEVOLUCIONES</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active"><a href="#">Devoluciones</a></li>
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
                            <form action="{{route('sale.sale.report_return')}}" method="get">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mb-6">
                                            <label>&nbsp;</label>
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                            <input type="text" class="form-control"  name="searchText" placeholder="Buscar Venta" value="{{old('searchText',$texto)}}" aria-label="campo busqueda" aria-describedby="button-addon2">
                                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label  for="start_date">Fecha Inicio</label><input class="form-control" type="date" value="{{old('start_date',$start_date)}}" name="start_date">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="end_date">Fecha Final</label><input class="form-control"  type="date" value="{{old('end_date',$end_date)}}" name="end_date">
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
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>POS</th>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Descripción</th> 
                            <th>Responsable</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forEach($returns as $ret)
                        <tr>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" onclick="view_invoice({{$ret->sale_id}})" data-bs-toggle="#"><i class="bi bi-receipt" title="imprimir factura"></i></button>
                            </td>
                            <td>{{$ret->sale_id}}</td>
                            <td>{{$ret->created_at}}</td>
                            <td>{{$ret->product_name}} {{$ret->concentration}} {{$ret->presentation}} {{$ret->laboratory}}</td>
                            <td>{{$ret->quantity}}</td>
                            <td>${{number_format($ret->return_total,0,",",".")}}</td>
                            <td>{{$ret->description}}</td> 
                            <td>{{$ret->user_name}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                    {{$returns->links('pagination::bootstrap-5')}}
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




