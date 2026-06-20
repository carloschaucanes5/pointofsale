@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @if(session('allow_access_page'))
                        <h1>Acceso No Permitido</h1>
                    @else
                        <div class="card-header">
                            <label>Inventario por Finalizar</label>
                            <form action="{{ route('home') }}" method="GET" class="row g-3 mb-4" id="form_existencias">
                                <select class="form-control"  name="category" id="category" onchange="document.getElementById('form_existencias').submit()">
                                    @php
                                    $selected = $category;
                                    @endphp
                                    @foreach ($categories as $cat)
                                        @if ($cat->id == $selected)
                                            <option selected="{{ $selected  }}" value="{{ $cat->id }}">{{$cat->category}}</option>
                                        @else
                                            <option  value="{{ $cat->id }}">{{$cat->category}}</option>
                                        @endif
                                        
                                    @endforeach
                                </select>
                            </form> 
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 table-sm">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Producto</th>
                                            <th>Laboratorio</th>
                                            <th>Existencias</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forEach($products as $pro)
                                        @php
                                        $claseFondo = "";
                                        if($pro->existencias == 0)
                                        {
                                             $claseFondo = "table-danger";
                                        }elseif($pro->existencias >=1 && $pro->existencias <= 5){
                                            $claseFondo = "table-warning";
                                        }else{
                                           $claseFondo = "table-success"; 
                                        }
                                        @endphp
                                            <tr class="{{ $claseFondo }}">
                                                <td>{{$pro->code}}</td>
                                                <td>{{$pro->name}}</td>
                                                <td>{{$pro->laboratory}}</td>
                                                <td>{{$pro->existencias}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{$products->links('pagination::bootstrap-5')}}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection