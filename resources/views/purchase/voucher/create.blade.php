@extends('layouts.admin')

@section('title', 'Crear Factura')

@section('content')
<div class="col-md-6">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Nueva Factura</h3>
        </div>

        <form id="voucherForm" class="form">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="supplier_id">Proveedor</label>
                    <select name="supplier_id" id="supplier_id" class="form-control">
                        @foreach ($suppliers as $per)
                        <option value="{{$per->id}}">{{$per->name}}</option>   
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="status_payment">Estado Pago</label>
                    <select name="status_payment" id="status_payment" class="form-control">
                        @foreach ($status_payment as $pay)
                        <option value="{{$pay}}">{{$pay}}</option>   
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="voucher_number">Numero de Factura</label>
                    <input type="text" class="form-control" name="voucher_number" id="voucher_number" required>
                </div>
                <div class="form-group">
                    <label for="total">Valor</label>
                    <input type="number" step="0.001" class="form-control" name="total" id="total" required>
                </div>
                <div class="form-group">
                    <label for="description">Descripcion</label>
                    <textarea rows="3" class="form-control" name="description" id="description"></textarea>
                </div>

                <!-- Webcam Section -->

                <div class="form-group" style="display: flex; justify-content: center;flex-direction: column; align-items: center;">
                    <label for="photo">Foto de la Factura</label>
                    <video id="video" class="col-md-12" width="auto" autoplay></video>
                    <canvas  id="canvas" style="display: none;"></canvas>
                    <img id="preview" class="img-fluid mt-2" style="display: none;" />
                    <input type="hidden" name="photo" id="photo" value="0">
                    <p>
                        <button type="button" class="btn btn-sm btn-info mt-2" id="capture"><i class="bi bi-camera"></i></button>
                        <button type="button" class="btn btn-sm btn-danger mt-2" id="clear_capture"><i class="bi bi-trash"></i></button>
                    </p>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success me-1 mb-1">Guardar</button>
                <button type="reset" class="btn btn-danger me-1 mb-1">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const preview = document.getElementById('preview');
    const captureButton = document.getElementById('capture');
    const form = document.getElementById('voucherForm');

    // Iniciar cámara
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(err => {
            console.error("No se pudo acceder a la cámara", err);
        });

    // Limpiar captura
    document.getElementById('clear_capture').addEventListener('click', () => {
        const context = canvas.getContext('2d');
        context.clearRect(0, 0, canvas.width, canvas.height);
        preview.src = '';
        preview.style.display = 'none';
        document.getElementById('photo').value = 0; // Resetear valor de foto
    });
    // Capturar foto
    captureButton.addEventListener('click', () => {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        preview.src = canvas.toDataURL('image/png');
        preview.style.display = 'block';
        document.getElementById('photo').value = 1; // Indicar que se ha capturado una foto
    });

    // Enviar formulario con imagen
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData();
        formData.append('voucher_number', document.getElementById('voucher_number').value);
        formData.append('total', document.getElementById('total').value);
        formData.append('description', document.getElementById('description').value);
        formData.append('supplier_id', document.getElementById('supplier_id').value);
        formData.append('status_payment', document.getElementById('status_payment').value);
        // Convertir canvas a blob
        canvas.toBlob(function(blob) {
            const photoInput = document.getElementById('photo');
            if (blob && photoInput.value == 1) {
                formData.append('photo', blob, 'captured.png'); 
                showSpinner();
                fetch("{{ route('voucher.store') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    hideSpinner();
                    if(data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: data.message,
                            timer: 3000,
                        }).then(() => {
                            window.location.href = "{{route('voucher.index')}}";
                        });
                    }else {
                        Swal.fire({
                            title: 'Error',
                            text: 'No se pudo guardar la factura',
                            icon: 'error',
                            buttons: true,
                            dangerMode: true,
                            timer: 3000
                        });
                    }
                    //location.reload();
                })
                .catch(error => {
                    hideSpinner();
                    console.log(error);
                    alert('Error al guardar');
                });
        }
        else
        {
            Swal.fire({
                title: 'Error',
                text: 'No hay imagen capturada',
                icon: 'error',
                buttons: true,
                dangerMode: true,
                timer: 3000
            }).then(() => {
                document.getElementById('photo').value = 0; // Resetear valor de foto
            });
        }
        }, 'image/png');
    });
</script>
@endsection