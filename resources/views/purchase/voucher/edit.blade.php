@extends('layouts.admin')

@section('title', 'Actualizar factura')

@section('content')
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Editar Factura {{$voucher->voucher_number}}</h3>
            </div>
            <form action="{{route('voucher.update',$voucher->id)}}" method="POST" class="form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="voucher_number">Numero de Factura</label>
                        <input type="text" class="form-control" name="voucher_number" id="voucher_number" value="{{$voucher->voucher_number}}" placeholder="Ingresar el número de factura">
                    </div>
                    <div class="form-group">
                        <label for="total">Valor</label>
                        <input type="number" step="0.001" class="form-control" name="total" id="total" value="{{$voucher->total}}" placeholder="Ingresar el número de factura">
                    </div>
                    <div class="form-group">
                        <label for="description">Descripcion</label>
                        <textarea rows="4" class="form-control" name="description" id="description"  placeholder="Ingresar la descripcion">{{$voucher->description}}</textarea>
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
