@extends('layouts.admin')

@section('title', 'Recepciones Facturas')

@section('content')
<div class="container">
    <h3 class="mb-4">Medicamentos Próximos a Vencer</h3>

<form action="{{ route('report.income.expiredproducts') }}" method="GET" class="row g-3 mb-4">
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
    @if ($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif
    <table class="table table-bordered table-hover table-sm table-striped">
        <thead class="table-dark">
            <tr>
                <th>F.Ven</th>
                <th>Cod.</th>
                <th>Producto</th>
                <th>Laboratorio</th>
                <th>Cantidad</th>
                <th>P. Compra</th>
                <th>P. Venta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $pro)
            <tr>
                <td>{{$pro->expiration_date}}</td>
                <td>{{$pro->code}}</td>
                <td>{{$pro->name}} {{$pro->concentration}} {{$pro->presentation}}</td>
                <td>{{$pro->laboratory}}</td>
                <td>{{$pro->quantity}}</td>
                <td>{{number_format($pro->purchase_price,2,',','.')}}</td>
                <td><a class="link-primary text-info" onclick="update_price({{ $pro->id }})" >{{number_format($pro->sale_price,0,',','.')}}</a></td>
            </tr>
            @endforeach
            
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">
                    {{$products->links()}}
                <td>
            <tr>
        </tfoot>
    </table>

</div>
@endsection

<script>
    function update_price(id){
        let new_price = prompt("Ingrese el nuevo precio de venta:");
        let url = "{{ url('purchase/income/update_sale_price') }}/"+id+"/"+new_price;
        //con fetch enviar la peticion get
        fetch(url)
        .then(response => response.json()) //convertir la respuesta a json
        .then(data => {
            if(data.success){
                alert("Precio de venta actualizado correctamente");
                location.reload();
            }else{
                alert("Error al actualizar el precio de venta");
            }
        });  
    }
</script>
