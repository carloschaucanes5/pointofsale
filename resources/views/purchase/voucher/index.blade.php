@extends('layouts.admin')

@section('title', 'Index')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">LISTADO DE FACTURAS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active"><a href="#">Facturas</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row" id="table-hover-row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-xl-12">
                            <form action="{{route('voucher.index')}}" method="get">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mb-6">
                                            <span class="input-group-text" id="basic-addon1"><i
                                                    class="bi bi-search"></i></span>
                                            <input type="text" class="form-control" name="searchText"
                                                placeholder="Buscar Facturas" value="{{$searchText}}"
                                                aria-label="campo busqueda" aria-describedby="button-addon2">
                                            <button class="btn btn-outline-secondary" type="submit"
                                                id="button-addon2">Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="from" class="form-control" value="{{ $from }}">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="to" class="form-control" value="{{ $to }}">
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group mb-6">
                                            <a href="{{route('voucher.create')}}" class="btn btn-success">Nueva</a>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-content">
            @if ($errors->has('error'))
                <div class="alert alert-danger">
                    {{ $errors->first('error') }}
                </div>
            @endif
            <div class="card-body">
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Número de Factura</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>valor</th>
                            <th>Responsable</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forEach($vouchers as $vou)
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-view-{{$vou->id}}"><i class="bi bi-eye"></i></button>
                                            </td>
                                            <td>{{$vou->voucher_number}}</td>
                                            <td>{{$vou->supplier_name}}</td>
                                            <td>{{ \Carbon\Carbon::parse($vou->updated_at)->format('d/m/Y g:i A') }}</td>
                                            <td>$ {{number_format($vou->total, 2, ',', '.')}}</td>
                                            <td>{{$vou->user_name}}</td>
                                            <td>{{$vou->status_payment}}</td>
                                            <td>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="abrirModalRevisionInvima({{ $vou->id }},'{{ $vou->voucher_number }}')" title="Asignar revisión INVIMA">
                                                    <i class="bi bi-clipboard-check"></i>
                                                </button>
                                            </td>

                                        </tr>
                                        @include("purchase.voucher.modal")
                        @endforeach
                    </tbody>
                </table>
                {{$vouchers->links('pagination::bootstrap-5')}}
            </div>
        </div>
    </section>
@endsection
@push('scripts')
<script>

function abrirModalRevisionInvima(voucherId, voucherNumber) {
    Swal.fire({
        title: 'Asignar revisión INVIMA',
        width: '650px',
        html: `
            <div class="text-start">

                <div class="alert alert-light border">
                    <strong>Factura:</strong> ${voucherNumber}
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Usuario responsable
                    </label>

                    <select 
                        id="usuario_revision"
                        class="form-select">

                        <option value="">
                            Seleccione un usuario
                        </option>

                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Descripción
                    </label>

                    <textarea
                        id="descripcion_revision"
                        class="form-control"
                        rows="4"
                        placeholder="Ingrese la descripción de la revisión INVIMA">
                    </textarea>
                </div>

            </div>
        `,

        showCancelButton: true,

        confirmButtonText:
            '<i class="bi bi-clipboard-check"></i> Asignar revisión INVIMA',

        cancelButtonText: 'Cancelar',

        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',

        focusConfirm: false,

        preConfirm: () => {

            const usuario = document.getElementById('usuario_revision').value;
            const descripcion = document.getElementById(
                'descripcion_revision'
            ).value.trim();
            // Validar usuario
            if (!usuario) {
                Swal.showValidationMessage(
                    'Debe seleccionar un usuario'
                );
                return false;
            }
            // Validar descripción
            if (!descripcion) {
                Swal.showValidationMessage(
                    'Debe ingresar una descripción'
                );
                return false;
            }
            // Mostrar loading mientras se ejecuta AJAX
            Swal.showLoading();
            // FETCH AL CONTROLADOR
            return fetch(
                "{{ route('purchase.voucher.revision.invima') }}",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",

                        "X-CSRF-TOKEN":
                            "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        voucher_id: voucherId,
                        user_id: usuario,
                        descripcion: descripcion
                    })
                }
            )
            .then(response => {
                if (!response.ok) {
                    throw new Error(
                        'Error HTTP: ' + response.status
                    );
                }

                return response.json();
            })
            .then(data => {
                if (!data.success) {
                    throw new Error(
                        data.message ??
                        'No se pudo realizar la asignación.'
                    );
                }
                return data;
            })
            .catch(error => {
                Swal.showValidationMessage(
                    error.message
                );
            });
        }
    }).then(result => {
        if (result.isConfirmed && result.value) {
            Swal.fire({
                icon: 'success',
                title: 'Asignación realizada',
                text: result.value.message ??
                    'La revisión INVIMA fue asignada correctamente.',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}
</script>
@endpush