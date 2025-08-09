@extends('layouts.admin')

@section('title', 'Crear Ingreso')

@section('content')
<style>
    table td, table th {
    padding: 0.2rem !important;
    font-size: 0.8rem;
}
</style>
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Nuevo Ingreso</h3>
            </div>
            <form id="incomeform"  class="form">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="voucher_id">Factura</label>
                                        <select name="voucher_id" id="voucher_id" class="form-control">
                                            @foreach ($vouchers as $voucher)
                                            <option value="{{$voucher->id}}">Proveedor({{$voucher->supplier_name }}) Factura({{$voucher->voucher_number }}) Valor({{$voucher->total}})</option>   
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="voucher_total" id="voucher_total" value="{{isset($vouchers[0]->total)?$vouchers[0]->total:0}}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-sm-12">
                                    <div class="form-group">
                                        <label for="product_id">Producto</label>
                                        <input type="text" id="product_search" class="form-control" placeholder="Buscar producto...">
                                        <input type="hidden" name="product_id" id="product_id">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <label for="btn_buscar"></label><br/>
                                    <input type="button" id="btn_search" onclick="fun_search_product()" value="buscar" class="btn btn-info"/>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <label for="btn_scan"></label><br/>
                                    <input type="button" id="btn_scan" onclick="scan_code_bar()" value="Escan" class="btn btn-warning"/>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div id="product_info"></div>
                        </div>
                    </div>
                <div id="row_add" style="display: none">
                    <div class="row">
                        <div class="col-md-3">
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
                                <label for="sale_price">F.Vence</label>
                                <input type="date" class="form-control" name="expiration_date" id="expiration_date"  placeholder="Fecha de vencimiento">
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="lote">Lote</label>
                                <input type="text" class="form-control" name="lote" id="lote" placeholder="lote">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="invima">Invima</label>
                                <input type="text" class="form-control" name="invima" id="invima" placeholder="invima">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="btn_add"></label><br/>
                                <button type="button" id="btn_add" onclick="add()" class="btn btn-success me-1 mb-1">Agregar</button>
                            </div>
                        </div>
                    </div>
                </div> 
                    <hr/>
                    <div class="row">
                        <div class="col-md-12  table-responsive text-sm">
                            <table class="table table-sm p-0 m-0 table table-hover mb-1 table-striped table-hover table-bordered align-middle" id="detalles">
                                <thead class="table-dark">
                                    <tr>
                                        <th></th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Costo/U</th>
                                        <th>Costo/T</th>
                                        <th>M/V</th>
                                        <th>Precio/U</th>
                                        <th>Precio/T</th>
                                        <th>Utilidad</th>
                                        <th>F. Venc.</th>
                                        <th>Lote</th>
                                        <th>Invima</th>  
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
                                    <th></th>
                                    <th></th>
                                </tfoot>
                                <tbody>
                                </tbody> 
                            </table>
                            <input type="hidden" id="total_purchase_hidden" value="0"/>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="card-footer">
                    <button type="button"  style="display: none" id="save" onclick="saveIncome()" class="btn btn-success me-1 mb-1">Guardar</button>
                    <button type="reset" class="btn btn-danger me-1 mb-1">Cancelar</button>
                </div>
            </form>
            <!-- Aquí se mostrarán los errores -->
            <div id="errors" class="alert alert-danger" style="display: none;">
                <ul id="errorsList"></ul>
            </div>
        </div>
    </div>
    @include('purchase.income.modal')
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

    function fun_search_product(){
        //buscar el producto por codigo o nombre
        let search = document.getElementById('product_search').value;   
        if (search.length > 0) {
            showSpinner();
            fetch("{{url('purchase/income/search_product_unit')}}/" + encodeURIComponent(search))
                .then(response => response.json())
                .then(data => {
                    let productInfo = document.getElementById('product_info');
                    if (!productInfo) {
                        productInfo = document.createElement('div');
                        productInfo.id = 'product_info';
                        productInfo.classList.add('mt-2');
                        document.getElementById('row_add').parentNode.appendChild(productInfo);
                    }
                    if (data.length > 0) {
                        let product = data[0];
                        document.getElementById('product_id').value = product.id+"_"+product.code+"_"+product.name+" "+product.concentration+" "+product.presentation;
                        productInfo.innerHTML =`
                        <div class="alert alert-success text-sm">
                            <h6>Información del Producto</h6>
                            <b style='font-size:0.9rem'>${product.name} ${product.concentration} ${product.presentation}</b><br>
                            <b style='font-size:0.9rem'>${product.code}</b><br>     
                            <a href="#" onclick="view_information_historical(${product.code})">Ver Historico Costos y Precios</a>
                        </div>
                            `;
                        document.getElementById('row_add').style.display = '';
                    } else {
                        productInfo.innerHTML = '<span class="text-danger">Producto no encontrado.</span>';
                        document.getElementById('product_id').value = '';
                    }
                })
                .catch(() => {

                }).finally(()=>{
                    hideSpinner();
                });
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('result').innerText = `Código escaneado: ${decodedText}`;
        // Puedes detener el escáner si quieres:
        const productSearch = document.getElementById('product_search');  
        productSearch.value = decodedText;  
        html5QrcodeScanner.clear();
    }

    const html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess);


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
                            <div class="alert alert-success text-sm">
                                <h6>Información del Producto</h6>
                                <b style='font-size:0.9rem'>${product.name} ${product.concentration} ${product.concentration}</b><br>    
                                <a href="#" onclick="view_information_historical(${product.code})">Ver Historico Costos y Precios</a>
                            </div>
                                `;
                            document.getElementById('row_add').style.display = '';
                        } else {
                            productInfo.innerHTML = '<span class="text-danger">Producto no encontrado.</span>';
                            document.getElementById('product_id').value = '';
                        }
                    })
                    .catch(() => {

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



 //codigo para cambiar el data-id del boton cuando cambie el voucher_id
    document.getElementById('voucher_id').addEventListener('change', function() {
        let voucherId = this.value.split('-')[0];
        document.getElementById('voucher_total').value = this.value.split('-')[1];
        let btnViewVoucher = document.querySelector('.btn-view-voucher');
        if (btnViewVoucher) {
            btnViewVoucher.setAttribute('data-id', voucherId);
        }
    });

});


//con el data-id del boton btn-view-voucher, mostrar el modal con la imagen del voucher
document.querySelector('.btn-view-voucher').addEventListener('click', function() {
    if(this.getAttribute('data-id') === ''){
        Swal.fire({icon: 'warning',title: 'Advertencia',text: 'Debe seleccionar una factura para ver su contenido.'});
        return;
    }
    let voucherId = this.getAttribute('data-id');
    document.getElementById('modal-body').innerHTML = "";
    showSpinner();
    fetch("{{url('purchase/income/view_voucher')}}/" + encodeURIComponent(voucherId))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let modalBody = document.getElementById('modal-body');
                let modalTitle = document.getElementById('modal-title');
                modalTitle.textContent = `Factura: ${data.voucher_number}`;
                modalBody.innerHTML = `<img src="${data.photo_url}" class="img-fluid" alt="Voucher">`;
                $('#modal-voucher').modal('show');
            } else {
                Swal.fire({icon: 'error',title: 'Error',text: data.message,});
            }
        })
        .catch(error => {
            Swal.fire({icon: 'error',title: 'Error',text: 'Ocurrió un error al cargar el voucher.',});
        }).finally(()=>{
            hideSpinner();  
        });


});

function scan_code_bar() {
    Swal.fire({
        title: 'Escanear Código de Barras',
        html: '<div id="reader" style="width: 300px; height: 300px;"></div><div id="result" class="mt-2"></div>',
        showConfirmButton: true,
        didOpen: () => {
            const html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", { fps: 10, qrbox: 250 });
            html5QrcodeScanner.render(onScanSuccess);
        }
    });
}
function view_information_historical(code){
    showSpinner();
    fetch("{{url('purchase/income/search_product_historical')}}/" + encodeURIComponent(code))
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                let table = `<div class="table-responsive">
                                <table class="table table-sm table-bordered table-striped table-hover ">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Costo</th>
                                        <th>Precio</th>
                                        <th>F.Venta</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>`;
                data.forEach(item => {
                    let item_t = JSON.stringify(item).replace(/"/g, '&quot;');
                    let price = item.sale_price ? formatCurrency.format(item.sale_price) : 'N/A';
                    table += `<tr>
                                <td>${item.created_at}</td>
                                <td>${item.purchase_price}</td>
                                <td>${price}</td>
                                <td>${item.form_sale}</td>

                                <td><button class="btn btn-warning btn-sm" onclick="selection('${item_t}')"><i class="bi bi-plus"></i></button></td>
                              </tr>`;
                });
                table += `</tbody></table></div>`;
                Swal.fire({
                    title: 'Histórico de Precios y Costos',
                    html: table,
                    showCloseButton: true,
                    focusConfirm: false,
                    width: '900px',
                });
            } else {
                Swal.fire({icon: 'info',title: 'Información',text: 'No se encontraron registros históricos.'});
            }
        })
        .catch(error => {
            Swal.fire({icon: 'error',title: 'Error',text: 'Ocurrió un error al cargar el histórico.',});
        }).finally(()=>{
            hideSpinner();
        });

}

function selection(item){
    let item1 = JSON.parse(item);
    document.getElementById('purchase_price').value = item1.purchase_price;
    document.getElementById('sale_price').value = item1.sale_price;
    document.getElementById('form_sale').value = item1.form_sale;
    document.getElementById('expiration_date').value = item1.expiration_date;
    document.getElementById('lote').value = item1.lote;
    document.getElementById('invima').value = item1.invima;
    //cerrar el modal
   Swal.close();

}

function parseAmount(value) {
    value = value.trim().replace(/[^\d.,-]/g, '');
    const hasComma = value.includes(',');
    const hasDot = value.includes('.');

    if (hasComma && hasDot) {
        if (value.indexOf('.') < value.indexOf(',')) {
            value = value.replace(/\./g, '').replace(',', '.');
        } else {
            value = value.replace(/,/g, '');
        }
    } else if (hasComma && !hasDot) {
        value = value.replace(',', '.');
    } else {
        value = value.replace(/,/g, '');
    }
    return parseFloat(value);
}

function saveIncome(){

            let form = document.getElementById('incomeform');
            let formData = new FormData(form);
            let voucher_id = document.getElementById('voucher_id').value;
            if (voucher_id === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe seleccionar una factura.',
                });
                return;
            }
            showSpinner();
            fetch("{{route('income.store')}}", {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideSpinner();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',    
                        title: 'Éxito',
                        text: data.message,
                    }).then(() => {
                        window.location.href = "{{route('purchase.startinventory')}}"; // Redirigir a la página de inicio de inventario
                    });
                } else {
                    let errorsList = document.getElementById('errorsList');
                    errorsList.innerHTML = '';
                    data.errors.forEach(error => {
                        let li = document.createElement('li');
                        li.textContent = error;
                        errorsList.appendChild(li);
                    });
                    document.getElementById('errors').style.display = 'block';
                }
            })
            .catch(error => {
                hideSpinner();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al guardar el ingreso.',
                });
            });
       
}



function showValues(){
    var dataArticle = document.getElementById('product_id').value.split('_');
    $('#quantity').val(dataArticle[1]);
    $('#unit').html(dataArticle[2]);
}

function add(){
    //validar si hay voucher seleccionado
    if(document.getElementById('voucher_id').value == ""){
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debe seleccionar una factura.',
        });
        return;
    }
    if(!validateExist()){
    var dataArticle = document.getElementById('product_id').value.split('_');
    var product_id = dataArticle[0];
    var code = dataArticle[1];
    var product_name = dataArticle[2];

    var quantity = document.getElementById('quantity').value;
    var purchase_price = document.getElementById('purchase_price').value;
    var sale_price = document.getElementById('sale_price').value;
    var form_sale = document.getElementById('form_sale').value;
    var profit = 0;
    var expiration_date = document.getElementById('expiration_date').value;
    var lote = document.getElementById('lote').value;
    var invima = document.getElementById('invima').value;
    //validar que la fecha de experacion no sea menor a la fecha actual asi viene el formato 2025-06-15
    if(expiration_date != "" && new Date(expiration_date) <= new Date()){
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'La fecha de expiración debe ser mayor a la fecha actual. Almenos dos días de diferencia.',
        });
        return;
    }
 
    if(product_id!="" && quantity!="" && quantity > 0 && purchase_price !="" && sale_price!="" && expiration_date!=""){
            let subtotal_purchase = quantity * purchase_price;
            let subtotal_sale = quantity * sale_price;
            let subtotal_profit = subtotal_sale - subtotal_purchase;
           var row = `
                <tr>
                    <td>
                        <input type="hidden" name="products[]" value="${product_id}">
                        <button type="button" class="btn btn-warning" onclick="eliminar(this)">x</button>
                    </td>
                    <td>${product_name}</td>
                    <td><input class="form-control" type="number" min="1" name="quantities[]" onchange="updateSubtotal(event)" value="${quantity}"></td>
                    <td><input class="form-control" type="hidden" min="1" name="purchase_prices[]" onchange="updateSubtotal(event)" value="${purchase_price}">${purchase_price}</td>
                    <td><input class="form-control" type="hidden" name="subtotal_purchases[]" value="${subtotal_purchase}">${subtotal_purchase}</td>
                    <td><input class="form-control" type="hidden"  name="forms_sale[]" value="${form_sale}">${form_sale}</td>
                    <td><input class="form-control" type="hidden" min="1" name="sale_prices[]" onchange="updateSubtotal(event)" value="${sale_price}">${sale_price}</td>
                    <td><input class="form-control" type="hidden" name="subtotal_sales[]" value="${subtotal_sale}">${subtotal_sale}</td>
                    <td><input class="form-control" type="hidden" name="subtotal_profits[]" value="${subtotal_profit}">${subtotal_profit}</td>
                    <td><input class="form-control" type="hidden" name="expiration_dates[]" value="${expiration_date}">${expiration_date}</td>
                    <td><input class="form-control" type="hidden" name="lotes[]" value="${lote}">${invima}</td>
                    <td><input class="form-control" type="hidden" name="invimas[]" value="${lote}">${invima}</td>
                </tr>
            `;

            limpiar();
            const detalles = document.querySelector("#detalles tbody");
            detalles.insertAdjacentHTML('afterbegin', row);

            const product_search =  document.getElementById('product_search');
            product_search.value = "";
            product_search.focus();
            document.getElementById('product_info').innerHTML = "";
            updateTotal();
        }
        else
        {
            alert("Error al ingresar el detalle del ingreso, revise los datos del articulo");
        }
    }else{
        alert("El producto ya ha sido agregado");
    }
    
}

function updateSubtotal(event){
    const row = event.target.closest('tr');
    const quantityInput = row.querySelector('input[name="quantities[]"]');
    const priceInput = row.querySelector('input[name="purchase_prices[]"]');
    const subtotalInput = row.querySelector('input[name="subtotal_purchases[]"]');
    const quantity = parseFloat(quantityInput.value) || 0;
    const price = parseFloat(priceInput.value) || 0;
    const subtotal = quantity * price;
    subtotalInput.value = subtotal.toFixed(2);

    const salePriceInput = row.querySelector('input[name="sale_prices[]"]');
    const subtotalSaleInput = row.querySelector('input[name="subtotal_sales[]"]');
    const subtotalProfitInput = row.querySelector('input[name="subtotal_profits[]"]');

    const salePrice = parseFloat(salePriceInput.value) || 0;
    const subtotalSale = quantity * salePrice;
    const profit = subtotalSale - subtotal;

    subtotalSaleInput.value = subtotalSale.toFixed(2);
    subtotalProfitInput.value = profit.toFixed(2);
    updateTotal();
}

function updateTotal(){
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

    document.getElementById('total_purchase_hidden').value = total_purchase; 
    document.getElementById('total_purchase').innerHTML = formatCurrency.format(total_purchase.toFixed(2));
    document.getElementById('total_sale').innerHTML = formatCurrency.format(total_sale.toFixed(2));
    document.getElementById('total_profit').innerHTML = formatCurrency.format(total_profit.toFixed(2));

    const voucherTotal = document.getElementById('voucher_total').value;
    const totalPurchase = document.getElementById('total_purchase_hidden').value;
    if (parseFloat(voucherTotal) > 0) {
        document.getElementById('save').style.display = "";
    } else {
        document.getElementById('save').style.display = "none";
    }
}

function validateExist(){
    let b = 0;
    var products =document.querySelectorAll('input[name="products[]"]');
    var forms = document.querySelectorAll('input[name="forms_sale[]"]');
    for (let index = 0; index < products.length; index++) {
        const input = products[index];
        if (input.value === document.getElementById('product_id').value.split('_')[0] && forms[index].value === document.getElementById('form_sale').value) {
            b=1;
        }
    }
    if(b==1){;
        return true;
    }else{
        return false;
    }
}




function limpiar(){
    document.getElementById('quantity').value = "";
    document.getElementById('purchase_price').value = "";
    document.getElementById('sale_price').value = "";
    document.getElementById('form_sale').value = "";
    document.getElementById('expiration_date').value = "";
    document.getElementById('lote').value = "";
    document.getElementById('invima').value = "";
}



function eliminar(item){
    const tr = item.closest("tr");
    tr.remove();
    updateTotal();
}


</script>
@endpush

@endsection

