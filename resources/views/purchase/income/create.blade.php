@extends('layouts.admin')

@section('title', 'Crear Ingreso')

@section('content')
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Nuevo Ingreso</h3>
            </div>
            <form id="incomeform" action="{{route('income.store')}}" method="POST" class="form">
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
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="voucher_id">Factura</label>
                                <select name="voucher_id" id="voucher_id" class="form-control">
                                    @foreach ($vouchers as $voucher)
                                    <option value="{{$voucher->id}}">Proveedor({{$voucher->supplier_name }}) Factura({{$voucher->voucher_number }}) Valor({{$voucher->total}})</option>   
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <br/>
                                <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#modal-view-"><i class="bi bi-eye"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row" style="display: none">
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="purchase_price">Precio Compra</label>
                                <input type="number" class="form-control" name="purchase_price" id="purchase_price" step="0.01" min="0" placeholder="Precio compra">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="sale_price">Precio Venta</label>
                                <input type="number" class="form-control" name="sale_price" id="sale_price" step="0.01" min="0" placeholder="Precio venta">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="sale_price">F.Vence</label>
                                <input type="date" class="form-control" name="expiration_date" id="expiration_date"  placeholder="Fecha de vencimiento">
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
                                <button type="button" id="btn_add" onclick="add()" class="btn btn-success me-1 mb-1">Agregar</button>
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
                                        <th>F. Venc.</th> 
                                    </tr>
                                </thead>
                                <tfoot>
                                    <th colspan="4">TOTALES</th>
                                    <th></th>
                                    <th id="total_purchase">$0</th>
                                    <th></th>
                                    <th></th>
                                    <th id="total_sale">$ 0</th>
                                    <th id="total_profit">$ 0</th>
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
            <!-- Aquí se mostrarán los errores -->
            <div id="errors" class="alert alert-danger" style="display: none;">
                <ul id="errorsList"></ul>
            </div>
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
                    });
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


    //ocultar el boton de guardar y mostrar los detalles de cada producto
    $('#save').hide();
    $('#product_id').change(showValues());

    //funcion para recalcular los totales
    var detalles = document.getElementById('detalles');
    detalles.addEventListener('input', function(e) {
        if (e.target.name === 'quantities[]'){
            let line = e.target.id.split('_')[1];
            let quantity = parseInt(e.target.value) || 0;
            let purchase = parseFloat(document.getElementById('purchase_' + line).value) || 0;
            let sale = parseFloat(document.getElementById('sale_' + line).value) || 0;
            updateSubtotales(line, quantity, purchase, sale);
        }

        if (e.target.name === 'purchase_prices[]'){
            let line = e.target.id.split('_')[1];
            let purchase = parseInt(e.target.value) || 0;
            let quantity = parseFloat(document.getElementById('quantity_' + line).value) || 0;
            let sale = parseFloat(document.getElementById('sale_' + line).value) || 0;
            updateSubtotales(line, quantity, purchase, sale);
        }

        if (e.target.name === 'sale_prices[]'){
            let line = e.target.id.split('_')[1];
            let sale = parseInt(e.target.value) || 0;
            let quantity = parseFloat(document.getElementById('quantity_' + line).value) || 0;
            let purchase = parseFloat(document.getElementById('purchase_' + line).value) || 0;
            updateSubtotales(line, quantity, purchase, sale);
        }
    });

    //enviar formulario incomeform tipo ajax con fetch a la ruta de store
    document.getElementById('incomeform').addEventListener('submit',function(e){
        e.preventDefault();
        let formData = new FormData(this);
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Error en la solicitud');
            }
        }).then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: data.message,
                    timer: 3000,
                }).then(() => {
                    window.location.href = "{{route('income.index')}}";
                });
            } else {
                let errors = data.errors || [];
                let errorList = document.getElementById('errorsList');
                errorList.innerHTML = '';
                errors.forEach(error => {
                    let li = document.createElement('li');
                    li.textContent = error;
                    errorList.appendChild(li);
                });
                document.getElementById('errors').style.display = 'block';
            }
        }).catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al procesar la solicitud.',
            });
        });

    });



});


