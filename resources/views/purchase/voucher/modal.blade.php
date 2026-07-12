

<style>
    .custom-modal-dialog {
        max-width: 90vw;
    }

    .custom-modal-dialog .modal-body {
        max-height: 75vh;
        overflow-y: auto;
    }

    .custom-modal-dialog .modal-body img {
        width: 100%;
        max-width: 900px;
        height: auto;
        margin-bottom: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,.2);
    }

    @media (max-width: 768px) {
        .custom-modal-dialog {
            max-width: 100vw;
        }
    }

    .modal-header{
    cursor: move;
    }
</style>
<div class="modal fade" id="modal-view-{{$vou->id}}">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable custom-modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">
                    Factura No. {{$vou->voucher_number}}
                </h4>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body text-center">

                @foreach(explode(',', $vou->photo) as $photo)

                    <img
                        src="{{ asset($photo) }}?t={{ time() }}"
                        class="img-fluid"
                        alt="Factura">

                @endforeach

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modal-view-{{$vou->id}}">
  <div class="modal-dialog modal-dialog-centered custom-modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Factura No. {{$vou->voucher_number}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      @foreach (explode(",",$vou->photo) as $photo)
        <div class="modal-body text-center">
            <img id="voucher-photo-{{$loop->iteration}}" src="{{ asset($photo) }}" style="max-width: 100vw; height: auto;" />
        </div>
      @endforeach
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<script>
document.querySelectorAll('.modal').forEach(function(modal){

    const dialog = modal.querySelector('.modal-dialog');
    const header = modal.querySelector('.modal-header');

    let isDragging = false;
    let offsetX = 0;
    let offsetY = 0;

    header.addEventListener('mousedown', function(e){

        isDragging = true;

        const rect = dialog.getBoundingClientRect();

        offsetX = e.clientX - rect.left;
        offsetY = e.clientY - rect.top;

        dialog.style.margin = "0";
        dialog.style.position = "fixed";
        dialog.style.zIndex = "1055";
    });

    document.addEventListener('mousemove', function(e){

        if(!isDragging) return;

        dialog.style.left = (e.clientX - offsetX) + "px";
        dialog.style.top = (e.clientY - offsetY) + "px";

    });

    document.addEventListener('mouseup', function(){

        isDragging = false;

    });

    modal.addEventListener('hidden.bs.modal', function(){

        dialog.style.left = "";
        dialog.style.top = "";
        dialog.style.position = "";
        dialog.style.margin = "";
    });

});
</script>
 