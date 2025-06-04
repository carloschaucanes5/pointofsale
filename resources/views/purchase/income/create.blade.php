@extends('layouts.admin')

@section('title', 'Crear Ingreso')

@section('content')
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Nuevo Ingreso</h3>
            </div>
            <form action="{{route('income.store')}}" method="POST" class="form">
                @csrf
                <div class="card-body">
                    <div style="display: none">
                        <div class="form-group">
                            <label for="voucher_type">Tipo de comprobante</label>
                            <select name="voucher_type" id="voucher_type" class="form-control">
                                <option value="RFC">RFC</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="supplier_id">Proveedor</label>
                                        <select name="supplier_id" id="supplier_id" class="form-control">
                                            @foreach ($persons as $per)
                                            <option value="{{$per->id}}">{{$per->name}}</option>   
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="voucher_number">Número de comprobante</label>
                                        <input type="text" class="form-control" name="voucher_number" id="voucher_number" placeholder="Número comprobante">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="product_id">Producto</label>
                                        <input type="text" id="product_search" class="form-control" placeholder="Buscar producto...">
                                        <input type="hidden" name="product_id" id="product_id">
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div id="product_info"></div>
                        </div>
                    </div>
                    <div class="row" id="row_add" style="display: none">
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="quantity">Cantidad</label>
                                <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Cantidad">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="purchase_price">Precio Compra</label>
                                <input type="number" class="form-control" name="purchase_price" id="purchase_price" step="0.01" min="0" placeholder="Precio compra">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sale_price">Precio Venta</label>
                                <input type="number" class="form-control" name="sale_price" id="sale_price" step="0.01" min="0" placeholder="Precio venta">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="form_sale">Forma/V</label>
                                <select name="form_sale" id="form_sale" class="form-control">
                                    @foreach ($forms as $for)
                                    <option value="{{$for->description}}">{{$for->description}}</option>   
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="btn_add"></label><br/>
                                <button type="button" id="btn_add" class="btn btn-success me-1 mb-1">Agregar</button>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-hover mb-1 table-striped table-hover table-bordered align-middle" id="detalles">
                                <thead class="table-dark">
                                    <tr>
                                        <th></th>
                                        <th>C. Barras</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Costo/U</th>
                                        <th>Costo/T</th>
                                        <th>M/V</th>
                                        <th>Precio/U</th>
                                        <th>Precio/T</th>
                                        <th>Utilidad</th> 
                                    </tr>
                                </thead>
                                <tfoot>
                                    <th colspan="4">TOTALES</th>
                                    <th></th>
                                    <th id="total_purchase">$ 0.00</th>
                                    <th></th>
                                    <th></th>
                                    <th id="total_sale">$ 0.00</th>
                                    <th id="total_profit">$ 0.00</th>
                                </tfoot>
                                <tbody>
                                </tbody> 
                            </table>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="card-footer">
                    <button type="submit" id="save" class="btn btn-success me-1 mb-1">Guardar</button>
                    <button type="reset" class="btn btn-danger me-1 mb-1">Cancelar</button>
                </div>
            </form>
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

