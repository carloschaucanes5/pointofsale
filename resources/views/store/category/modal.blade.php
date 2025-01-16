<div class="modal fade" id="modal-delete-{{$cat->id}}">
    <div class="modal-dialog">
        <form action="{{route('category.destroy',$cat->id)}}" method="POST">
        @csrf
        @method("DELETE")
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar categoria</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Deseas eliminar la categoria {{$cat->category}}?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-outline-light">Eliminar</button>
            </div>
        </div>
        </form>
    </div>
</div>

  
 