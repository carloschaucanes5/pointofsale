@extends('layouts.admin')

@section('title', 'Mostrar Ingreso')

@section('content')
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Detalle Ingreso</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="supplier_id">Proveedor</label>
                            <p>{{$income->name}}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="voucher_number">Número de Comprobante</label>
                            <p>{{$income->voucher_number}}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="users_id">Usuario</label>
                            <p>{{$income->users_name}}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="users_id">Valor Factura</label>
                            <p>{{number_format($income->total, 2, ",", ".")}}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover mb-1" id="detalles">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Concentración</th>
                                    <th>Presentación</th>
                                    <th>Cantidad</th>
                                    <th>Precio Compra</th>
                                    <th>Subtotal</th>
                                    <th>Precio Venta</th>
                                    <th>Forma Venta</th>
                                    <th>F. Venc.</th>
                                    <th>Lote</th>
                                    <th>Invima</th>
                                    <th>Vigencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sum = 0;
                                 @endphp
                                @foreach($details as $det)
                                    @php
                                        $sum = $sum + ($det->quantity * $det->purchase_price);

                                    @endphp
                                    <tr>
                                        <td><a href="#"
                                                onclick="actualizar_producto({{ json_encode($det) }});">{{$det->article}}</a>
                                        </td>
                                        <td>{{$det->concentration}}</td>
                                        <td>{{$det->presentation}}</td>
                                        <td>{{$det->quantity}}</td>
                                        <td>$ {{number_format($det->purchase_price, 2, ",", ".")}}</td>
                                        <td>$ {{number_format($det->quantity * $det->purchase_price, 2, ",", ".")}}</td>
                                        <td>$ {{number_format($det->sale_price, 0, ",", ".")}}</td>
                                        <td>{{$det->form_sale}}</td>
                                        <td>{{$det->expiration_date}}</td>
                                        <td>{{$det->lote}}</td>
                                        <td>{{$det->invima}}</td>
                                        @if (!$det->validity)
                                            <td><button class="btn btn-warning"
                                                    onclick="validar_vigencia({{ $det->income_detail_id }})">Validar</button></td>
                                        @else
                                            <td>{{$det->validity}}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Total:</th>
                                    <th id="total">{{number_format($sum, 2)}}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                        <a href="{{ route('purchase.income.export', $income_id) }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-spreadsheet-fill"></i> Exportar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li> {{-- Incluye el mensaje personalizado --}}
                @endforeach
            </ul>
        </div>
    @endif

@endsection


<script>
    function validar_vigencia(income_detail_id) {
        const vigencias = @json($vigencias);

        let options = vigencias.map(vigencia => {
            return `
                <option value="${vigencia}"}>
                    ${vigencia}
                </option>
            `;
        }).join('');

        Swal.fire({
            title: 'Cambiar vigencia',
            html: `
                <select id="select-vigencia" class="swal2-select">
                    ${options}
                </select>
            `,
            showCancelButton: true,
            confirmButtonText: 'Actualizar',
            cancelButtonText: 'Cancelar',
            focusConfirm: false,

            preConfirm: () => {
                return document.getElementById('select-vigencia').value;
            }
        }).then((result) => {

            if (!result.isConfirmed) {
                return;
            }

            const nueva_vigencia = result.value;

            let url = "{{ route('purchase.income.update_vigencia') }}";

            fetch(`${url}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    vigencia: nueva_vigencia,
                    income_detail_id: income_detail_id
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al actualizar');
                    }

                    return response.json();
                })
                .then(data => {

                    Swal.fire({
                        icon: 'success',
                        title: 'Actualizado',
                        text: data.message ?? 'La vigencia fue actualizada correctamente.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    window.location.reload();
                })
                .catch(error => {

                    console.error(error);

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo actualizar la vigencia.'
                    });

                });
        });
    }
    function actualizar_producto(det) {
        const formasVenta = @json($forms);
        const vigencias = @json($vigencias);
        let opcionesFormSale = formasVenta.map(opcion => `
        <option value="${opcion.description}" ${det.form_sale === opcion.description ? 'selected' : ''}>
            ${opcion.description}
        </option>
    `).join('');

        let opcionesVigencias = vigencias.map(opcion => `
            <option value="${opcion}" ${det.validity === opcion ? 'selected' : ''}>
            ${opcion}
        </option>
        `).join('');

        Swal.fire({
            title: 'Editar producto',
            width: '700px',

            html: `
            <div class="row text-start">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Artículo</label>
                    <input type="text"
                           id="swal_article"
                           class="form-control"
                           value="${det.article ?? ''}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Concentración</label>
                    <input type="text"
                           id="swal_concentration"
                           class="form-control"
                           value="${det.concentration ?? ''}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Presentación</label>
                    <input type="text"
                           id="swal_presentation"
                           class="form-control"
                           value="${det.presentation ?? ''}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Forma de venta</label>

                    <select id="swal_form_sale" class="form-control">
                        ${opcionesFormSale}
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Lote</label>
                    <input type="text"
                           id="swal_lote"
                           class="form-control"
                           value="${det.lote ?? ''}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Fecha vencimiento</label>
                    <input type="date"
                           id="swal_expiration_date"
                           class="form-control"
                           value="${det.expiration_date ?? ''}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">INVIMA</label>
                    <input type="text"
                           id="swal_invima"
                           class="form-control"
                           value="${det.invima ?? ''}">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Vigencia</label>
                    <select id="swal_vigencia" class="form-control">
                        ${opcionesVigencias}
                    </select>
                </div>
            </div>
        `,

            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',

            preConfirm: () => {

                return {
                    id: det.id,
                    income_detail_id: det.income_detail_id,
                    article: document.getElementById('swal_article').value,
                    concentration: document.getElementById('swal_concentration').value,
                    presentation: document.getElementById('swal_presentation').value,
                    form_sale: document.getElementById('swal_form_sale').value,
                    lote: document.getElementById('swal_lote').value,
                    expiration_date: document.getElementById('swal_expiration_date').value,
                    invima: document.getElementById('swal_invima').value,
                    vigencia:document.getElementById('swal_vigencia').value
                };
            }

        }).then(result => {

            if (result.isConfirmed) {

                guardar_producto(result);
            }
        });
    }

    function guardar_producto(objeto) {
        let url = "{{ route('purchase.income.update_income') }}";
        fetch(`${url}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content")
            },
            body: JSON.stringify(objeto.value)
        })
            .then(response => {
                if (!response.ok) {
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!',
                        text: error.message || 'Ocurrió un error al actualizar el producto.',
                        confirmButtonText: 'Aceptar'
                    });
                }

                return response.json();
            })
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Actualizado!',
                    text: 'El producto se actualizó correctamente.',
                    timer: 3000,
                    showConfirmButton: false
                });
                window.location.reload();
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: error.message || 'Ocurrió un error al actualizar el producto.',
                    confirmButtonText: 'Aceptar'
                });
            });
    }

</script>