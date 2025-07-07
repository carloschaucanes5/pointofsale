<style>
    .mb-custom {
        margin-bottom: 0.25rem;
    }
    #content-receipt{
        width: 95%;
        margin: 0 auto;
        height: 50vh;
        overflow-x:hidden;
        overflow-y: auto;
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
                            <div class="justify-content-center text-center">
                                <img src="{{ $logo->value }}" alt="Logo" class="img-fluid mb-3" style="max-width: 100px;">
                                <article><i>{{$company['company_slogan']->value}}</i></article>
                                <small class="text-muted mb-custom">{{ $company['company_name']->value }}</small><br>
                                <small class="text-muted mb-custom">{{ $company['company_nit']->alias}}:{{ $company['company_nit']->value}}</small><br>
                                <small class="text-muted mb-custom">{{ $company['company_reguimen']->alias}}:{{ $company['company_reguimen']->value}}</small><br>
                                <small class="text-muted mb-custom">{{ $company['company_address']->value}}</small><br>
                                <small class="text-muted mb-custom">{{ $company['company_municipality']->value}}-{{ $company['company_deparment']->value}}</small><br>
                                <small id="date_sale"></small>
                            </div>
                            <div class="row">
                                <div class="col-md-12" id="information_pos">
                                    <small class="text-muted mb-custom">Factura de Venta: <b>POS</b><b id="sale_number"></b></small><br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8" id="information_customer"></div>
                                <div class="col-md-4" id="">
                                    <strong>Atendido Por</strong><label id="employee"></label>
                                </div>
                            </div>
                    </div>        
                </div>
                <div class="row">
                    <div class="col-md-12" id="details">
                        <table>
                            <thead>
                                <tr style="border-bottom: 1px solid;">
                                    <th class="text-center">Cant.</th>
                                    <th class="text-center">Descripción.</th>
                                    <th class="text-center">Des.</th>
                                    <th class="text-center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">%imp</th>
                                    <th class="text-center">Base imp.</th>
                                    <th class="text-center">Impuesto.</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <table id="table_form_payment">
                            <thead>
                                <tr>
                                    <th class="text-center">Forma de Pago</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table>
                            <tr><th>SUBTOTAL:</th><td><label id="receipt_subtotal"></label></td></tr>
                            <tr><th>(-)Descuento:</th><td><label id="receipt_discount"></label></td></tr>
                            <tr><th>(+)Imp:</th><td><label id="receipt_tax">0</label></td></tr>
                            <tr><th>TOTAL:</th><td><label id="receipt_total"></label></td></tr>
                            <tr><th>RECIBIDO:</th><td><label id="receipt_received"></label></td></tr>
                            <tr><th>CAMBIO:</th><td><label id="receipt_change"></label></td></tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <small class="text-muted mb-custom">Gracias por su compra</small><br>
                        <small class="text-muted mb-custom text-center">{{$company["company_timetable"]->alias}}:{{$company["company_timetable"]->value}}</small><br>
                        <small class="text-muted mb-custom text-center">{{$company["company_information"]->value}}</small><br> 
                    </div>
                </div>
    </div>
    <div class="modal-footer justify-content-center">
        <button type="button" data-dismiss="modal"  class="btn btn-warning" >Cerrar</button>
        <button type="button" onclick="printInvoice('content-receipt')" class="btn btn-success" >Imprimir</button>
    </div>
</div>
