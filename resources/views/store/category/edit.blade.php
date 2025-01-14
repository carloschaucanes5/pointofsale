@extends('layouts.admin')

@section('title', 'Crear Categoria')

@section('content')
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Editar Categoria {{$category->category}}</h3>
            </div>
            <form action="{{route('category.update',$category->id)}}" method="POST" class="form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="category">Nombre</label>
                        <input type="text" class="form-control" name="category" id="category" value="{{$category->category}}" placeholder="Ingresar el nombre de la categoria">
                    </div>
                    <div class="form-group">
                        <label for="description">Descripcion</label>
                        <input type="text" class="form-control" name="description" id="description" value="{{$category->description}}" placeholder="Ingresar la descripcion">
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
