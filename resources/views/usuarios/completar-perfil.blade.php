@extends(!Auth::user() || !Auth::user()->esAdmin() ? 'layouts.app' : 'layouts.admin', ['gris' => true])
@section('content')
<div class="registro">
    <div class="row justify-content-center">
        <div class="col-md-9 mt-4">
            @if(session('mensaje'))
                <div class="alert alert-warning" role="alert">
                  {{session('mensaje')}}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>Completar mis datos</h4>
                    <i class="fas fa-sort-up fa-2x"></i>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('usuarios.completar-perfil', $usuario) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="razon_social">Razón Social</label>
                            <input id="razon_social" type="text" class="form-control{{ $errors->has('razon_social') ? ' is-invalid' : '' }}" name="razon_social" value="{{ old('razon_social', $usuario->razon_social) }}" autofocus>
                            @if ($errors->has('razon_social'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('razon_social') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción de la empresa (opcional)</label>
                            <textarea id="descripcion" class="form-control{{ $errors->has('descripcion') ? ' is-invalid' : '' }}" name="descripcion"  autofocus>{{ old('descripcion',$usuario->descripcion) }}</textarea>
                            @if ($errors->has('descripcion'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('descripcion') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="sitio_web">Sitio Web (opcional)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">http://</span>
                                </div>
                                <input id="sitio_web" type="text" class="form-control{{ $errors->has('sitio_web') ? ' is-invalid' : '' }}" name="sitio_web" value="{{ old('sitio_web', $usuario->sitio_web) }}">
                            </div>
                            @if ($errors->has('sitio_web'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('sitio_web') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-9 col-12">
                                <div class="form-group">
                                    <label for="foto_url">Logo de la empresa (opcional)</label>
                                    <div class="input-group">
                                        <div class="btn-file">Seleccionar archivo</div>
                                        <input type="file" name="foto_url" id="file-image">
                                        <span class="message"></span>
                                        @if ($errors->has('foto_url'))
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $errors->first('foto_url') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                @if($usuario->foto_url)
                                    <img src="{{$usuario->foto_url}}" class="img-perfil">
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="provincia_id" class="form-label"><strong>Provincia</strong></label>
                            <select id="provincia_id" class="form-control{{ $errors->has('provincia_id') ? ' is-invalid' : '' }} selectpicker show-tick" name="provincia_id" value="{{ old('provincia_id') }}">
                                 <option value="" disabled selected>Seleccione</option>
                                @foreach ($provincias as $provincia)
                                {{$provincia->id}}
                                    <option value="{{$provincia->id}}" @if($usuario->localidad) {{old('provincia_id', $usuario->localidad->provincia->id) == $provincia->id? 'selected':''}} @endif>{{$provincia->nombre}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('provincia_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('provincia_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="localidad_id" class="form-label"><strong>Localidad</strong></label>
                            <input id="localidad_id" class="form-control{{ $errors->has('localidad_id') ? ' is-invalid' : '' }}" name="localidad_id" type="text" value="@if($usuario->localidad){{old('localidad_id', $usuario->localidad)}} @endif">
                            <ul id="predicciones_localidad" class="menu-predicciones"></ul>
                            @if ($errors->has('localidad_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('localidad_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <input id="loc_place_id" type="hidden" name="localidad_place_id">
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input id="direccion" type="text" class="form-control {{ $errors->has('direccion')?'is-invalid':''}}"  name="direccion" value="{{ old('direccion', $usuario->direccion) }}">
                            <ul id="predicciones_direccion" class="menu-predicciones"></ul>
                            @if ($errors->has('direccion'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('direccion') }}</strong>
                                </span>
                            @endif
                        </div>
                        <input id="dir_place_id" type="hidden" name="google_place_id" value="{{$usuario->google_place_id}}">
                        <div class="row">
                            <div class="form-group col-md-6 col-12">
                                <label for="telefono">Teléfono fijo</label>
                                <input id="telefono" type="text" class="form-control {{ $errors->has('telefono') ? 'is-invalid' : '' }}" name="telefono" value="{{ old('telefono', $usuario->telefono) }}">
                            </div>
                            @if ($errors->has('telefono'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('telefono') }}</strong>
                                </span>
                            @endif
                            <div class="form-group col-md-6 col-12">
                                <label for="telefono_celular">Teléfono celular (opcional)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">+54</span>
                                    </div>
                                    <input type="text" name="telefono_celular" class="form-control {{ $errors->has('telefono_celular') ? 'is-invalid' : '' }}" value="{{ old('telefono_celular', $usuario->telefono_celular) }}">
                                </div>
                                 <small class="form-text text-muted">Cod. de área sin el 0 + Número sin el 15</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="persona_contacto">Persona de contacto (opcional)</label>
                            <input id="persona_contacto" type="text" class="form-control{{ $errors->has('persona_contacto') ? ' is-invalid' : '' }}" name="persona_contacto" value="{{ old('persona_contacto', $usuario->persona_contacto) }}">
                            @if ($errors->has('persona_contacto'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('persona_contacto') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="cuit">CUIT (sólo números)</label>
                            <input id="cuit" type="number" class="form-control{{ $errors->has('cuit') ? ' is-invalid' : '' }}" name="cuit" value="{{ old('cuit', $usuario->cuit) }}">
                            @if ($errors->has('cuit'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('cuit') }}</strong>
                                </span>
                            @endif
                        </div> 
                        <div class="form-group">
                            <label for="condicion_iva">Condición IVA</label>
                            <select id="condicion_iva" type="text" class="form-control{{ $errors->has('condicion_iva') ? ' is-invalid' : '' }} selectpicker show-tick" name="condicion_iva" value="{{ old('condicion_iva') }}"> 
                                <option value="" disabled selected>Seleccione</option>
                                @foreach ($condiciones_iva as $condicion) 
                                    <option value="{{$condicion->id}}" {{old('condicion_iva', $usuario->condicion_iva_id) == $condicion->id? 'selected':''}}>{{$condicion->nombre}}</option> 
                                @endforeach
                            </select>
                            @if ($errors->has('condicion_iva'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('condicion_iva') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-9 col-12 text-md-right text-center">
                                <a href="{{ route('home') }}" class="btn btn-secondary">
                                    Volver
                                </a>
                            </div>
                            <div class="col-md-3 col-12 text-md-right text-center">
                                <button type="submit" class="btn btn-verde">
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('post-scripts')
    <script src="\js\propiedades\direccionesAutocompletar.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAPS_API_KEY', '')}}&libraries=places"></script>
    <script type="text/javascript">
        $(function() {
            if (jQuery('.btn-file').val() == '') {
               $('.message').addClass('message-file').html("No se eligió archivo");
            }

            $('.btn-file').on("click" , function () {
                $('#file-image').click();
            });
            $('#file-image').change(function () {
                if($(this).val()) {
                    $('.message').addClass('message-file').text($(this).val().replace(/.*(\/|\\)/, ''));
                }
                else {
                    $('.message').addClass('message-file').text("No se eligió archivo");
                }
            });
        });
    </script>
@endsection