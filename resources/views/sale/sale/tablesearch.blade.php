<style>
    #list-products {
        font-size: 0.7rem;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }
    #list-products th, #list-products td {
        text-align: center;
    }
    #list-products th {
        background-color: #f8f9fa;
    }
    .page-link {
        font-size: 0.7rem;
        padding: 0.3rem 0.3rem;
    }
</style>

@if ($products->count() > 0)
    <table id="list-products" class="table table-sm table-striped table-bordered mb-2 table-hover">
        <thead class="table-light">
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Form.V</th>
                <th>Fecha.V</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td style="font-size: 0.7rem;">{{$product->code }}</td>
                    <td style="font-size: 0.7rem;"><b>{{$product->name }} {{ $product->concentration }} {{ $product->presentation }} {{ $product->laboratory}}</b></td>
                    <td style="font-size: 0.7rem;">{{$product->quantity}}</td>
                    <td style="font-size: 0.7rem;">${{number_format($product->sale_price,0,',','.')}}</td>
                    <td style="font-size: 0.7rem;">{{$product->form_sale}}</td>
                    <td style="font-size: 0.7rem;">{{$product->expiration_date}}</td>
                    <td style="font-size: 0.7rem;">
                        <button class="btn btn-info btn-sm" data-product='@json($product)' type="button"  onclick="add_quantity_Discount(this)"><i class="bi bi-cart"></i></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-center">
                        <div style="font-size: 0.7rem;" class="d-flex justify-content-center">
                            {!! $products->links() !!}
                        </div>
                </td>
            </tr>   
        </tfoot>
    </table>


@else
    <div class="text-danger">No se encontraron productos con ese nombre o código.</div>
@endif