@push("scripts")
<script>
    //inicializacion de variables
    var cont = 0; 
    var total_sale = 0;
    var total_purchase = 0;
    var total_profit = 0;

    var subtotal_sale = [];
    var subtotal_purchase = [];
    var subtotal_profit = [];
    var forms_sale = [];
    document.addEventListener('DOMContentLoaded', function() {
        //evento buscar producto
        document.getElementById('product_search').focus();
        document.getElementById('product_search').addEventListener('keypress', function(e) {
        if (e.which === 13 || e.keyCode === 13) {
            e.preventDefault();
            let search = this.value;
            if (search.length > 0) {
                showSpinner();
                fetch("{{url('purchase/income/search_product')}}/" + encodeURIComponent(search))
                    .then(response => response.json())
                    .then(data => {
                        let productInfo = document.getElementById('product_info');
                        if (!productInfo) {
                            productInfo = document.createElement('div');
                            productInfo.id = 'product_info';
                            productInfo.classList.add('mt-2');
                            this.parentNode.parentNode.parentNode.appendChild(productInfo);
                        }
                        if (data.length > 0) {
                            let product = data[0];
                            document.getElementById('product_id').value = product.id+"_"+product.code+"_"+product.name+" "+product.concentration+" "+product.presentation;
                            productInfo.innerHTML =`
                            <div class="alert alert-info">
                                <h5>Información del Producto</h5>
                                <b>Código:</b> ${product.code} <b>Stock:</b> ${product.stock}<br/>
                                <b>Producto:</b> ${product.name} ${product.concentration} ${product.concentration}
                                
                            </div>
                                `;
                            document.getElementById('row_add').style.display = '';
                        } else {
                            productInfo.innerHTML = '<span class="text-danger">Producto no encontrado.</span>';
                            document.getElementById('product_id').value = '';
                        }
                    })
                    .catch(() => {
                        let productInfo = document.getElementById('product_info');
                        if (!productInfo) {
                            productInfo = document.createElement('div');
                            productInfo.id = 'product_info';
                            productInfo.classList.add('mt-2');
                            this.parentNode.parentNode.parentNode.appendChild(productInfo);
                        }
                        productInfo.innerHTML = '<span class="text-danger">Error en la búsqueda.</span>';
                        document.getElementById('product_id').value = '';
                    }).finally(()=>{
                        hideSpinner();
                    }) ;
            }
        }
    });
    //evento para calcular el precio sugerido
   document.getElementById('purchase_price').addEventListener('blur', function() {
        let purchasePrice = parseFloat(this.value);
        if (isNaN(purchasePrice) || purchasePrice < 0) {
            this.value = '';
            alert('El precio de compra debe ser un número positivo.');
        }else{
            document.getElementById('sale_price').value = (purchasePrice / 0.70).toFixed(2);
        }
    });


    //evento para adicinar un nuevo item a la compra
    document.getElementById('btn_add').addEventListener('click',function(){
        add();
    });



    //ocultar el boton de guardar y mostrar los detalles de cada producto
    $('#save').hide();
    $('#product_id').change(showValues());

    //funcion para recalcular los totales


});





function showValues(){
    var dataArticle = document.getElementById('product_id').value.split('_');
    $('#quantity').val(dataArticle[1]);
    $('#unit').html(dataArticle[2]);
}

function add(){
    var dataArticle = document.getElementById('product_id').value.split('_');
    var product_id = dataArticle[0];
    var code = dataArticle[1];
    var product_name = dataArticle[2];
    var product = $('#product_id option:selected').text();
    var quantity = $('#quantity').val();
    var purchase_price = $('#purchase_price').val();
    var sale_price = $('#sale_price').val();
    var form_sale = $('#form_sale').val();
    var profit = 0;
    if(product_id!="" && quantity!="" && quantity > 0 && purchase_price !="" && sale_price!=""){
        subtotal_purchase[cont] = quantity * purchase_price;
        subtotal_sale[cont] = quantity * sale_price;
        subtotal_profit[cont] = subtotal_sale[cont] - subtotal_purchase[cont];
        forms_sale[cont] = form_sale;

        total_purchase = total_purchase + subtotal_purchase[cont];
        total_sale = total_sale + subtotal_sale[cont];
        total_profit = total_profit + subtotal_profit[cont];
        var row = `
        <tr class="selected" id="fila`+cont+`">
            <td>
                <button type="button" class="btn btn-warning" onclick="eliminar(`+cont+`)">x</button>
            </td>
            <input type="hidden" name="products[]" value="`+product_id+`">`+product_id+`
            <td>`+code+`</td>
            <td>`+product_name+`</td>
            <td><input class="form-control" onchange="actualizarTotales(this)" type="number" class="w-10" name="quantities[]" value="`+quantity+`"></td>
            <td><input class="form-control" onchange="actualizarTotales(this)" type="number"  name="purchase_prices[]" value="`+purchase_price+`"></td>
            <td><input class="form-control" onchange="actualizarTotales(this)" type="number"  name="subtotal_purchases[]" value="`+subtotal_purchase[cont]+`"></td>
            <td><input class="form-control" onchange="actualizarTotales(this)" type="text" readonly name="forms_sale[]" value="`+forms_sale[cont]+`"></td>
            <td><input class="form-control" onchange="actualizarTotales(this)" type="number"  name="sale_prices[]" value="`+sale_price+`"></td>
            <td><input class="form-control" onchange="actualizarTotales(this)" type="number"  name="subtotal_sales[]" value="`+subtotal_sale[cont]+`"></td>
            <td><input class="form-control" onchange="actualizarTotales(this)" type="number"  name="subtotal_profits[]" value="`+subtotal_profit[cont]+`"></td>
        </tr>
        `;
        cont++;
        limpiar();
        $('#total_purchase').html(formatCurrency.format(total_purchase.toFixed(2)));
        $('#total_sale').html(formatCurrency.format(total_sale));
        $('#total_profit').html(formatCurrency.format(total_profit.toFixed(2)));
        evaluate();
        $('#detalles').append(row);
        // Agrega eventos para recalcular totales al cambiar valores en la fila recién agregada
        $('#fila'+(cont-1)).find('input[name="quantities[]"], input[name="purchase_prices[]"], input[name="sale_prices[]"]').on('input', function() {
            recalcularTotales();
        });

        $('#product_search').val("");
        $('#product_search').focus();
        $('#product_info').html('');
        document.getElementById('row_add').style.display = 'none';
    }
    else
    {
        alert("Error al ingresar el detalle del ingreso, revise los datos del articulo");
    }
    
}

