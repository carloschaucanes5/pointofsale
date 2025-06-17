@extends('layouts.admin')

@section('title', 'Crear Producto')

@section('content')
    <div class="col-md-7">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Nuevo Producto</h3>
            </div>
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" class="form">
    @csrf
    <div class="card-body">
        <div class="row">
            <!-- Campo Código -->
            <div class="col-12">
                <div class="form-group">
                    <label for="code">Código</label>
                    <input type="text" class="form-control" name="code" id="code" placeholder="Código de Barras" value="{{ old('code') }}">
                    @error('code')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Campo Nombre -->
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Ingresar el nombre del producto" value="{{ old('name') }}">
                    @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Campo Concentración -->
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="concentration">Concentración</label>
                    <input type="text" class="form-control" name="concentration" id="concentration" placeholder="Ingresar la concentración" value="{{ old('concentration') }}">
                    @error('concentration')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Campo Presentación -->
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="presentation">Presentación</label>
                    <input type="text" class="form-control" name="presentation" id="presentation" placeholder="Ingresar la presentación" value="{{ old('presentation') }}">
                    @error('presentation')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Campo Laboratorio -->
            <div class="col-md-6 col-12">
                <div class="row">
                    <div class="col-md-8 col-12">
                        <div class="form-group">
                            <label for="laboratory">Laboratorio</label>
                            <input type="text" class="form-control" name="laboratory" id="laboratory" list="list-laboratories" placeholder="Ingresar el laboratorio" value="{{ old('laboratory') }}" autocomplete="off">
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
              @error('laboratory')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
            </div>   
            

            <!-- Campo Categoría -->
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="category_id">Categoría</label>
                    <select name="category_id" class="form-control" id="category_id">
                        @foreach($category as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->category }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Campo Stock -->
            <div class="col-md-6 col-12" style="display: none">
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" class="form-control" name="stock" id="stock" placeholder="Ingresar la cantidad del producto" value="0">
                    @error('stock')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Campo Descripción -->
        <div class="col-12">
            <div class="form-group">
                <label for="description">Descripción</label>
                <textarea class="form-control" name="description" id="description" cols="100" placeholder="Ingresar la descripción">{{ old('description') }}</textarea>
                @error('description')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Campo Imagen -->
        <div class="col-md-6 col-12">
    <div class="form-group">
        <label for="image">Imagen</label>
        <input type="file" class="form-control" name="image" id="image" accept="image/*">
        @error('image')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
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

</script>





