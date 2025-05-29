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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="voucher_type">Tipo de comprobante</label>
                                <select name="voucher_type" id="voucher_type" class="form-control">
                                    <option value="RFC">RFC</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
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
                                <select name="product_id" id="product_id" class="form-control selectpicker" data-live-search="true">
                                    @foreach ($products as $pro)
                                    <option value="{{$pro->id}}" data-tokens="{{$pro->id}}">{{$pro->article}}</option>   
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
                                <label for="purchase_price">P. Compra</label>
                                <input type="number" class="form-control" name="purchase_price" id="purchase_price" step="0.01" min="0" placeholder="Precio compra">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sale_price">P. Venta</label>
                                <input type="number" class="form-control" name="sale_price" id="sale_price" step="0.01" min="0" placeholder="Precio venta">
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
                                        <th>Precio Compra</th>
                                        <th>Precio Venta</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th id="total">$ 0.00</th>
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

        $(document).ready(function(){
            $('#btn_add').click(function(){
                add();
            });
        });
        var cont = 0; 
        var total = 0;
        var subtotal = [];
        $('#save').hide();
        $('#product_id').change(showValues());

        function showValues(){
            var dataArticle = document.getElementById('product_id').value.split('_');
            $('#quantity').val(dataArticle[1]);
            $('#unit').html(dataArticle[2]);
        }

        function add(){
            var dataArticle = document.getElementById('product_id').value.split('_');

            var product_id = dataArticle[0];
            var product = $('#product_id option:selected').text();
            var quantity = $('#quantity').val();
            var purchase_price = $('#purchase_price').val();
            var sale_price = $('#sale_price').val();
            if(product_id!="" && quantity!="" && quantity > 0 && purchase_price !="" && sale_price!=""){
                subtotal[cont] = quantity * purchase_price;
                total = total + subtotal[cont];

                var row = `
                <tr class="selected" id="fila`+cont+`">
                    <td>
                        <button type="button" class="btn btn-warning" onclick="eliminar(`+cont+`)">x</button>
                    </td>
                    <td><input type="hidden" name="products[]" value="`+product_id+`">`+product_id+`</td>
                    <td><input class="form-control" type="number" name="quantities[]" value="`+quantity+`"></td>
                    <td><input class="form-control" type="number" name="purchase_prices[]" value="`+purchase_price+`"></td>
                    <td><input class="form-control" type="number" name="sale_prices[]" value="`+sale_price+`"></td>
                    <td>`+subtotal[cont]+`</td>
                </tr>
                `;
                cont++;
                limpiar();
                $('#total').html("$ "+total);
                evaluate();
                $('#detalles').append(row);
            }
            else
            {
                alert("Error al ingresar el detalle del ingreso, revise los datos del articulo");
            }
        }

        function limpiar(){
            $('#quantity').val("");
            $('#purchase_price').val("");
            $('#sale_price').val("");
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
            $('#fila'+index).remove();
            evaluate();
        }

    

    </script>
@endpush

@endsection

