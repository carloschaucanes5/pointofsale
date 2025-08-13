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

            <table class="table table-bordered table-striped fs-6 no-padding">
                <thead>
                    <tr>
                        <th colspan="6" class="text-center">Movimientos Caja Registradora {{$cash_opening->user_name}}</th>
                    </tr>
                </thead>
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
                <th>Total Turno</th>
                @php
                    $payTotal = 0;
                @endphp
                @foreach($payment_methods as $method)
                    @php
                        $b = 0;
                        $pay = 0;
                    @endphp
                    @foreach($totals as $tot)
                        @if($tot->payment_method == $method)
                            @php 
                                $b = 1;
                                $method_selected = $method;
                                if($method=='efectivo'){
                                    $pay = $tot->total + $cash_opening->start_amount;
                                    $payTotal = $tot->total;
                                }else{
                                    $pay = $tot->total;
                                }
                            @endphp
                        @endif
                    @endforeach
                    @if($b==1)
                        @if($method == 'efectivo')
                            <td class="bg-warning">{{number_format($pay,0,',','.')}}</td>
                        @else
                            @php
                                
                            @endphp
                            <td>{{number_format($pay,0,',','.')}}</td>
                        @endif
                    @else
                        <td></td>
                    @endif
                @endforeach
                </tr>
                <tr>
                    <th>Total a Entregar</th><th colspan="5" >{{number_format($payTotal?$payTotal:0,'0',',','.')}}</th>
                <tr>
                    <th colspan="6" class="text-center">&nbsp;</th>
                </tr>
                <tr>
                    <th colspan="6" class="text-center">Movimientos Caja Menor</th>
                </tr>
                <tr>
                    <th>Descripción</th>
                    @foreach($payment_methods as $method)
                        <th>{{$method}}</th>
                    @endforeach
                </tr>
                <tr>
                    <td>Saldo Inicial Caja Menor</td>
                    @foreach($payment_methods as $method)
                        @php
                            $b = 0;
                            $pay = 0;
                        @endphp
                        @foreach($last_balances as $last_balance)
                            @if($last_balance->method == $method)
                                @php 
                                    $b = 1;
                                    $pay = $last_balance->balance;
                                @endphp
                            @endif
                        @endforeach
                        @if($b==1)
                            <td class="bg-warning">{{number_format($pay,0,',','.')}}</td>
                        @endif
                    @endforeach
                </tr>
                <tr>
                    <th colspan="5" ></th>
                </tr>
                @foreach($petty_cash as $mov)
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
                    <td>Total Caja Menor en el Día</td>
                    @foreach($array_totals_movements as $tot)
                        <td>{{$tot->total}}</td>
                    @endforeach
                </tr>
                </tbody>
            </table>
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

