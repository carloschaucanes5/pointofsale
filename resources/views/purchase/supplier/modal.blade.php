<div class="modal fade" id="modal-delete-{{$sup->id}}">
    <div class="modal-dialog">
        <form action="{{route('supplier.destroy',$sup->id)}}" method="POST">
        @csrf
        @method("DELETE")
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Proveedor</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Deseas eliminar el proveedor {{$sup->name}}?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-outline-light">Eliminar</button>
            </div>
        </div>
        </form>
    </div>
</div>

  
 