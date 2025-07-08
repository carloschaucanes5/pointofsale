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
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group mb-6">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                            <input type="text" class="form-control" name="searchText" placeholder="Buscar Venta" value="{{$texto}}" aria-label="campo busqueda" aria-describedby="button-addon2">
                                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
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
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Impuesto</th>
                            <th>Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forEach($sales as $sale)
                        <tr>
                            <td>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="view_invoice({{$sale->id}})" data-bs-toggle="#"><i class="bi bi-eye"></i></button>
                            </td>
                            <td>{{$sale->created_at}}</td>
                            <td>{{$sale->name}}</td>
                            <td>{{$sale->tax}}</td>
                            <td>{{$sale->sale_total}}</td>
                            <td>{{$sale->status}}</td>
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

        function generateInvoice(info_sale,detail_sale,info_payment){
            const information_customer = document.getElementById("information_customer");
            information_customer.innerHTML = `<small class="text-muted mb-custom">Cliente: <b>${info_sale.customer_name}</b></small><br>
                                                <small class="text-muted mb-custom">Documento: <b>${info_sale.document_type}:${info_sale.document_number}</b></small><br>
                                                <small class="text-muted mb-custom">Dirección: <b>${info_sale.customer_address}</b></small><br>
                                                <small class="text-muted mb-custom">Télefono:<b>${info_sale.customer_phone}</b></small><br>`;
            document.getElementById("sale_number").textContent = info_sale.id;
            document.getElementById("date_sale").textContent = `Fecha: ${info_sale.updated_at}`;
            const body_details = document.querySelector("#details table tbody");
            const foot_details = document.querySelector("#details table tfoot")
            var discountTotals  = 0;
            var subtotals = 0;
            for(let i=0;i<detail_sale.length;i++){
                const detail = detail_sale[i];
                discountTotals += detail.discount; 
                subtotals += (detail.sale_price * detail.quantity); 
                const tr = document.createElement("tr");
                tr.innerHTML = `<td>${detail.quantity}</td><td>${detail.article} ${detail.concentration} ${detail.presentation}</td><td>${detail.discount}</td><td>${formatCurrency.format(parseFloat(detail.sale_price * detail.quantity).toFixed(0))}</td>`;
                body_details.appendChild(tr);
            }
            const trFoot = document.createElement("tr");
            trFoot.style.borderTop = "2px solid #000";
            trFoot.style.borderBottom = "2px solid #000";
            trFoot.innerHTML = `
                <td class="text-center">0</td>
                <td class="text-center">${info_sale.sale_total}</td>
                <td class="text-center">0</td>
                <td class="text-center">${info_sale.sale_total}</td>
            `; 
            foot_details.appendChild(trFoot);
            document.getElementById('receipt_subtotal').textContent = formatCurrency.format(subtotals);
            document.getElementById('receipt_discount').textContent = formatCurrency.format(discountTotals);
            document.getElementById('receipt_tax').textContent = 0;
            document.getElementById('receipt_total').textContent = formatCurrency.format(info_sale.sale_total);
            document.getElementById('receipt_change').textContent = formatCurrency.format(info_sale.change);

            const table_form_payment = document.querySelector("#table_form_payment tbody");
            var received = 0;
            info_payment.forEach(ele=>{
                    const tr_pay = document.createElement("tr");
                    tr_pay.innerHTML = `
                    <td>${ele.method}</td>
                    <td>${ele.value}</td>
                `;
                received=received + ele.value;
                table_form_payment.appendChild(tr_pay);
            });
            document.getElementById('receipt_received').textContent = formatCurrency.format(received);
            document.getElementById("employee").textContent = info_sale.user_name;
        }
    </script>
@endpush




