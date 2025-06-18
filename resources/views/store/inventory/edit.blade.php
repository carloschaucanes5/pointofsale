@extends('layouts.admin')

@section('title', 'Editar Producto')

@section('content')
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Editar Producto {{$product->name}}</h3>
            </div>
            <form action="{{route('product.update',$product->id)}}" method="POST" enctype="multipart/form-data" class="form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Codigo</label>
                                <input type="text" class="form-control" name="code" id="code" value="{{$product->code}}" placeholder="Ingresar el codigo del producto">
                                @error('code')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{$product->name}}" placeholder="Ingresar el nombre del producto">
                                @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="concentration">Concentración</label>
                                <input type="text" class="form-control" name="concentration" value="{{ $product->concentration }}" id="concentration" placeholder="Ingresar la concentracion">
                                @error('concentration')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="presentation">Presentación</label>
                                <input type="text" class="form-control" name="presentation" value="{{ $product->presentation }}" id="presentation" placeholder="Ingresar la presentacion">
                                @error('presentation')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="row">
                                <div class="col-md-8 col-12">
                                    <div class="form-group">
                                        <label for="laboratory">Laboratorio</label>
                                        <input type="text" class="form-control" name="laboratory" id="laboratory" value="{{ $product->laboratory}}" list="list-laboratories" placeholder="Ingresar el laboratorio">
                                        @error('laboratory')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                        <div id="container-laboratories">
                                            <datalist id="list-laboratories">
                                                @foreach ($laboratories as $lab)
                                                    <option>{{ $lab->name }}</option>
                                                @endforeach
                                            </datalist>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <br/>
                                        <input type="button" class="btn btn-info"  value="Nuevo" onclick="clearContainer()" data-bs-toggle="modal" data-bs-target="#modal-new-laboratory"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="category_id">Categoria</label>
                                <select name="category_id" class="form-control" id="category_id">
                                    @foreach($categories as $cat)
                                        @if($cat->id == $product->category_id)
                                            <option value="{{$cat->id}}">{{$cat->category}}</option>
                                        @else
                                            <option value="{{$cat->id}}">{{$cat->category}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="col-md-6 col-12" style="display: none">
                            <div class="form-group">
                                <label for="stock">Stock</label>
                                <input type="number" class="form-control" name="stock" id="stock" value="{{$product->stock}}" placeholder="Ingresar la cantidad del producto">
                                @error('stock')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea type="text" class="form-control" name="description" id="description"  placeholder="Ingresar la descripcion">{{$product->description}}</textarea>
                            @error('description')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="image">Imagen</label>
                            <input type="file" class="form-control" name="image" id="image" placeholder="Imagen">
                            @error('image')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            @if(($product->image)!="")
                            <!--img src="{{asset('images/products/'.$product->image)}}" alt="imagen" height="100px" width="100px"/-->
                            <img src="{{ asset('images/products/'.$product->image) }}" alt="Product Image" height="150px" width="300px" >
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success me-1 mb-1">Guardar</button>
                    <button type="reset" class="btn btn-danger me-1 mb-1">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@include("store.laboratory.modal")

<script>
    document.addEventListener('DOMContentLoaded', e => {
        $('#laboratory').autocomplete()
    }, false);

    function clearContainer(){
        const responseMessage = document.getElementById('responseMessage');
        const errorContainer = document.getElementById('errorContainer');
        responseMessage.innerHTML = '';
        errorContainer.innerHTML = ''; 
    }
</script>
