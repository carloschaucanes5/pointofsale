@extends('layouts.admin')

@section('title', 'Crear Producto')

@section('content')
    <div class="col-md-7">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Nuevo Producto</h3>
            </div>
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" class="form">
    @csrf
    <div class="card-body">
        <div class="row">
            <!-- Campo Código -->
            <div class="col-12">
                <div class="form-group">
                    <label for="code">Código</label>
                    <input type="text" class="form-control" name="code" id="code" placeholder="Código de Barras" value="{{ old('code') }}">
                    @error('code')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Campo Nombre -->
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Ingresar el nombre del producto" value="{{ old('name') }}">
                    @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Campo Concentración -->
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="concentration">Concentración</label>
                    <input type="text" class="form-control" name="concentration" id="concentration" placeholder="Ingresar la concentración" value="{{ old('concentration') }}">
                    @error('concentration')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Campo Presentación -->
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="presentation">Presentación</label>
                    <input type="text" class="form-control" name="presentation" id="presentation" placeholder="Ingresar la presentación" value="{{ old('presentation') }}">
                    @error('presentation')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Campo Laboratorio -->
            <div class="col-md-6 col-12">
                <div class="row">
                    <div class="col-md-8 col-12">
                        <div class="form-group">
                            <label for="laboratory">Laboratorio</label>
                            <input type="text" class="form-control" name="laboratory" id="laboratory" list="list-laboratories" placeholder="Ingresar el laboratorio" value="{{ old('laboratory') }}" autocomplete="off">
                            <div id="container-laboratories">
                                <datalist id="list-laboratories">
                                    @foreach ($laboratories as $lab)
                                        <option>{{ $lab->name }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <br/>
                            <input type="button" class="btn btn-info"  value="Nuevo" onclick="clearContainer()" data-bs-toggle="modal" data-bs-target="#modal-new-laboratory"/>
                        </div>
                    </div>
              </div>
              @error('laboratory')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
            </div>   
            

            <!-- Campo Categoría -->
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="category_id">Categoría</label>
                    <select name="category_id" class="form-control" id="category_id">
                        @foreach($category as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->category }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Campo Stock -->
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" class="form-control" name="stock" id="stock" placeholder="Ingresar la cantidad del producto" value="{{ old('stock') }}">
                    @error('stock')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Campo Descripción -->
        <div class="col-12">
            <div class="form-group">
                <label for="description">Descripción</label>
                <textarea class="form-control" name="description" id="description" cols="100" placeholder="Ingresar la descripción">{{ old('description') }}</textarea>
                @error('description')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Campo Imagen -->
        <div class="col-md-6 col-12">
    <div class="form-group">
        <label for="image">Imagen</label>
        <input type="file" class="form-control" name="image" id="image" accept="image/*">
        <button type="button" id="captureButton" class="btn btn-secondary" style="margin-top: 10px;">Capturar Imagen con la Cámara</button>
        
        <!-- Contenedor de la cámara -->
        <div id="cameraContainer" style="display:none;">
            <video id="video" width="100%" autoplay></video>
            <canvas id="canvas" style="display:none;"></canvas>
            <img id="capturedImage" style="display:none; max-width: 100%; margin-top: 10px;" />
            <input type="hidden" name="captured_image" id="capturedImageInput">
        </div>

        @error('image')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-success me-1 mb-1">Guardar</button>
        <button type="reset" class="btn btn-danger me-1 mb-1">Cancelar</button>
    </div>

</form>
        </div>
    </div>
@endsection

@include("store.laboratory.modal")
<script>
document.getElementById("captureButton").addEventListener("click", function () {
    const canvas = document.getElementById("canvas");
    const video = document.getElementById("video");
    const context = canvas.getContext("2d");

    // Dibujar la imagen actual del video en el canvas
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0);

    // Obtener imagen en base64
    const imageData = canvas.toDataURL("image/jpeg");

    // Asignar al input hidden
    document.getElementById("capturedImageInput").value = imageData;

    // Mostrar imagen capturada (opcional)
    document.getElementById("capturedImage").src = imageData;
    document.getElementById("capturedImage").style.display = 'block';
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', e => {
        $('#laboratory').autocomplete()
    }, false);

    function clearContainer(){
        const responseMessage = document.getElementById('responseMessage');
        const errorContainer = document.getElementById('errorContainer');
        responseMessage.innerHTML = '';
        errorContainer.innerHTML = ''; 
    }
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('image');
    const captureButton = document.getElementById('captureButton');
    const cameraContainer = document.getElementById('cameraContainer');
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');
    const capturedImage = document.getElementById('capturedImage');
    const imageInput = document.createElement('input');
    imageInput.type = 'hidden';
    imageInput.name = 'image_base64'; // Este será el campo que contenga la imagen en base64

    // Cuando se selecciona un archivo, se oculta la cámara
    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            cameraContainer.style.display = 'none';
        }
    });

    // Cuando el usuario haga clic en el botón de capturar, mostrar la cámara
    captureButton.addEventListener('click', function () {
        cameraContainer.style.display = 'block';
        startCamera();
    });

    // Función para comenzar la cámara
    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function (stream) {
                video.srcObject = stream;
            })
            .catch(function (err) {
                console.log("Error al acceder a la cámara: ", err);
            });
    }

    // Capturar imagen cuando se haga clic en el video
    video.addEventListener('click', function () {
        // Establecer las dimensiones del canvas al tamaño del video
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        // Dibujar la imagen del video en el canvas
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Convertir la imagen capturada a base64
        const imageData = canvas.toDataURL('image/png');
        capturedImage.src = imageData;
        capturedImage.style.display = 'block';

        // Asignar la imagen base64 al campo oculto
        imageInput.value = imageData;

        // Agregar el campo base64 al formulario
        document.forms[0].appendChild(imageInput);

        // Detener la cámara después de capturar la imagen
        const stream = video.srcObject;
        const tracks = stream.getTracks();
        tracks.forEach(track => track.stop());
    });

});
</script>


