@extends('layouts.admin')

@section('title', 'Mostrar Ingreso')
<style>
  .no-padding td,
  .no-padding th {
    padding: 0 !important;
  }
</style>
@section('content')
<div class="container mt-1">
    <div class="row">
        <div class="col-md-12">

          <div class="card text-center"><h6>Movimientos Realizados por {{$cash_opening->username}}</h6></div>
            <table class="table table-bordered table-striped fs-6 no-padding">
                <tbody>
                <tr>
                    <th>Descripción</th>
                    @foreach($payment_methods as $method)
                        <th>{{$method}}</th>
                    @endforeach
                </tr>

                <tr>
                    <th colspan="5" ></th>
                </tr>
                <tr>
                    <td>{{$cash_opening->cash_name}} Inicial</td>
                @foreach($payment_methods as $method)
                    @php
                        $b = 0;
                        $pay = 0;
                    @endphp
                    @foreach($last_balances as $balance)
                        @if($balance->method == $method)
                        @php 
                            $b = 1;
                            $pay = $balance->balance;
                        @endphp
                        @endif
                    @endforeach
                    @if($b==1)
                        <td>{{number_format($pay,0,',','.')}}</td>
                    @else
                        <td></td>
                    @endif
                @endforeach
                </tr> 
                <tr><td class="text-center" colspan="4">------------- o -------------</td><td></td><td></td></tr>
                <tr>
                    @foreach($last_balances as $balance)
                        
                    @endforeach
                </tr>
                <tr>
                    <td>Saldo Apertura({{$cash_opening->created_at}})</td>
                    <td>{{number_format($cash_opening->start_amount,"2",",",".")}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach($movements as $mov)
                @php
                    $bg_cell = "text-success";
                    if($mov->type == "egreso"){
                        $bg_cell = "text-danger";
                    }
                    
                @endphp
                <tr>
                    <td>{{$mov->description}}</td>
                    @foreach($payment_methods as $method)
                        @if($method == $mov->payment_method)
                            <td class="{{$bg_cell}}">{{number_format($mov->amount,2,',','.')}}</td>
                        @else
                            <td></td>
                        @endif
                    @endforeach 
                </tr>
                @endforeach
                <tr>
                <th>Totales Turno</th>
                @foreach($payment_methods as $method)
                    @php
                        $b = 0;
                        $pay = 0;
                        $method_selected = "";
                    @endphp
                    @foreach($totals as $tot)
                        @if($tot->payment_method == $method)
                        @php 
                            $b = 1;
                            $method_selected = $method;
                            $pay = $method=='efectivo'?($tot->total + $cash_opening->start_amount):$tot->total;
                        @endphp
                        @endif
                    @endforeach
                    @if($b==1)
                        @if($method == 'efectivo')
                            <td class="bg-warning">{{number_format($pay,0,',','.')}}</td>
                        @else
                            <td>{{number_format($pay,0,',','.')}}</td>
                        @endif
                    @else
                        <td></td>
                    @endif
                @endforeach
                </tr>
                <tr><td class="text-center" colspan="4">------------- o -------------</td><td></td><td></td></tr>
                <tr>
                    <td>{{$cash_opening->cash_name}} Final</td>
                @foreach($payment_methods as $method)
                    @php
                        $b = 0;
                        $pay = 0;
                    @endphp
                    @foreach($current_balances as $balance)
                        @if($balance->method == $method)
                        @php 
                            $b = 1;
                            $pay = $balance->balance;
                        @endphp
                        @endif
                    @endforeach
                    @if($b==1)
                        <td>{{number_format($pay,0,',','.')}}</td>
                    @else
                        <td></td>
                    @endif
                @endforeach
                </tr> 

                </tbody>
            </table>
            <div class="card text-center"><h6>Arqueo de Caja</h6></div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Denominación</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @foreach ($cash_count as $key => $value)
                            @php
                                $type = $key[0] === 'm' ? 'Moneda' : 'Billete';
                                $denomination = (int) substr($key, 1);
                                $subtotal = $denomination * $value;
                                $total += $subtotal;
                            @endphp
                            @if ($value > 0)
                            <tr>
                                <td>{{ $type }} de ${{ number_format($denomination, 0, ',', '.') }}</td>
                                <td>{{ $value }}</td>
                                <td>${{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-success fw-bold">
                            <td colspan="2">Total Arqueo</td>
                            <td>${{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    //necesito una funcion que me cuente el valor en plata y que me calcule el valor automaticamente
    document.addEventListener('DOMContentLoaded',function(){
       
        const form = document.getElementById('form_cash_close');
        form.addEventListener('submit',function(e){
            e.preventDefault();
            let spare = parseFloat(document.getElementById('amount_incomplete').textContent);
            if(spare > 0){
                Swal.fire({
                    'icon':'warning',
                    'text':'Le sobra ' + spare
                });
            }else if(spare < 0){
                Swal.fire({
                    'icon':'warning',
                    'text':'Le falta ' + spare
                });
            }else{
                this.submit();
            }
        });
    });


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
        
        let total_movement_cash = parseFloat(document.getElementById('total_cash').value);
        let total_cash =  total_movement_cash + parseFloat({{($cash_opening->start_amount)}});
        // Mostrar el total en el elemento con id="total_close"
        document.getElementById('total_close').textContent = total.toLocaleString();
        document.getElementById('total_close_amount').value = total;
        let incomplete = total-total_cash;
        let text_incomplete = document.getElementById('text_incomplete');
        let amount_incomplete = document.getElementById('amount_incomplete');
        text_incomplete.className = "";
        if(incomplete < 0){
            text_incomplete.textContent = 'falta';
            text_incomplete.classList.add('text-danger');
        }else if(incomplete > 0){
            text_incomplete.textContent = "Sobra";
            text_incomplete.classList.add('text-warning');
        }else{
            text_incomplete.textContent = "Completo";
            text_incomplete.classList.add('text-success');
        }
        amount_incomplete.textContent = incomplete; 
    }

    // Agregar eventos a todos los inputs para recalcular automáticamentes
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

