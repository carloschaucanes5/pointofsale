<style>
    .content-photo{
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-content: center;
    }

    .content-photo img{
        max-width:100%;
        max-height:100%;
    }
</style>
<div class="modal fade" id="modal-output-{{$incd->id}}">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title">Salida de Producto</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="form-out-{{$incd->id}}">
                               @csrf
                                <div class="form-control">
                                    <label><b>CODIGO DE BARRAS: </b></label>
                                    <label>{{$incd->code}}</label>
                                </div>
                                <div class="form-control">
                                    <label><b>PRODUCTO: </b></label>
                                    <label>{{$incd->name}} {{$incd->concentration}} {{$incd->presentation}}</label>
                                </div>
                                <div class="form-control">
                                    <label><b>LABORATORIO: </b></label>
                                    <label>{{$incd->laboratory}}</label>
                                </div>
                                <div class="form-control">
                                    <label for="quantity-out"><b>Cantidad: </b></label><br/>
                                    <input type="number" min="1" value="{{$incd->quantity}}" id="quantity-out-{{$incd->id}}" name="quantity-out-{{$incd->id}}" required>
                                </div>
                                <div class="form-control">
                                    <label for="description-out" ><b>Motivo </b></label><br/>
                                    <textarea cols="50" id="description-out-{{$incd->id}}" name="description-out-{{$incd->id}}"></textarea>
                                </div>
                        </form>
                    </div>        
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btn_save_out_{{$incd->id}}">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Evento click para el botón de cada formulario
        document.getElementById('btn_save_out_{{$incd->id}}').addEventListener('click', async function () {
            try {
                showSpinner();
                let form = document.getElementById("form-out-{{$incd->id}}");
                let formData = new FormData(form);
                let token = form._token.value;
                const response = await fetch("{{ route('store.inventory.proccess_out', $incd->id) }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": token,
                        "Accept": "application/json"
                    }
                });
                const result = await response.json(); // Asegura que la respuesta es JSON
                if (response.ok) {
                    if(result.success){
                        Swal.fire({
                            "success":true,
                            "text":result.message,
                            "title":"Actualizacion correcta",
                            "icon":"success",
                            "timer":4000
                        });
                        window.location.reload();
                    }
                    else
                    {
                        Swal.fire({
                            "success":false,
                            "text":result.message,
                            "title":"Error de Validación",
                            "icon":"error",
                            "timer":4000
                        });
                    }
                    // Aquí podrías mostrar Swal o actualizar parte del HTML
                } else {
                    Swal.fire(
                        {
                            "success":false,
                            "text":"Error de validación",
                            "title":"Error de actualización",
                            "icon":"warning",
                            "timer":4000
                        }
                    )
                    console.log(result);
                    // Muestra los mensajes de error si los hay
                }
            } catch (err) {
                console.error("Error inesperado:", err);
            }finally{
                hideSpinner();
            }
        });
    });
</script>  


  
 