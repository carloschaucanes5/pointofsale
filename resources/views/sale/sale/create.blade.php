@extends('layouts.admin')

@section('title', 'Crear Venta')
@include('sale.sale.quantity')
@section('content')
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header py-1 px-2">
                <h5 class="card-title m-0">Nueva Venta</h5>
            </div>
            <form  method="POST" class="form" id="form-sale">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="product_search"><b>Producto</b></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" id="product_search"  placeholder="Introduce el codigo de barras o el nombre del producto"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 table-responsive">
                                    <br/>
                                    <table class="table table-hover mb-1 table-sm table-striped table-hover table-bordered align-middle" id="incomes_detail">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>CB</th>
                                                <th>Producto</th>
                                                <th>Stock</th>
                                                <th>Precio/U</th>
                                                <th>M/V</th>
                                                <th>F.V</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody> 
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 table-responsive">
                                    <p style="text-align:center"><b>Carrito de compras</b></p>
                                    <table class="table table-hover mb-1 table-sm table-striped table-hover table-bordered align-middle" id="detalles">
                                        <thead class="table-warning" >
                                            <tr>
                                                <th>CB</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio Venta</th>
                                                <th>Descuento</th>
                                                <th>Subtotal</th>
                                                <th>F/V</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <th>TOTAL</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th><h4 id="total">$ 0.00</h4><input type="hidden"  name="sale_total" id="sale_total"/></th>
                                            <th></th>
                                        </tfoot>

                                        <tbody>
                                        </tbody> 
                                    </table>
                                    <a href="{{ route('sale.cash_close') }}" class="btn btn-primary">Cerrar Caja</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="customer_id"><b>Cliente</b></label>
                                <select name="customer_id" id="customer_id" class="form-control selectpicker" data-live-search="true">
                                    @foreach ($persons as $per)
                                    <option value="{{$per->id}}-{{$per->name}}-{{$per->document_number}}-{{$per->address}}-{{$per->phone}}" data-tokens="{{$per->id}}">{{$per->name}}</option>   
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <hr/>
                                <label for="payment_form"><b>Forma de Pago</b></label>
                                <select name="payment_form" id="payment_form" class="form-control">
                                    @foreach($payment_forms as $form)
                                        <option value="{{$form}}">{{$form}}</option>
                                    @endforeach
                                </select>                
                            </div>
                            <div class="form-group">
                                <hr/>
                                <label for="payment_method"><b>Medio de Pago</b></label>
                                <select name="payment_method" id="payment_method" class="form-control">
                                    @foreach($payment_methods as $method)
                                        <option value="{{$method}}">{{$method}}</option>
                                    @endforeach
                                </select>                
                            </div>
                            <div class="row form-group">
                                <div class="col-md-9">
                                    <input type="number" class="form-control" value="0" min="0" id="payment_value" placeholder="Ingrese valor" />
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-primary" onclick="add_payment()"><i class="bi bi-plus-circle"></i></button>
                                </div>
                            </div>
                            <hr/>
                            <div class="row form-group" >
                                    <table id="table_payments" class=" table table-hover mb-1 table-sm table-striped table-hover table-bordered align-middle">
                                        <colgroup>
                                                <col>
                                                <col style="display: none">
                                                <col>
                                        </colgroup>
                                        <thead>
                                            <th>Medio</th><th>Valor</th><th></th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <table id="table_totals" class=" table table-hover mb-1 table-sm">
                                        <tbody>
                                            <tr>    
                                                <td><h4 class="text-primary" id="totalPaymentTitle">Total</h4></td><td><h4><span class="text-success" id="totalPayment">0</span></h4></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="hidden" id="totalChangeHidden" name="totalChangeHidden" value="0" />
                                                    <h4 class="text-primary" id="totalChangeTitle">Cambio</h4></td><td><h4><span class="text-success" id="totalChange">0</span></h4>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    <div style="display: flex;flex-direction: row;justify-content: center">
                                                        <button type="button" onclick="toinvoice()" id="invoice"   class="btn btn-success me-1 mb-1">FACTURAR</button>
                                                        <!--button type="button" id="invoice" data-bs-toggle="modal" data-bs-target="#modal-receipt-invoice" class="btn btn-success me-1 mb-1">FACTURAR</button-->
                                                    </div>
                                                </td>
                                                <td> 
                                                    <div style="display: flex;flex-direction: row;justify-content: center"> 
                                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                        <button type="reset" onclick="window.location.reload()" class="btn btn-danger me-1 mb-1">Cancelar</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                        </div>                 
                    </div>
                </div>
               

               
               
            </form>
             @include('sale.sale.receipt')

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
        document.addEventListener('DOMContentLoaded',function(){

            //focalizar el input de busqueda
            document.getElementById("product_search").focus();
            document.getElementById("invoice").style.display = "none";
            //llamar tipo ajax hacia el controlador para la busqueda de entradas o inventario
            document.getElementById("product_search").addEventListener("keyup",function(e){
                e.preventDefault();
                //cuando sea enter y este enfocado en el input de busqueda
                if(e.key == 'Enter' && this.value.trim() != ""){
                    showSpinner();
                    const codeName = this.value;
                   fetch("{{url('sale/sale/search_product')}}/" + encodeURIComponent(codeName))
                    .then(response => response.json())
                    .then(data => {
                        // Limpiar la tabla antes de agregar nuevos resultados
                        const tbody = document.querySelector("#incomes_detail tbody");
                        tbody.innerHTML = "";
                        // Verifica si hay resultados
                        if (data.incomes_detail && data.incomes_detail.data.length > 0) {
                            data.incomes_detail.data.forEach(item => {
                                const tr = document.createElement("tr");
                                const itemSelected = `'${item.id},${item.code},${item.name} ${item.concentration} ${item.presentation},${item.sale_price},${item.form_sale},${item.quantity}'`; 
                                tr.innerHTML = `
                                    <td>${item.code}</td>
                                    <td>${item.name} ${item.concentration} ${item.presentation}</td>
                                    <td>${item.quantity}</td>
                                    <td>${formatCurrency.format(parseFloat(item.sale_price).toFixed(0))}</td>
                                    <td>${item.form_sale}</td>
                                    <td>${item.expiration_date}</td>
                                    <td><button class="btn btn-warning" type="button" onclick="add_quantity_Discount(${itemSelected})"> <span class="bi bi-cart"></span></button></td>
                                `;
                                tbody.appendChild(tr); 
                                });
                                //enfoca en el primer boton de la tabla income:details
                                const firstButton = tbody.querySelector("button");
                                if (firstButton) {
                                    if(data.incomes_detail.data.length == 1){
                                        const detallesTr = document.querySelectorAll("#detalles tbody tr input[name='income_detail_id[]']");
                                        let b=0;
                                        for(let i = 0;i<detallesTr.length;i++){
                                            if(detallesTr[i].value == data.incomes_detail.data[0].id){
                                                b=1;
                                            }
                                        }
                                        if(b==0){
                                            firstButton.onclick();
                                        }
                                        else
                                        {
                                            //firstButton.focus();
                                        }     
                                    }else{
                                        firstButton.focus();
                                    }
                                    
                                }
                                
                        }else{
                            // Si no hay resultados, muestra un mensaje
                            const tr = document.createElement("tr");
                            tr.innerHTML = `<td colspan="5" class="text-center">No se encontraron productos</td>`;
                            tbody.appendChild(tr);
                        }
                        //create_pagination(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    }).finally(()=>{
                        hideSpinner();
                        
                    });
                }else if(e.key == 'Enter' && this.value.trim() == ""){
                    //enfocar en facturar cuando haya un valor mayor aque cero en el total
                    e.preventDefault();
                    if(parseFloat(document.getElementById("sale_total").value) > 0){
                        document.getElementById("invoice").focus();
                    }
                    

                }
            })  

        


            //desplazamiento con las flechas del teclado sobre cada boton de la tabla incomes_detail pasar enfocando boton por boton
            const tbody = document.querySelector("#incomes_detail tbody");
            tbody.addEventListener("keydown", function(e) {
                if (e.key === "ArrowDown") {
                    e.preventDefault();
                    const currentButton = document.activeElement;
                    const nextButton = currentButton.closest("tr").nextElementSibling?.querySelector("button");
                    if (nextButton) {
                        nextButton.focus();
                    }
                } else if (e.key === "ArrowUp") {
                    e.preventDefault();
                    const currentButton = document.activeElement;
                    const previousButton = currentButton.closest("tr").previousElementSibling?.querySelector("button");
                    if (previousButton) {
                        previousButton.focus();
                    }
                }   
            });


            //evitar que se vaya en submit
            document.querySelector('form').addEventListener('submit', function(e) {
                e.preventDefault(); // Cancela cualquier envío
            });


            $('#modal-receipt-invoice').on('hidden.bs.modal', function (e) {
                window.location.reload(); // Recargar la página al cerrar el modal

            });
        });
    

        //una funcion que me permita 

        //function para crear paginacion
        function create_pagination(data){
                // Paginación
                const paginationContainerId = "incomes_detail_pagination";
                let paginationContainer = document.getElementById(paginationContainerId);
                if (!paginationContainer) {
                    paginationContainer = document.createElement("div");
                    paginationContainer.id = paginationContainerId;
                    document.querySelector("#incomes_detail").after(paginationContainer);
                }
                paginationContainer.innerHTML = "";

                if (data.incomes_detail && data.incomes_detail.last_page > 1) {
                    let paginationHtml = `<nav><ul class="pagination justify-content-center">`;
                    for (let page = 1; page <= data.incomes_detail.last_page; page++) {
                        paginationHtml += `
                            <li class="page-item${page === data.incomes_detail.current_page ? ' active' : ''}">
                                <a href="#" class="page-link btn-sm py-0 px-1 fs-6" data-page="${page}">${page}</a>
                            </li>
                        `;
                    }
                    paginationHtml += `</ul></nav>`;
                    paginationContainer.innerHTML = paginationHtml;

                    // Evento click para paginación
                    paginationContainer.querySelectorAll(".page-link").forEach(link => {
                        link.addEventListener("click", function(e) {
                            e.preventDefault();
                            const page = this.getAttribute("data-page");
                            showSpinner();
                            fetch("{{url('sale/sale/search_product')}}/" + encodeURIComponent(document.getElementById("product_search").value) + "?page=" + page)
                                .then(response => response.json())
                                .then(data => {
                                    // Recursivamente vuelve a ejecutar este bloque para actualizar la tabla y paginación
                                    // Puedes extraer este bloque a una función para evitar duplicación si lo deseas
                                    // --- INICIO BLOQUE RECURSIVO ---
                                    const tbody = document.querySelector("#incomes_detail tbody");
                                    tbody.innerHTML = "";
                                    const itemSelected = `'${item.id},${item.code},${item.name} ${item.concentration} ${item.presentation},${item.sale_price},${item.form_sale},${item.quantity}'`;
                                    if (data.incomes_detail && data.incomes_detail.data.length > 0) {
                                        //si solo hay un resultado de la consulta de producto agregar directamente al carrito de compras
                                        data.incomes_detail.data.forEach(item => {
                                            const tr = document.createElement("tr");
                                            tr.innerHTML = `
                                                <td>${item.code}</td>
                                                <td>${item.name}</td>
                                                <td>${formatCurrency.format(parseFloat(item.sale_price).toFixed(0))}</td>
                                                <td>${item.form_sale}</td>
                                                <td>${item.expiration_date}</td>
                                                <td><button class="btn btn-warning" type="button" onclick="add_quantity_Discount(${itemSelected})"> <span class="bi bi-cart"></span></button></td>
                                            `;
                                            tbody.appendChild(tr);
                                        });
                                        if(data.incomes_detail.data.length == 1 ){
                                           const  trFirst = document.querySelector("#incomes_detail tbody tr")[0];
                                            trFirst.querySelector("button").onclick();
                                        }

                                    }else{
                                        const tr = document.createElement("tr");
                                        tr.innerHTML = `<td colspan="5" class="text-center">No se encontraron productos</td>`;
                                        tbody.appendChild(tr);
                                    }

                                }).catch(error => {
                                    console.error('Error:', error);
                                }).finally(()=>{
                                    hideSpinner();
                                });
                        });
                    });
                }
        }


        //
        //function para adicionar un item al carrito de compras
        function add_item_selected(){
            const id = document.getElementById("productId").value;
            const code = document.getElementById("productCode").value;
            const name = document.getElementById("productName").value;
            const stock = document.getElementById("productStock").value;
            const sale_price = document.getElementById("productSalePrice").value;
            const form_sale = document.getElementById("productFormSale").value;
            const quantityItem = document.getElementById("quantityItem").value;
            const totalDiscount = document.getElementById("totalDiscount").value;

            const subtotal = (parseFloat(sale_price) * parseFloat(quantityItem)) - (parseFloat(totalDiscount));
                const quantity_min = 1;
       
                const table = document.getElementById("detalles").getElementsByTagName('tbody')[0];
                const row = table.insertRow();
                row.innerHTML = `
                    <td>
                        <input type="hidden" name="income_detail_id[]" value="${id}">
                        <input type="hidden" name="code[]" value="${code}">
                        ${code}
                    </td>
                    <td>
                        ${name}
                        <input type="hidden" value="${name}" name="description[]" />
                        </td>
                    <td>
                        <input type="number" name="quantity[]" readonly class="form-control form-control-sm" value="${quantityItem}" min="1" max="${stock}"  style="width:80px;" onchange="updateSubtotal(this)">
                    </td>
                    <td>
                        <input type="hidden" name="sale_price[]" readonly class="form-control form-control-sm" value="${sale_price}" min="0" step="0.01" style="width:100px;" onchange="updateSubtotalWithoutMaxMin(this)">
                         ${formatCurrency.format(parseFloat(sale_price).toFixed(0))}
                    <td>
                        <input type="number" name="discount[]" readonly class="form-control form-control-sm" value="${totalDiscount}" min="0" step="0.01" style="width:80px;" onchange="updateSubtotalWithoutMaxMin(this)">
                    </td>
                    <td class="subtotal">${formatCurrency.format(parseFloat(subtotal).toFixed(0))}</td>
                    <td class="form_sale">
                        <input type="hidden" name="form_sale[]" value="${form_sale}">
                        ${form_sale}
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteItem(this)"><li class='bi bi-trash'></li></button>
                    </td>
                `;

            //enfocar el input de buscar producto
            document.getElementById("product_search").value = "";
            document.getElementById("product_search").focus();
            //eliminar el enter por defecto que se genera
            updateTotal();
        }
        //adicionar cantidad al item seleccionado
        function add_quantity_Discount(item)
        {
            
            const [id, code, name, sale_price, form_sale, quantity] = item.split(",");
            //valido si es la tabla detalles hay con las mismas caracteristicas
            const existingRow = Array.from(document.querySelectorAll("#detalles tbody tr")).find(row => {
                return row.querySelector('input[name="income_detail_id[]"]').value === id;
                });
            if (existingRow) {
                if(parseInt(existingRow.querySelector('input[name="quantity[]"]').value) < quantity){
                     existingRow.querySelector('input[name="quantity[]"]').value = parseInt(existingRow.querySelector('input[name="quantity[]"]').value) + 1;
                     updateSubtotal(existingRow.querySelector('input[name="quantity[]"]'));
                }
            }
            else
            {
            const modalSetQuantity = document.getElementById("modal-set-quantity");
            modalSetQuantity.querySelector(".modal-header").innerHTML = `
                <div class="row">
                    <div class="form-group">
                        <div class="card text-dark bg-light mb-3">
                            <div class="card-header">
                                <h5 class="card-title">${name}</h5></div>
                            <div class="card-body">
                            <table with="100%" class="table table-bordered">
                                <tr>
                                    <td><b>Codigo:</b><br>${code}</td>
                                    <td><b>Precio:</b><br>${formatCurrency.format(parseFloat(sale_price).toFixed(0))}</td>
                                </tr>
                                <tr>
                                    <td><b>Forma Venta:</b><br>${form_sale}</td>
                                    <td><b>Stock:</b><br>${quantity}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="hidden" id="productId" name="productId" class="form-control" value="${id}"  />
                    <input type="hidden" id="productName" name="productName" class="form-control" value="${name}"  />
                    <input type="hidden" id="productCode" name="productCode" class="form-control" value="${code}" />
                    <input type="hidden" id="productStock" name="productStock" class="form-control" value="${quantity}"  />
                    <input type="hidden" id="productSalePrice" name="productSalePrice" class="form-control" value="${sale_price}" readonly /> 
                    <input type="hidden" id="productFormSale" name="productFormSale" class="form-control" value="${form_sale}" readonly />
                </div>
            </div>`;

            modalSetQuantity.querySelector(".modal-body").innerHTML = `
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="quantityItem">Cantidad</label>
                    <input type="number" id="quantityItem" onkeydown="accept_quantity(event)" onchange="updateSubtotalQuantityDiscount(event)" onkeyup="updateSubtotalQuantityDiscount(event)" name="quantityItem" class="form-control" value="1" min="1" max="${quantity}" step="1"/>
                </div>
                <div class="col-md-4 form-group">
                    <label for="discountItem">Descuento(%)/U</label>
                    <input type="number" id="discountPercent" onkeyup="updateSubtotalQuantityDiscount(event)" onchange="updateSubtotalQuantityDiscount(event)" name="discountPercent" class="form-control" value="0" min="0" step="0.01" max="100" />
                </div>
                <div class="col-md-4 form-group">
                    <label for="discountCurrency">Descuento($)/U</label>
                    <input type="number" id="discountItem" onkeyup="updateSubtotalQuantityDiscount(event)" onchange="updateSubtotalQuantityDiscount(event)" name="discountItem" class="form-control" value="0" min="0" step="0.01" max="${sale_price}" />
                </div>
                <input type="hidden" id="totalDiscount" value="0"/>
            </div>`;
            //agregar el subtotal al modal
           modalSetQuantity.querySelector(".subtotalItem").textContent = formatCurrency.format(parseFloat(sale_price * 1).toFixed(0));
           const modal_quantity = new bootstrap.Modal(modalSetQuantity);
           //crear una promesa que primero me muestre el modal y luego me enfoque
            modal_quantity.show(); 
            setTimeout(() => {
                    modalSetQuantity.querySelector("#quantityItem").focus()
            }, 500);
          }    
        }
   
        function accept_quantity(event) {
            event.preventDefault();
            if(event.key==="Enter"){
                const quantityItem = document.getElementById("quantityItem");
                const quantity = parseFloat(quantityItem.value) || 1;
                const stock = parseFloat(document.getElementById("productStock").value) || 0;
                if(quantity > stock){
                    Swal.fire({
                        title: 'Advertencia',
                        text: 'La cantidad no puede ser mayor al stock disponible',
                        icon: 'warning',
                        timer: 3000
                    });
                    quantityItem.value = stock;
                }
                else
                {
                    document.getElementById("modal-set-quantity").querySelector(".modal-footer button").click();
                    //enfocar el input de buscar producto
                    document.getElementById("product_search").value = "";
                    setTimeout(() => {
                        document.getElementById("product_search").focus();
                    }, 1000);
                       
                }
                //si usa las teclas flecha arriba y abajo del teclado aumenta la cantidad o desminuye de quantityItem
            }else if(event.key === "ArrowUp"){
                const quantityItem = document.getElementById("quantityItem");
                const stock = parseFloat(document.getElementById("productStock").value) || 0;
                let quantity = parseFloat(quantityItem.value) || 1;
                if(quantity < stock){
                    quantityItem.value = quantity + 1;
                }else{
                    return false;
                }
                
            }else if(event.key === "ArrowDown"){
                const quantityItem = document.getElementById("quantityItem");
                let quantity = parseFloat(quantityItem.value) || 1;
                if(quantity > 1){
                    quantityItem.value = quantity - 1;
                }else{
                    return false;
                }
            }
            updateSubtotalQuantityDiscount(event);
        }

        function validate_money(input){
            const valueSaved = input.value;
            if (!/^\d*$/.test(input.value)){
                input.value = 0;  
            }else{
                if(input.max && input.min){
                    if(!(parseFloat(input.value) <=parseFloat(input.max) && parseFloat(input.value) >= parseFloat(input.min))){
                    input.value = input.min;  
                    }
                    else
                    {
                        input.value = valueSaved;
                    }
                }
            } 
        }

        function validate_quantity(input){
            const valueSaved = input.value;
            if (!/^\d*$/.test(input.value)){
                input.value = 0;  
            }else{
                if(input.max!="" && input.min!=""){
                    if(!(parseInt(input.value) <=parseInt(input.max) && parseInt(input.value) >= parseInt(input.min))){
                    input.value = input.min;  
                    }
                    else
                    {
                        input.value = valueSaved;
                    }
                }
            } 
        }


        function updateSubtotalQuantityDiscount(e) {

            if(e.target.id=="discountPercent"){
                document.getElementById("discountItem").value = 0;
            }else if(e.target.id=="discountItem"){
                document.getElementById("discountPercent").value = 0;
            }


            validate_quantity(document.getElementById("quantityItem"));
            validate_quantity(document.getElementById("discountPercent"));
            validate_money(document.getElementById("discountItem"));

            const quantity = parseFloat(document.getElementById("quantityItem").value) || 1;
            const sale_price = parseFloat(document.getElementById("productSalePrice").value) || 0;
            //validar si el descuento se lo hace por porcentaje o por un valor momnetario al subtotal
            const discountPercent = parseFloat(document.getElementById("discountPercent").value) || 0;
            const discountItem = parseFloat(document.getElementById("discountItem").value) || 0;
            let discount = 0;   
            if (discountPercent > 0) {
                discount = (sale_price * discountPercent / 100).toFixed(2);
            } else if (discountItem > 0) {
                discount = discountItem.toFixed(2);
            }       
            // Calcular el subtotal final
            const finalSubtotal = ((sale_price - discount)* quantity).toFixed(2);
            // Actualizar el subtotal en el modal
            document.querySelector("#modal-set-quantity .subtotalItem").textContent = formatCurrency.format(parseFloat(finalSubtotal).toFixed(0));
            // Actualizar el valor del descuento total
            document.getElementById("totalDiscount").value = (discount * quantity);
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll("#detalles tbody tr").forEach(row => {
            const quantity = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
            const sale_price = parseFloat(row.querySelector('input[name="sale_price[]"]').value) || 0;
            const discount = parseFloat(row.querySelector('input[name="discount[]"]').value) || 0;
            const subtotal = ((quantity * sale_price) - discount).toFixed(2);
            row.querySelector('.subtotal').textContent = formatCurrency.format(parseFloat(subtotal).toFixed(0));
            total += parseFloat(subtotal);
            });
            document.getElementById("total").textContent = formatCurrency.format(total.toFixed(0)) ;
            document.getElementById("sale_total").value = total.toFixed(2);

            if(total > 0){
                const table_payments_tbody = document.querySelector('#table_payments tbody');
                table_payments_tbody.innerHTML = "";
                const tr = document.createElement("tr");
                tr.innerHTML = `
                <td>{{$payment_methods[0]}}</td>
                <td>${total.toFixed(2)}</td>
                <td><a href="#" onclick="deletePayment(this)" class="text-danger"><i class="bi bi-trash"></i></a></td>
                `;
                const totalPayment = document.querySelector('#totalPayment');
                totalPayment.textContent = formatCurrency.format(total.toFixed(0));
                table_payments_tbody.appendChild(tr);
                //mostrar el boton de facturar
                const btn_invoice = document.getElementById("invoice");
                btn_invoice.style.display = "";
            }else{
                const totalPayment = document.querySelector('#totalPayment');
                totalPayment.textContent = 0;
                const table_payments_tbody = document.querySelector('#table_payments tbody');
                table_payments_tbody.innerHTML = "";
                //ocultar el boton de facturar
                const btn_invoice = document.getElementById("invoice");
                btn_invoice.style.display = "none";
            }

        }
        //funcion para actualizar el subtotal
        function updateSubtotal(input) {
            if(input.getAttribute('min') && input.getAttribute("max")){
                  const min = parseFloat(input.getAttribute('min'));
                  const max = parseFloat(input.getAttribute('max'));
                  const valor = parseFloat(input.value);

                    if (!isNaN(min) && !isNaN(max)) {
                        if (!(valor >= min && valor <= max)) {
                            input.value = min;
                        } 
                    }else{
                        input.value = min;
                    }
            }else{
                return;
            }
            const row = input.closest('tr');
            const quantity = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
            const sale_price = parseFloat(row.querySelector('input[name="sale_price[]"]').value) || 0;
            const discount = parseFloat(row.querySelector('input[name="discount[]"]').value) || 0;
            const subtotal = ((quantity * sale_price) - discount).toFixed(2);
            row.querySelector('.subtotal').textContent = formatCurrency.format(parseFloat(subtotal).toFixed(0));
            updateTotal();
        }

        function updateSubtotalWithoutMaxMin(input) {
            const valor = parseFloat(input.value);
            if (isNaN(valor)) {
                return;
            }
            const row = input.closest('tr');
            const quantity = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
            const sale_price = parseFloat(row.querySelector('input[name="sale_price[]"]').value) || 0;
            const discount = parseFloat(row.querySelector('input[name="discount[]"]').value) || 0;
            const subtotal = ((quantity * sale_price) - discount).toFixed(2);
            row.querySelector('.subtotal').textContent = formatCurrency.format(parseFloat(subtotal).toFixed(0));
            updateTotal();
        }
        //funcion para eliminar un medio de pago
        function deletePayment(ele){
            /*
            const tr = ele.closest("tr");
            tr.remove();*/
            const table_payments = document.querySelector("#table_payments tbody");
            table_payments.innerHTML = "";
            updateTotalChange();
        } 
        //funcion que me permita adicionar un nuevo metodo de pago
        function add_payment(){
            const value = parseFloat(document.getElementById('payment_value').value);
            const sale_total = parseFloat(document.getElementById('sale_total').value);
            if(value=="" && value == 0){
                Swal.fire({"icon":"warning",'text':"valor inválido"});
                return;
            }

            const method = document.getElementById('payment_method').value;
            const table_payments = document.querySelector("#table_payments tbody");
            const tr_payments = document.querySelectorAll("#table_payments tbody tr");

            const tr = document.createElement("tr");
            const td1 = document.createElement("td");
            td1.textContent = method;
            const td2 = document.createElement("td");
            td2.textContent = value;
            const td3 = document.createElement("td");
            td3.innerHTML = `<a href="#" onclick="deletePayment(this)" class="text-danger"><i class="bi bi-trash"></i></a>`;
            if(tr_payments.length == 0){
                if(value >= sale_total){
                    const td4 = document.createElement("td");
                    td4.textContent = sale_total;
                    tr.appendChild(td1);
                    tr.appendChild(td2);
                    tr.appendChild(td3);
                    tr.appendChild(td4);
                    table_payments.appendChild(tr);
                }else{
                    const td4 = document.createElement("td");
                    td4.textContent = value;
                    tr.appendChild(td1);
                    tr.appendChild(td2);
                    tr.appendChild(td3);
                    tr.appendChild(td4);
                    table_payments.appendChild(tr);  
                }
            }else{
                let sum = 0;
                for(let i = 0; i<tr_payments.length; i++){
                    $pay = parseFloat(tr_payments[i].children[1].textContent);
                    sum = sum + $pay;
                }
                if((sum + value) <= sale_total){
                    const td4 = document.createElement("td");
                    td4.textContent = value;
                    tr.appendChild(td1);
                    tr.appendChild(td2);
                    tr.appendChild(td3);
                    tr.appendChild(td4);
                    table_payments.appendChild(tr);
                }else{
                    const td4 = document.createElement("td");
                    td4.textContent = sale_total-sum;
                    tr.appendChild(td1);
                    tr.appendChild(td2);
                    tr.appendChild(td3);
                    tr.appendChild(td4);
                    table_payments.appendChild(tr);
                }
                
            }

            updateTotalChange();
            document.getElementById('payment_method').value="{{$payment_methods[0]}}";
            document.getElementById('payment_value').value=0;
  
        }
        //Actualizar el total a pagar y el cambio
