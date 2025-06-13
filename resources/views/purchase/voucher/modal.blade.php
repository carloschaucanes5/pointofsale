

<style>
  .custom-modal-dialog {
      max-width: 90vw; /* Adjust the width as needed */
      margin: 0 auto; /* Center the modal */
  }
  .custom-modal-dialog .modal-content {
      width: 100%; /* Ensure the content takes full width */
  }
  /*responsive adjustments*/
  @media (max-width: 768px) {
      .custom-modal-dialog {
          max-width: 100vw; /* Full width on smaller screens */
      }
      .custom-modal-dialog .modal-content {
          width: 100%; /* Ensure the content takes full width */
      }
  }
</style>
<div class="modal fade" id="modal-delete-{{$vou->id}}">
    <div class="modal-dialog">
        <form action="{{route('voucher.destroy',$vou->id)}}" method="POST">
        @csrf
        @method("DELETE")
        <div id="modal_contenido" class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Factura</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Deseas eliminar la factura {{$vou->voucher_number}}?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-outline-light">Eliminar</button>
            </div>
        </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal-view-{{$vou->id}}">
  <div class="modal-dialog modal-dialog-centered custom-modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Factura No. {{$vou->voucher_number}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="voucher-photo-{{$vou->id}}" src="{{ asset($vou->photo) }}" style="max-width: 100vw; height: auto;" />
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
      @foreach ($vouchers as $vou)
          const modal = document.getElementById('modal-view-{{$vou->id}}');
          modal.addEventListener('show.bs.modal', function () {
              const img = document.getElementById('voucher-photo-{{$vou->id}}');
              const originalSrc = img.getAttribute('src').split('?')[0]; // evitar duplicar ?t=
              const timestamp = new Date().getTime();
              img.src = originalSrc + '?t=' + timestamp;
          });
      @endforeach
  });
</script>
 