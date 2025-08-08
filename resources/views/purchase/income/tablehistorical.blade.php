<style>
    #list-products {
        font-size: 0.6rem;
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
    <table id="list-products" class="table table-sm table-bordered mb-2">
        <thead class="table-light">
            <tr>
                <th>Código</th>
                <th>Nombre</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td style="font-size: 0.7rem;">{{ $product->code }}</td>
                    <td style="font-size: 0.7rem;">{{ $product->name }} {{ $product->concentration }} {{ $product->presentation }}</td>
                    <td style="font-size: 0.7rem;">
                        <a href="#" data-product='@json($product)'  onclick="view_information_historical(this)">Histórico</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-center">
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