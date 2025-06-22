@extends('layouts.admin')

@section('title', 'Crear Venta')

@section('content')
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header py-1 px-2">
                <h5 class="card-title m-0">Nueva Venta</h5>
            </div>
            <form action="{{route('sale.store')}}" method="POST" class="form" id="form-sale">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="product_search">Producto</label>
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
                                                <th></th>
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
                                <label for="customer_id">Cliente</label>
                                <select name="customer_id" id="customer_id" class="form-control selectpicker" data-live-search="true">
                                    @foreach ($persons as $per)
                                    <option value="{{$per->id}}" data-tokens="{{$per->id}}">{{$per->name}}</option>   
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="payment_method">Tipo de comprobante</label>
                                <select name="payment_method" id="payment_method" class="form-control">
                                    @foreach($payment_methods as $method)
                                        <option value="{{$method}}">{{$method}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr/>
                </div>
                <div class="form-group">
                    <div class="card-footer">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <button type="button" id="save" class="btn btn-success me-1 mb-1">Guardar</button>
                        <button type="reset" class="btn btn-danger me-1 mb-1">Cancelar</button>
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
                                    <td>${formatCurrency.format(parseFloat(item.sale_price).toFixed(0))}</td>
                                    <td>${item.form_sale}</td>
                                    <td>${item.expiration_date}</td>
                                    <td><button class="btn btn-warning" onclick="add_item(\'${item.id},${item.code},${item.name} ${item.concentration} ${item.presentation},${item.sale_price},${item.form_sale}\')"> <i class="bi bi-cart"></i></button></td>
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
                                                <td>${item.sale_price}</td>
                                                <td>${item.form_sale}</td>
                                                <td><button class="btn btn-warning" onclick="add_item(\'${item.id},${item.code},${item.name} ${item.concentration} ${item.presentation},${item.sale_price},${item.form_sale}\')"> <i class="bi bi-cart"></i></button></td>
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
        //function para adicionar un item al carrito de compras
        function add_item(item)
        {
            const [id, code, name, sale_price, form_sale] = item.split(",");
            const quantity = 0;
            const discount = 0
            const subtotal = ((quantity * sale_price) - discount).toFixed(2);

            const table = document.getElementById("detalles").getElementsByTagName('tbody')[0];
            const row = table.insertRow();

            row.innerHTML = `
                <td>
                    
                    <input type="hidden" name="product_id[]" value="${id}">
                    ${code}
                </td>
                <td>${name}</td>
                <td>
                    <input type="number" name="quantity[]" class="form-control form-control-sm" value="${quantity}" min="1" style="width:80px;" onchange="updateSubtotal(this)">
                </td>
                <td>
                    <input type="number" name="sale_price[]" class="form-control form-control-sm" value="${sale_price}" min="0" step="0.01" style="width:100px;" onchange="updateSubtotal(this)">
                </td>
                <td>
                    <input type="number" name="discount[]" class="form-control form-control-sm" value="${discount}" min="0" step="0.01" style="width:80px;" onchange="updateSubtotal(this)">
                </td>
                <td class="subtotal">${subtotal}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); updateTotal();"><li class='bi bi-trash'></li></button>
                </td>
            `;

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
            row.querySelector('.subtotal').textContent = subtotal;
            total += parseFloat(subtotal);
            });
            document.getElementById("total").textContent = "$ " + total.toFixed(2);
            document.getElementById("sale_total").value = total.toFixed(2);
        }
        //funcion para actualizar el subtotal
        function updateSubtotal(input) {
            const row = input.closest('tr');
            const quantity = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
            const sale_price = parseFloat(row.querySelector('input[name="sale_price[]"]').value) || 0;
            const discount = parseFloat(row.querySelector('input[name="discount[]"]').value) || 0;
            const subtotal = ((quantity * sale_price) - discount).toFixed(2);
            row.querySelector('.subtotal').textContent = subtotal;
            updateTotal();
        }



    </script>
@endpush

@endsection

