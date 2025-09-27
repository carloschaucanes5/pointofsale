@extends('layouts.admin')

@section('title', 'Movimientos de Caja')

@section('content')

<style>
    .table th, .table td {
        font-size: 0.8rem;
    }
    .cel-spent {
        background-color: rgb(253, 169, 112) !important;
    }
    .cel-income{
        background-color: rgb(130, 247, 130) !important;
    }
</style>

<div class="container">
    <h4 class="mb-4">Listado de Movimientos</h4>

<form action="{{ route('movement.index') }}" method="GET">
    <div class="row">
        <div class="col-md-2">
            <input type="date" name="from" class="form-control" value="{{ $from }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="to" class="form-control" value="{{ $to }}">
        </div>
        <div class="col-md-2">
            <select name="type" class="form-control" value="{{ $type }}">
                
                @if($type =='egreso')
                    <option value="">Todos</option>
                    <option value="egreso" selected>egreso</option>
                    <option value="ingreso">ingreso</option>
                @elseif($type=="ingreso")
                    <option value="">Todos</option>
                    <option value="egreso">egreso</option>
                    <option value="ingreso" selected>ingreso</option>
                @else
                    <option value="" selected>Todos</option>
                    <option value="egreso">egreso</option>
                    <option value="ingreso">ingreso</option>
                @endif
            </select>
        </div>
        <div class="col-md-2">
            <select name="cash_id" class="form-control" value="{{ $cash_selected }}">
                <option value="" >Todas las Cajas</option>
                @foreach($cashes as $cash)
                    @if($cash_selected == $cash->id)
                        <option value="{{$cash->id}}" selected>{{$cash->name}}</option>
                    @else
                        <option value="{{$cash->id}}">{{$cash->name}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-primary">Buscar</button>
        </div>
        <div class="col-md-2 text-end">
            <a href="{{ route('movement.create') }}" class="btn btn-success">+ Nuevo </a>
        </div>
    </div>
</form>
    @if ($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Fecha</th>
                <th>Caja</th>
                <th>Tipo</th>
                <th>Categoría</th>
                @foreach($payment_methods as $method)
                    <th>{{$method}}</th>
                @endforeach
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($movements as $movement)
                <tr>
                    <td>{{ $movement->created_at}}</td>
                    <td>{{ $movement->name_cash}}</td>
                    <td>
                        @if ($movement->type == 'ingreso')
                            <span class="badge bg-success">Ingreso</span>
                        @else
                            <span class="badge bg-danger">Egreso</span>
                        @endif
                    </td>
                    <td>{{ $movement->movement_type }}<small style="font-size: 12px;"> {{$movement->description}}</small></td>
                        @foreach($payment_methods as $method)
                            @if($method ==$movement->payment_method)
                                <td>${{number_format($movement->amount, 2, ',','.') }}</td>
                            @else
                                <td></td>
                            @endif
                        @endforeach

                    <td>{{ $movement->username ? $movement->username:'---' }}</td>
                </tr>
            @empty
            @endforelse
        </tbody>
    </table>
    {{-- Paginación --}}
    <div class="d-flex justify-content-end">
        {{ $paginator->links() }}
    </div>
    <div class="d-flex justify-content-center">
        <table class="table table-bordered table-hover">
            <tr>
                
                @foreach($payment_methods as $method)
                    <th>Total {{$method}}</th>
                @endforeach
            </tr>
            <tr>
                
                @foreach($payment_methods as $method)
                    @php
                        $b = 0;
                        $found = 0;
                    @endphp
                    @foreach($array_sums as $sum )
                        @if($method ==$sum->payment_method)
                            @php
                                $b = 1;
                                $found = $sum->total; 
                            @endphp
                        @endif
                    @endforeach
                    @if($b==1)
                        <td>${{number_format($found,2,',','.')}}</td>
                    @else
                        <th></th>
                    @endif
                @endforeach
            </tr>
        </table>
    </div>

</div>
@endsection