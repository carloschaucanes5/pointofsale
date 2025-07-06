
<div class="modal fade" id="modal-set-quantity">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header"></div>
            <div class="modal-body"></div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-success" onclick="add_item_selected()" data-dismiss="modal">Aplicar</button>
                <h6 class="subtotalItem"></h6>
            </div>
        </div>
    </div>
</div>
@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Modal de cantidad listo");
        });
    </script>
@endsection


