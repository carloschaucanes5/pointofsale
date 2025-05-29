<div class="modal fade" id="modal-new-laboratory">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="laboratoryForm">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Crear Laboratorio</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="container modal-body">
                    <div class="form-group">
                        <label for="name">Nombre laboratorio</label>
                        <input type="text" class="form-control" name="name" id="name" value="" autocomplete="off">
                        <div id="error-name" class="alert alert-danger d-none"></div> <!-- Error dinámico -->
                    </div>
                    <div id="responseMessage"></div> 
                    <div id="errorContainer"></div> <!-- Mensaje de respuesta general -->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

   document.getElementById('laboratoryForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const responseMessage = document.getElementById('responseMessage');
    const errorContainer = document.getElementById('errorContainer');
    responseMessage.innerHTML = '';
    errorContainer.innerHTML = ''; 
    let formData = new FormData(form);
    const csrfToken = form.querySelector('input[name="_token"]').value;
    try {
        const response = await fetch("{{ route('laboratory.store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            }
        });
        const data = await response.json();
        if (data.success) {
            const listLaboratories = document.getElementById('list-laboratories');
            const option = document.createElement("option");
            option.value = data.data.name;
            listLaboratories.appendChild(option);
            responseMessage.innerHTML = `<div class="alert alert-success">¡Información guardada correctamente!</div>`;
            form.reset(); 
        } else {
            if (data.errors) {
                errorContainer.innerHTML = ''; 
                Object.keys(data.errors).forEach(field => {
                    const errorMessages = data.errors[field].join(', ');
                    const errorElement = document.createElement('div');
                    errorElement.classList.add("alert","alert-warning");
                    errorElement.innerHTML = `${errorMessages}`;
                    errorContainer.appendChild(errorElement);
                });
            }else{
                responseMessage.innerHTML = `<div class="alert alert-danger">Ocurrió un error al guardar la información.</div>`;
            }
        }
    } catch (error) {
        console.error('Error:', error);
        responseMessage.innerHTML = `<div class="alert alert-danger">Ocurrió un error inesperado. Ver consola para detalles.</div>`;
    }
});
</script>

