
<div class="modal fade" id="modal-return-{{$det->income_detail_id}}" tabindex="-1" aria-labelledby="modalDevolucionLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDevolucionLabel">Confirmar Devolución</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <form id="form-return-{{$det->income_detail_id}}">
                @csrf
            <div class="col-md-12">
                    <div class="form-group">
                        <label for="product_name"><b>Producto</b></label>
                        <label id="product_name" class="form-control">{{$det->article}} {{$det->concentration}} {{$det->presentation}}</label>
                        <input type="hidden" name="income_detail_id" id="income_detail_id" value="{{$det->income_detail_id}}">
                        <input type="hidden" name="sale_id" id="sale_id" value="{{$sale->id}}">
                    </div>
                    <div class="form-group">
                        <label for="laboratory"><b>Laboratorio</b></label>
                        <label id="laboratory" class="form-control">{{$det->laboratory}}<label>
                    </div>
                    <div class="form-group">
                        <label for="quantity_return"><b>Cantidad</b></label>
                        <input type="number" class="form-control" min="1" max="{{$det->quantity}}" onblur="validate_quantity(this)" id="quantity_return" name="quantity_return" value="{{$det->quantity}}">
                    </div>
                    <div class="form-group">
                        <label for="description_return"><b>Descripción</b></label>
                        <textarea  id="description_return" class="form-control"  rows="5" cols="40" name="description_return"></textarea>
                    </div>  
                </div>
            </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-warning" onclick="make_return(event,{{$det->income_detail_id}})">Confirmar</button>
      </div>
    </div>
  </div>
</div>
<script>

    function validate_quantity(input){
        if (!/^\d*$/.test(input.value)){
            input.value = input.min;  
        }else{
            if(!(parseInt(input.value) <=parseInt(input.max) && parseInt(input.value) >= parseInt(input.min))){
                input.value = input.min;  
            }
            else{
                document.getElementById("quantity_return").value = input.value;
            }
        } 
    }

    async function  make_return(event,income_detail_id){
        event.preventDefault();
        showSpinner();
        try{
            const formreturn = document.getElementById(`form-return-${income_detail_id}`);
            const form = new FormData(formreturn);
            const res = await fetch("{{route('sale.sale.return_sale')}}",{
                method:'POST',
                headers:{
                    'X-CSRF-TOKEN':'{{csrf_token()}}'
                },
                body:form
            });
            const data = await res.json();
            if(data.success){
                Swal.fire({
                    'icon':'success',
                    'title':'Devolución',
                    'text':data.message,
                    'timer':2000
                }).then(()=>{
                    document.getElementById('description_return').value = "";
                    window.location.reload();
                });
            }else{
                Swal.fire({
                    'icon':'warning',
                    'title':'Devolución',
                    'text':data.message
                })
            }
        }catch(error){
                Swal.fire({
                    'icon':'error',
                    'title':'Devolución',
                    'text': error
                });
        }finally{
             hideSpinner();
        }
    }
</script>