function updateTotalChange() {
    const btn_invoice = document.getElementById("invoice");
    
    const titleChange = document.getElementById('totalChangeTitle');
    const valueChange = document.getElementById('totalChange');

    const table_payments = document.querySelectorAll("#table_payments tbody tr");
    const sale_total = parseFloat(document.getElementById("sale_total").value) || 0;
    let sum_total = 0;
    let change = 0;
    if(table_payments.length > 0){
        for (let i = 0; i < table_payments.length; i++) {
            const valorPayment = parseFloat(table_payments[i].children[1].textContent) || 0;
            sum_total += valorPayment;
        }
         change = sum_total - sale_total;
         if(change < 0){
            titleChange.textContent="Faltan";
            valueChange.className = "text-danger";
            btn_invoice.style.display="none";
         }else{
            titleChange.textContent="Cambio";
            valueChange.className = "text-success";
            btn_invoice.style.display="";
         }
         
         
    }
    else
    {
        btn_invoice.style.display="none";
    }
    document.getElementById("totalChange").textContent = formatCurrency.format(change);
    document.getElementById("totalChangeHidden").value =change;
}

function deleteItem(item){
    
    item.closest('tr').remove(); 
     const table_detalles = document.querySelectorAll("#detalles tbody tr");
     if(table_detalles.length == 0){
        const btn_invoice = document.getElementById("invoice");
        const table_payments = document.querySelector("#table_payments tbody");
        const totalPayment = document.getElementById("totalPayment");
        totalPayment.textContent = 0;
        table_payments.innerHTML = "";
        btn_invoice.style.display = "none";
        

     }
    updateTotal();
}

