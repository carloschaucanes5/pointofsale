@extends('layouts.admin')

@section('title', 'Crear Parámetro')

@section('content')

<div class="container">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h3 class="mb-0">

                    <i class="bi bi-plus-circle"></i>

                    Nueva configuración

                </h3>

                <a href="{{ route('config.index') }}"
                   class="btn btn-secondary">

                    <i class="bi bi-arrow-left"></i>

                    Volver

                </a>

            </div>


            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">

                    <i class="bi bi-gear"></i>

                    Datos de configuración

                </div>


                <div class="card-body">

                    <form action="{{ route('config.store') }}"
                          method="POST">

                        @csrf


                        {{-- KEY --}}
                        <div class="mb-3">

                            <label class="form-label">

                                Key

                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="key"
                                   value="{{ old('key') }}"
                                   class="form-control @error('key') is-invalid @enderror"
                                   placeholder="Ej: company_name">

                            @error('key')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <div class="form-text">

                                Identificador único de la configuración.

                            </div>

                        </div>


                        {{-- VALUE --}}
                        <div class="mb-3">

                            <label class="form-label">

                                Valor

                            </label>

                            <textarea name="value"
                                      rows="5"
                                      class="form-control @error('value') is-invalid @enderror"
                                      placeholder="Ingrese el valor...">{{ old('value') }}</textarea>

                            @error('value')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- ALIAS --}}
                        <div class="mb-4">

                            <label class="form-label">

                                Alias

                            </label>

                            <input type="text"
                                   name="alias"
                                   value="{{ old('alias') }}"
                                   class="form-control @error('alias') is-invalid @enderror"
                                   placeholder="Ej: Nombre de la empresa">

                            @error('alias')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- BOTONES --}}
                        <div class="text-center">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-save"></i>

                                Guardar

                            </button>


                            <a href="{{ route('config.index') }}"
                               class="btn btn-secondary">

                                <i class="bi bi-x-circle"></i>

                                Cancelar

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
