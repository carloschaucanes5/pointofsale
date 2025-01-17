@extends('layouts.admin')

@section('title', 'Crear Producto')

@section('content')
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Nuevo Producto</h3>
            </div>
            <form action="{{route('product.store')}}" method="POST" enctype="multipart/form-data" class="form">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Ingresar el nombre del producto">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="name">Categoria</label>
                                <select name="category_id" class="form-control" id="category_id">
                                    @foreach($category as $cat)
                                    <option value="{{$cat->id}}">{{$cat->category}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="name">Codigo</label>
                                <input type="text" class="form-control" name="code" id="code" placeholder="Ingresar el codigo del producto">
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="name">Stock</label>
                                <input type="number" class="form-control" name="stock" id="stock" placeholder="Ingresar la cantidad del producto">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="description">Descripcion</label>
                            <input type="text" class="form-control" name="description" id="description" placeholder="Ingresar la descripcion">
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="image">Imagen</label>
                            <input type="file" class="form-control" name="image" id="image" placeholder="Imagen">
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
