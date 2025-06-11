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
                <div class="form-group">
                    <label>Capturar foto</label>
                    <video id="video" width="100%" autoplay></video>
                    <canvas id="canvas" style="display: none;"></canvas>
                    <img id="preview" class="img-fluid mt-2" style="display: none;" />
                    <button type="button" class="btn btn-sm btn-info mt-2" id="capture">Capturar</button>
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

    // Capturar foto
    captureButton.addEventListener('click', () => {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        preview.src = canvas.toDataURL('image/png');
        preview.style.display = 'block';
    });

    // Enviar formulario con imagen
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append('voucher_number', document.getElementById('voucher_number').value);
        formData.append('total', document.getElementById('total').value);
        formData.append('description', document.getElementById('description').value);

        // Convertir canvas a blob
        canvas.toBlob(function(blob) {
            if (blob) {
                formData.append('photo', blob, 'captured.png');
            }

            fetch("{{ route('voucher.store') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert('Factura guardada correctamente');
                location.reload();
            })
            .catch(error => {
                console.error(error);
                alert('Error al guardar');
            });
        }, 'image/png');
    });
</script>
@endsection