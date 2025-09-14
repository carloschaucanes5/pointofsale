@extends('layouts.admin')

@section('title', 'Registrar Movimiento')

@section('content')
<div class="container mt-4">
    <h4>Registrar Movimiento de Dinero</h4>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <form action="{{ route('movement.store') }}" method="POST">
        @csrf

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="type" class="form-label">Tipo de Movimiento</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="">Seleccione</option>
                    <option value="ingreso">Ingreso</option>
                    <option value="egreso">Egreso</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="movement_type_id" class="form-label">Categoria Movimiento</label>
                <div class="mb-3">
                    
                    <select name="movement_type_id" id="movement_type_id" class="form-select" required>
                        <option value="">Seleccione una opción</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <label for="cash_id" class="form-label">Afecta a</label>
                <div class="mb-3">
                    <select name="cash_id" id="cash_id" class="form-select" required>
                        @foreach($cashes as $cash)
                           @php
                                $selected= '';
                                if($cash->id==1){
                                    $selected = 'selected';
                                }
                           @endphp
                            <option {{$selected}} value="{{$cash->id}}">{{$cash->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="amount" class="form-label">Monto</label>
                <input type="number" name="amount" id="amount" step="0.01" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="payment_method" class="form-label">Método de Pago</label>
                <select id="payment_method" name="payment_method" class="form-control">
                    <option value="">Seleccionar un medio de pago</option>
                    @foreach($methods as $method)
                            <option value="{{$method}}">{{$method}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descripción</label>
            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('movement.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar Movimiento
            </button>
        </div>
    </form>
</div>

@endsection
@push('scripts')
    

<script>
    document.addEventListener('DOMContentLoaded', function () {
document.getElementById('type').addEventListener('change', function () {
    const type = this.value;
    const movementTypeSelect = document.getElementById('movement_type_id');

    // Limpiar opciones actuales
    movementTypeSelect.innerHTML = '<option value="">Cargando...</option>';

    if (type !== '') {
        const baseUrl = "{{ url('movement/types') }}";
        fetch(`${baseUrl}/${type}`)
            .then(response => response.json())
            .then(data => {
                movementTypeSelect.innerHTML = '<option value="">Seleccione una opción</option>';
                data.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type.id;
                    option.text = type.name;
                    movementTypeSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error cargando tipos:', error);
                movementTypeSelect.innerHTML = '<option value="">Error al cargar</option>';
            });
    } else {
        movementTypeSelect.innerHTML = '<option value="">Seleccione una opción</option>';
    }
});
    });
</script>
@endpush