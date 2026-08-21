@extends('layouts.admin')

@section('title', 'Mostrar Ingreso')

@section('content')
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Estado Factura</h3><br/>
            </div>
            <div class="card-body">
               <div class="row">
                    <form action="{{ route('purchase.voucher.status') }}" method="GET" class="row g-3 mb-4">
                        <div class="col-md-5">
                            <input type="text" name="text" class="form-control form-control-sm" value="{{ $text }}" placeholder="Buscar producto/code">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-primary btn-sm">Buscar</button>
                        </div>
                    </form>
                </div>
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
                                        @php
                                        $nameProduct = $vou->name." ".$vou->concentration." ".$vou->presentation ." ".$vou->laboratory;
                                        $objProduct = new \stdClass();
                                        $objProduct->product =  $nameProduct;
                                        $objProduct->income_detail_id = $vou->income_detail_id;             
                                        @endphp
                                        <td>{{$vou->name}} {{$vou->concentration}} {{$vou->presentation}}</td>
                                        <td>{{$vou->laboratory}}</td>
                                        <td><a href="#" onclick='historial(@json($objProduct))'>{{$vou->inicial}}</a></td>
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
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection

<script>
    function historial(obje){

        const formData = new FormData();
        formData.append("income_detail_id", obje.income_detail_id);
        fetch("{{ route('purchase.voucher.historical_income') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            show_result(data,obje);
        })
        .catch(error => {
            console.error(error);
        });
    }
    function show_result(data,ob){
            let html = `
            <h2>${ob.product}</h2>
            <h3>VENTAS</h3>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Descuento</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
        `;

        data.sales.forEach(item => {
            html += `
                <tr>
                    <td>${item.name}</td>
                    <td style="text-align:center;">${item.quantity}</td>
                    <td style="text-align:center;">${item.sale_price}</td>
                    <td style="text-align:center;">${item.discount}</td>
                    <td>${item.created_at}</td>
                </tr>
            `;
        });

        html += `
                </tbody>
            </table>
        `;

         html += `
            <h3>SALIDAS</h3>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Cantidad</th>
                        <th>Descripcion</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
        `;
        data.outs.forEach(item => {
            html += `
                <tr>
                    <td>${item.name}</td>
                    <td style="text-align:center;">${item.quantity_out}</td>
                    <td style="text-align:center;">${item.description}</td>
                    <td>${item.created_at}</td>
                </tr>
            `;
        });

        html += `
                </tbody>
            </table>
        `;

        Swal.fire({
            title: 'Histórico',
            html: html,
            width: '700px',
            confirmButtonText: 'Cerrar'
        });
    }
</script>