async function toinvoice(){
            
            let payment_form = document.getElementById("payment_form").value;            
            
            let form = document.getElementById('form-sale');
            //--------------------------------------------
            const table_payments = document.querySelectorAll("#table_payments tbody tr");
            let methods = [];
            let cc = 0;
            for (let i = 0; i < table_payments.length; i++) {
                let valorPayment = parseFloat(table_payments[i].children[1].textContent);
                let methodPayment = table_payments[i].children[0].textContent;
                if(methodPayment=="credito")cc++;
                const obj = {method:methodPayment,value:valorPayment};
                methods.push(obj);
            }
            if(payment_form == "credito" && !(cc > 0)){
                Swal.fire({
                    title: 'Forma de pago',
                    text: 'Si la forma de pago es a Credito, se debe seleccionar almenos un medio de pago tipo credito',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                    timer: 6000
                });
                return;
            }else if(payment_form == 'contado' && (cc > 0)){
                Swal.fire({
                    title: 'Forma de Pago',
                    text: 'Si la forma de pago es a Contado, no se debe seleccionar medio de pago tipo credito',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                    timer: 6000
                });
                return;
            }
            const confirmTransaction = async () => {
                return Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Esta acción no se puede deshacer!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, Facturar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                });
            };
            const result = await confirmTransaction();
            if (!result.isConfirmed) return;
            showSpinner();
            //-------------------------------------------
            let formData = new FormData(form);
                formData.append("methods",JSON.stringify(methods));
                fetch("{{ route('sale.store') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
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
                            text: 'No se pudo facturar, intentalo nuevamente',
                            icon: 'error',
                            buttons: true,
                            dangerMode: true,
                            timer: 2000
                        });
                    }
                    //location.reload();
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
                     document.getElementById("btn_print").focus();
                });
        }
        function closeModal(){
            const myModal = new bootstrap.Modal(document.getElementById('modal-receipt-invoice'));
            myModal.hide();

        }

    </script>
@endpush

@endsection


