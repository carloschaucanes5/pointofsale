@extends('layouts.admin')

@section('title', 'Mostrar Ingreso')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bi bi-cash-coin me-2 fs-4"></i>
                    <h5 class="mb-0">Apertura de Caja</h5>
                </div>

                <div class="card-body">
                    {{-- Mostrar errores de validación --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('cash_opening.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="start_amount" class="form-label">💰 Monto inicial (efectivo)</label>
                            <input type="number" step="0.01" value="90000" name="start_amount" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="cashbox_name" class="form-label">📦 Nombre de la caja</label>
                            <select name="cashbox_name" class="form-control" required>
                                @foreach($cash_registers as $cash)
                                  <option value="{{$cash}}">{{$cash}}</option>  
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">📍 Ubicación (opcional)</label>
                            <select  name="location" class="form-control">
                                @foreach($cash_locations as $location)
                                  <option value="{{$location}}">{{$location}}</option>  
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="cash_id" class="form-label"> Afecta a</label>
                            <select name="cash_id" id="cash_id" class="form-select" required>
                                @foreach($cashes as $cash)
                                @php
                                        $selected= '';
                                        if($cash->id==3){
                                            $selected = 'selected';
                                        }
                                @endphp
                                    <option {{$selected}} value="{{$cash->id}}">{{$cash->name}}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="mb-3">
                            <label for="observations" class="form-label">📝 Observaciones (opcional)</label>
                            <textarea name="observations" class="form-control" rows="1"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Abrir Caja
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