function updateSubtotales($line,quantity,purchase,sale){
    let subtotal_purchase = quantity * purchase;
    let subtotal_sale = quantity * sale;
    let subtotal_profit = subtotal_sale - subtotal_purchase;
    document.getElementById('subtotalpurchase_' + $line).value = subtotal_purchase.toFixed(2);
    document.getElementById('subtotalsale_' + $line).value = subtotal_sale.toFixed(2);
    document.getElementById('subtotalprofit_' + $line).value = subtotal_profit.toFixed(2);
    recalcularTotales();
}

function showValues(){
    var dataArticle = document.getElementById('product_id').value.split('_');
    $('#quantity').val(dataArticle[1]);
    $('#unit').html(dataArticle[2]);
}

function add(){
    if(!validateExist()){
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
    var expiration_date = $('#expiration_date').val();
    if(product_id!="" && quantity!="" && quantity > 0 && purchase_price !="" && sale_price!="" && expiration_date!=""){
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
                <td><input class="form-control"  id="quantity_`+cont+`" type="number"  class="w-10" name="quantities[]" value="`+quantity+`"></td>
                <td><input class="form-control"  id="purchase_`+cont+`" type="number"   name="purchase_prices[]" value="`+purchase_price+`"></td>
                <td><input class="form-control" readonly id="subtotalpurchase_`+cont+`" type="number"  name="subtotal_purchases[]" value="`+subtotal_purchase[cont]+`"></td>
                <td><input class="form-control"  type="text"  name="forms_sale[]" value="`+forms_sale[cont]+`"></td>
                <td><input class="form-control"  id="sale_`+cont+`¿"  type="number"  name="sale_prices[]" value="`+sale_price+`"></td>
                <td><input class="form-control" readonly id="subtotalsale_`+cont+`" type="number" name="subtotal_sales[]" value="`+subtotal_sale[cont]+`"></td>
                <td><input class="form-control" readonly id="subtotalprofit_`+cont+`" type="number"  name="subtotal_profits[]" value="`+(subtotal_profit[cont]).toFixed(2)+`"></td>
                <td><input class="form-control"  id="expirationdate_`+cont+`" type="date"  name="expiration_dates[]" value="`+expiration_date+`"></td>
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
    }else{
        alert("El producto ya ha sido agregado");
    }
    
}

function validateExist(){
    let b = 0;
    var products =document.querySelectorAll('input[name="products[]"]');
    for (let index = 0; index < products.length; index++) {
        const input = products[index];
        if (input.value === document.getElementById('product_id').value.split('_')[0]) {
            b=1;
        }
    }
    if(b==1){;
        return true;
    }else{
        return false;
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
    total_purchase = total_purchase - subtotal_purchase[index];
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

    var subtotal_purchases = document.querySelectorAll('input[name="subtotal_purchases[]"]');
    var subtotal_sales = document.querySelectorAll('input[name="subtotal_sales[]"]');
    var subtotal_profits = document.querySelectorAll('input[name="subtotal_profits[]"]');
    subtotal_purchases.forEach(function(input) {
        total_purchase += parseFloat(input.value) || 0;
    });

    subtotal_profits.forEach(function(input) {
        total_profit += parseFloat(input.value) || 0;
    });

    subtotal_sales.forEach(function(input) {
        total_sale += parseFloat(input.value) || 0;
    });

    document.getElementById('total_purchase').innerHTML = formatCurrency.format(total_purchase.toFixed(2));
    document.getElementById('total_sale').innerHTML = formatCurrency.format(total_sale.toFixed(2));
    document.getElementById('total_profit').innerHTML = formatCurrency.format(total_profit.toFixed(2));

}

</script>
@endpush

@endsection

