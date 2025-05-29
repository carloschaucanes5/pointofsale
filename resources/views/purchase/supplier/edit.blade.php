@extends('layouts.admin')

@section('title', 'Actualizar Proveedor')

@section('content')
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Actualizar  Proveedor</h3>
            </div>
            <form action="{{route('supplier.update',$supplier->id)}}" method="POST" class="form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="document_type">Nombre</label>
                        <select name="document_type" id="document_type" value="{{$supplier->document_type}}" class="form-control">
                            <option value="CC">Cédula de Ciudadabia</option>
                            <option value="TI">Tarjeta de identidad</option>
                            <option value="NIT">NIT</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="document_number">Número de documento</label>
                        <input type="text" class="form-control" name="document_number" id="document_number" value="{{$supplier->document_number}}" placeholder="Ingresar el número de identificación">
                    </div>
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{$supplier->name}}" placeholder="Ingresar el nombre">
                    </div>
                    <div class="form-group">
                        <label for="address">Dirección</label>
                        <input type="text" class="form-control" name="address" id="address" value="{{$supplier->address}}" placeholder="Ingresar la dirección">
                    </div>
                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <input type="text" class="form-control" name="phone" id="phone" value="{{$supplier->phone}}" placeholder="Ingresar el teléfono">
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="text" class="form-control" name="email" id="email" value="{{$supplier->email}}" placeholder="Ingresar el correo electrónico">
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
