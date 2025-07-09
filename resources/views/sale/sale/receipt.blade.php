<style>
    .mb-custom {
        margin-bottom: 0.25rem !important;
    }
    #content-receipt{
        width: 95%;
        margin: 0 auto;
        height: 50vh;
        overflow-x:hidden;
        overflow-y: auto;
        font-family: Verdana, Geneva, Tahoma, sans-serif !important;
        font-size: 12px !important;
    }

    .noprint {
        display: none;
    }

</style>
<div class="modal fade" id="modal-receipt-invoice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title">Factura de Venta</h5>
                <!-- Botón con X para cerrar -->
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="content-receipt">
                <div class="row ">
                    <div class="col-md-12">
                        <center>
                            <img src="{{ $logo->value }}" alt="Logo" class="img-fluid mb-3" style="max-width: 100px;">
                            <article><i>{{$company['company_slogan']->value}}</i></article>
                            <small class="text-muted mb-custom">{{ $company['company_name']->value }}</small><br>
                            <small class="text-muted mb-custom">{{ $company['company_nit']->alias}}:{{ $company['company_nit']->value}}</small><br>
                            <small class="text-muted mb-custom">{{ $company['company_reguimen']->alias}}:{{ $company['company_reguimen']->value}}</small><br>
                            <small class="text-muted mb-custom">{{ $company['company_address']->value}}</small><br>
                            <small class="text-muted mb-custom">{{ $company['company_municipality']->value}}-{{ $company['company_deparment']->value}}</small><br>
                            <small id="date_sale"></small>
                        </center>
                        <hr>
                        <table>
                            <tr>
                                <td id="information_pos">
                                    <small class="text-muted mb-custom">Factura de Venta: <b>POS</b><b id="sale_number"></b></small><br>
                                    <p id="information_customer"></p>
                                </td>
                                <td style="display:flex;flex-direction:column;justify-content:end" >
                                    <strong>Atendido Por</strong><br><label id="employee"></label>
                                </td>
                            </tr>
                        </table>
                    </div>        
                </div>
                <div class="row">
                    <div class="col-md-12" id="details">
                        <hr>
                        <table>
                            <thead>
                                <tr>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-center">Descripción.</th>
                                    <th class="text-center">Des.</th>
                                    <th class="text-center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table style="with:100%;justify-content:end">
                            <tr>
                                <td>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <th>Forma Pago:</th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td id="info_form_payment"></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="table_method_payment">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Medio de Pago:</th>
                                                <th class="text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </td>
                                <td>
                                    <table>
                                        <tr><th>Subtotal:</th><td><label id="receipt_subtotal"></label></td></tr>
                                        <tr><th>(-)Descuento:</th><td><label id="receipt_discount"></label></td></tr>
                                        <tr><th>(+)Imp:</th><td><label id="receipt_tax">0</label></td></tr>
                                        <tr><th>total:</th><td><label id="receipt_total"></label></td></tr>
                                        <tr><th>Recibido:</th><td><label id="receipt_received"></label></td></tr>
                                        <tr><th>Cambio:</th><td><label id="receipt_change"></label></td></tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <hr>
                        <center> 
                            <small class="text-muted mb-custom"><strong>...! GRACIAS POR SU COMPRA ¡...</strong></small><br>
                            <small class="text-muted mb-custom text-center">{{$company["company_timetable"]->alias}}:{!!$company["company_timetable"]->value!!}</small><br>
                            <small class="text-muted mb-custom text-center">{!!$company["company_information"]->value!!}</small><br> 
                        </center>
                    </div>
                </div>
    </div>
    <div class="modal-footer justify-content-center">
        <button type="button" data-dismiss="modal"  class="btn btn-warning" >Cerrar</button>
        <button type="button" id="btn_print" onclick="printDiv('content-receipt')" class="btn btn-success" >Imprimir</button>
    </div>
</div>
