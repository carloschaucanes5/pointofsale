<style>
    .content-photo{
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-content: center;
    }

    .content-photo img{
        max-width: 450px;
        max-height:300px ;
    }
</style>
<div class="modal fade" id="modal-detail-{{$pro->id}}">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title">Información Producto</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="row">   
                        <div class="col-md-12 content-photo">
                            <img  src="{{asset('images/products/'.$pro->image)}}">
                        </div>
                    </div> 
                    <div class="col-md-12">
                        <div class="form-control">
                            <label><b>CODIGO DE BARRAS: </b></label>
                            <label>{{$pro->code}}</label>
                        </div>
                        <div class="form-control">
                            <label><b>PRODUCTO: </b></label>
                            <label>{{$pro->name}} {{$pro->concentration}} {{$pro->presentation}}</label>
                        </div>
                        <div class="form-control">
                            <label><b>LABORATORIO: </b></label>
                            <label>{{$pro->laboratory}}</label>
                        </div>
                        <fieldset>
                            <legend>Descripción</legend>
                            {{$pro->description}}
                        </fieldset>
                    </div>     
                  
                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

  
 