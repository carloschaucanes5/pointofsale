@extends('layouts.admin')

@section('title', 'Actualizar Cliente')

@section('content')
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Actualizar  Cliente</h3>
            </div>
            <form action="{{route('customer.update',$customer->id)}}" method="POST" class="form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="document_type">Nombre</label>
                        <select name="document_type" id="document_type" value="{{$customer->document_type}}" class="form-control">
                            <option value="CC">Cédula de Ciudadabia</option>
                            <option value="TI">Tarjeta de identidad</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="document_number">Número de documento</label>
                        <input type="text" class="form-control" name="document_number" id="document_number" value="{{$customer->document_number}}" placeholder="Ingresar el número de identificación">
                    </div>
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{$customer->name}}" placeholder="Ingresar el nombre">
                    </div>
                    <div class="form-group">
                        <label for="address">Dirección</label>
                        <input type="text" class="form-control" name="address" id="address" value="{{$customer->address}}" placeholder="Ingresar la dirección">
                    </div>
                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <input type="text" class="form-control" name="phone" id="phone" value="{{$customer->phone}}" placeholder="Ingresar el teléfono">
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="text" class="form-control" name="email" id="email" value="{{$customer->email}}" placeholder="Ingresar el correo electrónico">
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