function actualizarTotales(ele){
    let row = ele.closest('tr');
    let quantity = parseFloat(row.querySelector('input[name="quantities[]"]').value) || 0;
    let purchase_price = parseFloat(row.querySelector('input[name="purchase_prices[]"]').value) || 0;
    let sale_price = parseFloat(row.querySelector('input[name="sale_prices[]"]').value) || 0;

    let subtotal_purchase = quantity * purchase_price;
    let subtotal_sale = quantity * sale_price;
    let subtotal_profit = subtotal_sale - subtotal_purchase;

    row.querySelector('input[name="subtotal_purchases[]"]').value = subtotal_purchase.toFixed(2);
    row.querySelector('input[name="subtotal_sales[]"]').value = subtotal_sale.toFixed(2);
    row.querySelector('input[name="subtotal_profits[]"]').value = subtotal_profit.toFixed(2);

    recalcularTotales();
}



function limpiar(){
    $('#quantity').val("");
    $('#purchase_price').val("");
    $('#sale_price').val("");
}

function evaluate(){
    if(total_purchase > 0){
        $('#save').show();
    }
    else
    {
        $('#save').hide();
    }
}

function eliminar(index){
    total_purchase = total_purchase -subtotal_purchase[index];
    $('#total_purchase').html('$ '+ total_purchase.toFixed(2));

    total_sale = total_sale -subtotal_sale[index];
    $('#total_sale').html('$ '+ total_sale);

    total_profit = total_profit -subtotal_profit[index];
    $('#total_profit').html('$ '+ total_profit);

    $('#fila'+index).remove();
    evaluate();
    recalcularTotales();
}

function recalcularTotales() {
    let total_purchase = 0;
    let total_sale = 0;
    let total_profit = 0;

    document.querySelectorAll('#detalles tbody tr').forEach(function(row) {
        let quantity = parseFloat(row.querySelector('input[name="quantities[]"]').value) || 0;
        let purchase_price = parseFloat(row.querySelector('input[name="purchase_prices[]"]').value) || 0;
        let sale_price = parseFloat(row.querySelector('input[name="sale_prices[]"]').value) || 0;
        console.log(quantity, purchase_price, sale_price);
        let subtotal_purchase = quantity * purchase_price;
        let subtotal_sale = quantity * sale_price;
        let subtotal_profit = subtotal_sale - subtotal_purchase;

        row.querySelector('input[name="subtotal_purchases[]"]').value = subtotal_purchase.toFixed(2);
        row.querySelector('input[name="subtotal_sales[]"]').value = subtotal_sale.toFixed(2);
        row.querySelector('input[name="subtotal_profits[]"]').value = subtotal_profit.toFixed(2);

        total_purchase += subtotal_purchase;
        total_sale += subtotal_sale;
        total_profit += subtotal_profit;
    });

    document.getElementById('total_purchase').innerHTML = formatCurrency.format(total_purchase.toFixed(2));
    document.getElementById('total_sale').innerHTML = formatCurrency.format(total_sale.toFixed(2));
    document.getElementById('total_profit').innerHTML = formatCurrency.format(total_profit.toFixed(2));

}

</script>
@endpush

@endsection

