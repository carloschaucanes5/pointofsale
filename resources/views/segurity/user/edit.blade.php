@extends('layouts.admin')

@section('title', 'Actualizar Usuario')

@section('content')
  <div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Actualizar Usuario {{$user->name}}</h3>
            </div>
            <form method="POST" class="form" id="form_user">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @php
                        $readonly = "readonly";
                        if(Auth::user()->role == $user->role){
                            $readonly = "";
                        }
                    @endphp
                    <div class="form-group">
                        <label for="name">Nombre Usuario</label>
                        <input type="text" class="form-control" {{$readonly}} name="name" value="{{$user->name}}" id="name" placeholder="Ingresar el nombre del Usuario">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" {{$readonly}} name="email" value="{{$user->email}}" id="email" placeholder="Ingresar Correo">
                    </div>
                     <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" {{$readonly}} name="password" id="password" placeholder="Ingresar la contraseña">
                    </div>
                    <div class="form-group">
                        <label for="confirm">Confirmar</label>
                        <input type="confirm" class="form-control" {{$readonly}} name="confirm" id="confirm" placeholder="Confirmar la contraseña">
                    </div>
                    <div>
                    <div class="form-group">
                        <label>Rol</label>
                        <select class="form-control" name="role" id="role" {{$readonly}}>
                            @if($user->role == "superadmin")
                                <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                            @else
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="cashier" {{ $user->role == 'cashier' ? 'selected' : '' }}>Cajero</option>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Usuario</option>
                            @endif

                        </select>
                    </div>
                    </div>

                </div>
                @if(Auth::user()->role == $user->role)
                    <div class="card-footer">
                        <button type="button"  class="btn btn-success me-1 mb-1" onclick="update_user()">Guardar</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
   <div class="col-md-6">
            <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Información Adicional del Usuario</h3>
            </div>
            <form  id="form_person" method="POST" class="form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="document_type">Tipo de Documento</label>
                        <select name="document_type" id="document_type" value="{{$person->document_type}}" class="form-control" {{$readonly}}>
                            <option value="CC">Cédula de Ciudadabia</option>
                            <option value="TI">Tarjeta de identidad</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="document_number">Número de documento</label>
                        <input type="text" class="form-control" {{$readonly}} name="document_number" id="document_number" value="{{$person->document_number}}" placeholder="Ingresar el número de identificación">
                    </div>
                    <div class="form-group">
                        <label for="name">Nombres y Apellidos</label>
                        <input type="text" class="form-control" {{$readonly}} name="p_name" id="p_name" value="{{$person->name}}" placeholder="Ingresar el nombre">
                    </div>
                    <div class="form-group">
                        <label for="address">Dirección</label>
                        <input type="text" class="form-control" {{$readonly}} name="address" id="address" value="{{$person->address}}" placeholder="Ingresar la dirección">
                    </div>
                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <input type="text" class="form-control" {{$readonly}} name="phone" id="phone" value="{{$person->phone}}" placeholder="Ingresar el teléfono">
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="text" class="form-control" {{$readonly}} name="p_email" id="p_email" value="{{$person->email}}" placeholder="Ingresar el correo electrónico">
                    </div>
                </div>
                @if(Auth::user()->role == $user->role)
                    <div class="card-footer">
                        <button type="button" class="btn btn-success me-1 mb-1" onclick="update_person()">Guardar</button>
                    </div>
                @endif
            </form>
        </div>
    </div> 
</div>
@endsection
<script>
    async function update_user() {
        const form = document.getElementById("form_user");
        const nameRegex = /^[a-zA-Z0-9]+$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        // Validar nombre
        if (!nameRegex.test(form.name.value)) {
            Swal.fire({
                icon:"warning",
                text:"El nombre de usuario solo debe contener letras y números sin espacios."
            });
            return;
        }
        // Validar email
        if (!emailRegex.test(form.email.value)) {
                Swal.fire({
                icon:"warning",
                text:"Ingrese un correo válido."
            });
            return;
        }
        // Validar que las contraseñas coincidan (si están habilitadas)
        if (form.password.value !== '' || form.confirm.value !== '') {
            if (form.password.value  !== form.confirm.value) {
                Swal.fire({
                    icon:"warning",
                    text:"Las contraseñas no coinciden."
                });
            return;
            }
            
        }else{
            Swal.fire({
                icon:"warning",
                text:"Las contraseñas no deben tener espacios vacíos"
            });
            return;
        }

        const formData = new FormData(form);
        const url = "{{route('user.update',[$user->id])}}";
        showSpinner();
        const response = await fetch(url,{
            method:'post',
            headers:{
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
            body:formData
        });
        const data = await response.json();
        if(response.ok){
            if(data.success){
                Swal.fire({
                    icon:"success",
                    text:data.message
                });
            }
            else
            {
                Swal.fire({
                    icon:"warning",
                    text:data.message
                });
            }
        }else if(response.status = 422){
                Swal.fire({
                    icon:"warning",
                    text:data.message
                });
        }
        else
        {
            Swal.fire({
                icon: "error",
                text: "Error inesperado del servidor"
            });
        }
        hideSpinner();
    }

 async function update_person() {
    const form = document.getElementById('form_person');
        const documentType = form.document_type.value.trim();
        const documentNumber = form.document_number.value.trim();
        const name = form.p_name.value.trim();
        const address = form.address.value.trim();
        const phone = form.phone.value.trim();
        const email = form.p_email.value.trim();

        // Validaciones básicas
        if (!documentType) {
            Swal.fire({
                icon: 'warning',
                text: 'Selecciona un tipo de documento.'
            });
            return;
        }

        if (!/^\d+$/.test(documentNumber)) {
            Swal.fire({
                icon: 'warning',
                text: 'El número de documento debe contener solo números.'
            });
            return;
        }

        if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(name)) {
            Swal.fire({
                icon: 'warning',
                text: 'El nombre solo debe contener letras y espacios.'
            });
            return;
        }

        if (!address) {
            Swal.fire({
                icon: 'warning',
                text: 'La dirección no puede estar vacía.'
            });
            return;
        }

        if (!/^\d{7,15}$/.test(phone)) {
            Swal.fire({
                icon: 'warning',
                text: 'El teléfono debe contener entre 7 y 15 dígitos.'
            });
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            Swal.fire({
                icon: 'warning',
                text: 'Ingresa un correo electrónico válido.'
            });
            return;
        }
        const formData = new FormData(form);
        const url = "{{route('user.update',[$user->id,$person->id])}}";
        showSpinner();
        const response = await fetch(url,{
            method:'post',
            headers:{
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
            body:formData
        });
        const data = await response.json();
        if(response.ok){
            if(data.success){
                Swal.fire({
                    icon:"success",
                    text:data.message
                });
            }
            else
            {
                Swal.fire({
                    icon:"warning",
                    text:data.message
                });
            }
        }else if(response.status = 422){
                Swal.fire({
                    icon:"warning",
                    text:data.message
                });
        }
        else
        {
            Swal.fire({
                icon: "error",
                text: "Error inesperado del servidor"
            });
        }
        hideSpinner();
    }
</script>

