@extends('layouts.admin')

@section('title', 'Editar Configuración')

@section('content')

<div class="container">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h3 class="mb-0">

                    <i class="bi bi-pencil-square"></i>

                    Editar configuración

                </h3>

                <a href="{{ route('config.index') }}"
                   class="btn btn-secondary">

                    <i class="bi bi-arrow-left"></i>

                    Volver

                </a>

            </div>


            <div class="card shadow-sm">

                <div class="card-header bg-warning">

                    <i class="bi bi-gear"></i>

                    Editar parámetro

                </div>


                <div class="card-body">

                    <form action="{{ route('config.update', $config->key) }}"
                          method="POST">

                        @csrf

                        @method('PUT')


                        {{-- KEY --}}
                        <div class="mb-3">

                            <label class="form-label">

                                Key

                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="key"
                                   value="{{ old('key', $config->key) }}"
                                   class="form-control @error('key') is-invalid @enderror">

                            @error('key')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- VALUE --}}
                        <div class="mb-3">

                            <label class="form-label">

                                Valor

                            </label>

                            <textarea name="value"
                                      rows="6"
                                      class="form-control @error('value') is-invalid @enderror">{{ old('value', $config->value) }}</textarea>

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
                                   value="{{ old('alias', $config->alias) }}"
                                   class="form-control @error('alias') is-invalid @enderror">

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

                                <i class="bi bi-check-circle"></i>

                                Actualizar

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
