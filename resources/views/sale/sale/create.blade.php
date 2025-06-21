@extends('layouts.admin')

@section('title', 'Crear Venta')

@section('content')
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Nueva Venta</h3>
            </div>
            <form action="{{route('sale.store')}}" method="POST" class="form">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="product_search">Producto</label>
                                <input type="text" class="form-control" id="product_search"  placeholder="Introduce el codigo de barras o el nombre del producto"/>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-1 table-striped table-hover table-bordered align-middle" id="incomes_detail">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>CB</th>
                                            <th>Producto</th>
                                            <th>Stock</th>
                                            <th>Precio/U</th>
                                            <th>M/V</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody> 
                                </table>
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
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="quantity">Cantidad</label>
                                <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Cantidad">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="stock">Stock</label>
                                <input type="number" disabled class="form-control" name="stock" id="stock" step="1" min="0" placeholder="Stock">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="sale_price">P. Venta</label>
                                <input type="number" class="form-control" disabled name="sale_price" id="sale_price" step="0.01" min="0" placeholder="Precio venta">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="discount">Descuento</label>
                                <input type="number" value="0" class="form-control"  name="discount" id="discount" step="0.01" min="0" placeholder="Descuento">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="btn_add"></label><br/>
                                <button type="button" id="btn_add" class="btn btn-success me-1 mb-1">Agregar</button>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-hover mb-1" id="detalles">
                                <thead >
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Venta</th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="total">$ 0.00</h4><input type="hidden"  name="sale_total" id="sale_total"/></th>
                                </tfoot>
                                <tbody>
                                  
                                </tbody> 
                            </table>
                        </div>
                    </div>
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
                                    <td>${item.name}</td>
                                    <td>${item.stock}</td>
                                    <td>${item.sale_price}</td>
                                    <td>${item.form_sale}</td>
                                `;
                                tbody.appendChild(tr);
                            });
                        } else {
                            // Si no hay resultados, muestra un mensaje
                            const tr = document.createElement("tr");
                            tr.innerHTML = `<td colspan="5" class="text-center">No se encontraron productos</td>`;
                            tbody.appendChild(tr);
                        }

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
                                        <a href="#" class="page-link" data-page="${page}">${page}</a>
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
                                                        <td>${item.stock}</td>
                                                        <td>${item.sale_price}</td>
                                                        <td>${item.form_sale}</td>
                                                    `;
                                                    tbody.appendChild(tr);
                                                });
                                            } else {
                                                const tr = document.createElement("tr");
                                                tr.innerHTML = `<td colspan="5" class="text-center">No se encontraron productos</td>`;
                                                tbody.appendChild(tr);
                                            }
                                            // Actualizar paginación
                                            // (puedes llamar a una función aquí si extraes el código)
                                            // --- FIN BLOQUE RECURSIVO ---
                                            // Para evitar duplicación, puedes extraer el renderizado de tabla y paginación a funciones.
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                        }).finally(()=>{
                                            hideSpinner();
                                        });
                                });
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    }).finally(()=>{
                        hideSpinner();
                    });
                }
            })
        });

        /*
        $(document).ready(function(){
            $('#btn_add').click(function(){
                add();
            });
        });

        $('#bt_save').click(function(){
            swal({
                title:"Su cambio es!",
                text:"Gracias por su compra",
                type:"success"
            })
        });


        var cont = 0; 
        var total = 0;
        var subtotal = [];
        $('#save').hide();
        $('#product_id').change(showValues());

        function showValues(){
            var dataArticle = document.getElementById('product_id').value.split('_');
            console.log(dataArticle);
            $('#stock').val(dataArticle[1]);
            $('#sale_price').val(dataArticle[2]);
        }

        function add(){
            var dataArticle = document.getElementById('product_id').value.split('_');
            var product_id = dataArticle[0];
            var product = $('#product_id option:selected').text();
            var quantity = parseInt($('#quantity').val());
            var discount = parseInt($('#discount').val());
            var sale_price =parseFloat($('#sale_price').val());
            var stock = $('#stock').val();
            if(product_id!="" && quantity!="" && quantity > 0 && sale_price !=""){
                if(parseInt(quantity) < parseInt(stock)){
                    subtotal[cont] = quantity * sale_price;
                    total = total + subtotal[cont];
                        var row = `
                        <tr class="selected" id="fila`+cont+`">
                        <td>
                            <button type="button" class="btn btn-warning" onclick="eliminar(`+cont+`)">x</button>
                        </td>
                        <td><input type="hidden" name="products[]" value="`+product_id+`">`+product_id+`</td>
                        <td><input class="form-control" type="number" name="quantities[]" value="`+quantity+`"></td>
                        <td><input class="form-control" type="number" name="discounts[]" value="`+discount+`"></td>
                        <td><input class="form-control" type="number" name="sale_prices[]" value="`+sale_price+`"></td>
                        <td>`+subtotal[cont]+`</td>
                    </tr>
                    `;
                    cont++;
                    limpiar();
                    $('#total').html("$ "+total);
                    $('#sale_total').val(total);
                    evaluate();
                    $('#detalles').append(row);
                }
                else
                {
                    alert("la cantidad vendida supera el Stock");
                }
                
            }
            else
            {
                alert("Error al ingresar el detalle del ingreso, revise los datos del articulo");
            }
        }

        function limpiar(){
            $('#quantity').val(1);
            $('#discount').val(0); 
        }

        function evaluate(){
            if(total > 0){
                $('#save').show();
            }
            else
            {
                $('#save').hide();
            }
        }

        function eliminar(index){
            total = total -subtotal[index];
            $('#total').html('$ '+ total);
            $('#total_sale').val(total);
            $('#fila'+index).remove();
            evaluate();
        }
    */
    
    </script>
@endpush

@endsection

