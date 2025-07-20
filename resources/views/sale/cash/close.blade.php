@extends('layouts.admin')

@section('title', 'Mostrar Ingreso')

@section('content')
<div class="container mt-1">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bi bi-cash-coin me-2 fs-4"></i>
                    <h5 class="mb-0">Cerrar Caja</h5>
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
                        <div class="row">
                            <div class="col-md-6 bg-light">
                                <div class="row ">
                                    <h3>MONEDAS</h3>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="m50">$ 50</label>
                                            <input class="form-control" type="number" min="0"  id="m50" name="m50" step="1" value="0"> 
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="m100">$ 100</label>
                                            <input class="form-control" type="number" min="0"  id="m100" name="m100" step="1" value="0"> 
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="m200">$ 200</label>
                                            <input class="form-control" type="number" min="0"  id="m200" name="m200" step="1" value="0"> 
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="m500">$ 500</label>
                                            <input class="form-control" type="number" min="0"  id="m500" name="m500" step="1" value="0"> 
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="m1000">$ 1.000</label>
                                            <input class="form-control" type="number" min="0"  id="m1000" name="m1000" step="1" value="0"> 
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <h3>BILLETES</h3>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b2000">$ 2.000</label>
                                            <input class="form-control" type="number" min="0"  id="b2000" name="b2000" step="1" value="0"> 
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b5000">$ 5.000</label>
                                            <input class="form-control" type="number" min="0"  id="b5000" name="b5000" step="1" value="0"> 
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b10000">$ 10.000</label>
                                            <input class="form-control" type="number" min="0"  id="b10000" name="b10000" step="1" value="0"> 
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b20000">$ 20.000</label>
                                            <input class="form-control" type="number" min="0"  id="b20000" name="b20000" step="1" value="0"> 
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b50000">$ 50.000</label>
                                            <input class="form-control" type="number" min="0"  id="b50000" name="b50000" step="1" value="0"> 
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b100000">$ 100.000</label>
                                            <input class="form-control" type="number" min="0"  id="b100000" name="b100000" step="1" value="0"> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <h4 class="fs-5"><b>TOTAL:</b><span   id="total_close">0</span></h4>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    //necesito una funcion que me cuente el valor en plata y que me calcule el valor automaticamente
    function calcularTotal() {
        // Definir los valores de cada denominación
        const denominaciones = {
            m50: 50,
            m100: 100,
            m200: 200,
            m500: 500,
            m1000: 1000,
            b2000: 2000,
            b5000: 5000,
            b10000: 10000,
            b20000: 20000,
            b50000: 50000,
            b100000: 100000
        };

        let total = 0;

        // Sumar el valor de cada denominación multiplicado por su cantidad
        for (const [id, valor] of Object.entries(denominaciones)) {
            const cantidad = parseInt(document.getElementById(id).value) || 0;
            total += cantidad * valor;
        }

        // Mostrar el total en el elemento con id="total_close"
        document.getElementById('total_close').textContent = total.toLocaleString();
    }

    // Agregar eventos a todos los inputs para recalcular automáticamente
    document.addEventListener('DOMContentLoaded', function() {
        const ids = [
            'm50', 'm100', 'm200', 'm500', 'm1000',
            'b2000', 'b5000', 'b10000', 'b20000', 'b50000', 'b100000'
        ];
        ids.forEach(function(id) {
            document.getElementById(id).addEventListener('input', calcularTotal);
        });
        calcularTotal(); // Calcular al cargar la página
    });
</script>
@endsection

