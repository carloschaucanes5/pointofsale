@extends('layouts.admin')

@section('title', 'Crear Venta')

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
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="customer_id"><b>Cliente</b></label>
                                <select name="customer_id" id="customer_id" class="form-control selectpicker" data-live-search="true">
                                    @foreach ($persons as $per)
                                    <option value="{{$per->id}}" data-tokens="{{$per->id}}">{{$per->name}}</option>   
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
                                    <button type="button" class="btn btn-primary"><i class="bi bi-plus-circle" onclick="add_payment()"></i></button>
                                </div>
                            </div>
                            <hr/>
                            <div class="row form-group" >
                                    <table id="table_payments" class=" table table-hover mb-1 table-sm table-striped table-hover table-bordered align-middle">
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
                                                        <button type="button" id="invoice" onclick="toinvoice()" class="btn btn-success me-1 mb-1">FACTURAR</button>
                                                    </div>
                                                </td>
                                                <td> 
                                                    <div style="display: flex;flex-direction: row;justify-content: center"> 
                                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                        <button type="reset" class="btn btn-danger me-1 mb-1">Cancelar</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                            </div>

                    </div>
                    <hr/>
                </div>
                <div class="form-group">
                    <div class="card-footer">
                        
                        
                    </div>
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
        document.addEventListener('DOMContentLoaded',function(){

            //llamar tipo ajax hacia el controlador para la busqueda de entradas o inventario
            document.getElementById("product_search").addEventListener("keyup",function(e){
                e.preventDefault();
                if(e.key == 'Enter'){
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
                                tr.innerHTML = `
                                    <td>${item.code}</td>
                                    <td>${item.name} ${item.concentration} ${item.presentation}</td>
                                    <td>${item.quantity}</td>
                                    <td>${formatCurrency.format(parseFloat(item.sale_price).toFixed(0))}</td>
                                    <td>${item.form_sale}</td>
                                    <td>${item.expiration_date}</td>
                                    <td><button class="btn btn-warning" type="button" onclick="add_item(\'${item.id},${item.code},${item.name} ${item.concentration} ${item.presentation},${item.sale_price},${item.form_sale}\')"> <span class="bi bi-cart"></span></button></td>
                                `;
                                tbody.appendChild(tr);
                            });
                        } else {
                            // Si no hay resultados, muestra un mensaje
                            const tr = document.createElement("tr");
                            tr.innerHTML = `<td colspan="5" class="text-center">No se encontraron productos</td>`;
                            tbody.appendChild(tr);
                        }
                        create_pagination(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    }).finally(()=>{
                        hideSpinner();
                        
                    });
                }
            })

            //evitar que se vaya en submit
            document.querySelector('form').addEventListener('submit', function(e) {
                e.preventDefault(); // Cancela cualquier envío
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
                                    if (data.incomes_detail && data.incomes_detail.data.length > 0) {
                                        data.incomes_detail.data.forEach(item => {
                                            const tr = document.createElement("tr");
                                            tr.innerHTML = `
                                                <td>${item.code}</td>
                                                <td>${item.name}</td>
                                               <td>${formatCurrency.format(parseFloat(item.sale_price).toFixed(0))}</td>
                                                <td>${item.form_sale}</td>
                                                <td><button class="btn btn-warning" type="button" onclick="add_item(\'${item.id},${item.code},${item.name} ${item.concentration} ${item.presentation},${item.sale_price},${item.form_sale}\')"> <span class="bi bi-cart"></span></button></td>
                                            `;
                                            tbody.appendChild(tr);
                                        });
                                    } else {
                                        const tr = document.createElement("tr");
                                        tr.innerHTML = `<td colspan="5" class="text-center">No se encontraron productos</td>`;
                                        tbody.appendChild(tr);
                                    }

                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                }).finally(()=>{
                                    hideSpinner();
                                });
                        });
                    });
                }
        }

        //verificar si el item se encuentra en el carrito de compras
        function verifyExist(code,sale_price,form_sale){
            const codes = document.querySelectorAll("input[name='code[]']");
            const sale_prices = document.querySelectorAll("input[name='sale_price[]']");
            const form_sales = document.querySelectorAll("input[name='form_sale[]']");
            let b = 0;
            for(let i = 0;i<codes.length;i++){
                if(codes[i].value == code && sale_prices[i].value == sale_price && form_sales[i].value == form_sale){
                    b = 1;
                }
                
            }
            if(b==1){
                return true;
            }
            else
            {
                return false;
            }
        }

        //function para adicionar un item al carrito de compras
        function add_item(item)
        {
            const [id, code, name, sale_price, form_sale] = item.split(",");
            if(!verifyExist(code,sale_price,form_sale)){
                const quantity = 1;
                const discount = 0
                const subtotal = ((quantity * sale_price) - discount).toFixed(2);
                const table = document.getElementById("detalles").getElementsByTagName('tbody')[0];
                const row = table.insertRow();
                row.innerHTML = `
                    <td>
                        
                        <input type="hidden" name="income_detail_id[]" value="${id}">
                        <input type="hidden" name="code[]" value="${code}">
                        ${code}
                    </td>
                    <td>${name}</td>
                    <td>
                        <input type="number" name="quantity[]" class="form-control form-control-sm" value="${quantity}" min="1" style="width:80px;" onchange="updateSubtotal(this)">
                    </td>
                    <td>
                        <input type="hidden" name="sale_price[]" readonly class="form-control form-control-sm" value="${sale_price}" min="0" step="0.01" style="width:100px;" onchange="updateSubtotal(this)">
                         ${formatCurrency.format(parseFloat(sale_price).toFixed(0))}
                        
                    <td>
                        <input type="number" name="discount[]" class="form-control form-control-sm" value="${discount}" min="0" step="0.01" style="width:80px;" onchange="updateSubtotal(this)">
                    </td>
                    <td class="subtotal">${formatCurrency.format(parseFloat(subtotal).toFixed(0))}</td>
                    <td class="form_sale">
                        <input type="hidden" name="form_sale[]" value="${form_sale}">
                        ${form_sale}
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); updateTotal();"><li class='bi bi-trash'></li></button>
                    </td>
                `;
            }
            else
            {
                    Swal.fire({
                    icon:"warning",
                    text:"El producto ya se encuentra en la lista, modifica la cantidad si deseas agragar un nuevo",
                    timer:4000
                });
            }
            updateTotal();
            
        }
        //function para actualizar el total
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
            }
        }
        //funcion para actualizar el subtotal
        function updateSubtotal(input) {
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
            const tr = ele.closest("tr");
            tr.remove();
            updateTotalChange();
        } 
        //funcion que me permita adicionar un nuevo metodo de pago
        function add_payment(){
            const method = document.getElementById('payment_method').value;
            const value = document.getElementById('payment_value').value;
            if(value.trim()!="" && value!=0){
                const table_payments = document.querySelector("#table_payments tbody");
                const tr = document.createElement("tr");
                const td1 = document.createElement("td");
                td1.textContent = method;
                const td2 = document.createElement("td");
                td2.textContent = value;
                const td3 = document.createElement("td");
                td3.innerHTML = `<a href="#" onclick="deletePayment(this)" class="text-danger"><i class="bi bi-trash"></i></a>`;
                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);
                table_payments.appendChild(tr);
                updateTotalChange();
                document.getElementById('payment_method').value="{{$payment_methods[0]}}";
                document.getElementById('payment_value').value=0;
            }
            else
            {
                Swal.fire({
                    title:"Mensaje",
                    text:"se debe ingresar el valor del pago",
                    icon:"warning"
                })
            }          
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

function toinvoice(){
            showSpinner();
            let form = document.getElementById('form-sale');
            //--------------------------------------------
            const table_payments = document.querySelectorAll("#table_payments tbody tr");
            let methods = [];
            for (let i = 0; i < table_payments.length; i++) {
                let valorPayment = parseFloat(table_payments[i].children[1].textContent);
                let methodPayment = table_payments[i].children[0].textContent;
                const obj = {method:methodPayment,value:valorPayment};
                methods.push(obj);
            }

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
                    hideSpinner();
                    if(data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: data.message,
                            timer: 3000,
                        }).then(() => {
                            window.location.href = "{{route('sale.index')}}";
                        });
                    }else {
                        Swal.fire({
                            title: 'Error',
                            text: 'No se pudo facturar, intentalo nuevamente',
                            icon: 'error',
                            buttons: true,
                            dangerMode: true,
                            timer: 3000
                        });
                    }
                    //location.reload();
                })
                .catch(error => {
                    hideSpinner();
                    console.log(error);
                    alert('Error al guardar');
                });
        }

    </script>
@endpush

@endsection

