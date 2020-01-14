@extends('layouts.admin', ['gris' => true])
@section('content')
<form id="propiedad" method="POST" action="{{ route('admin.propiedades.store') }}" class="form-disable" url="upload" files="true" enctype="multipart/form-data">
@csrf
    <div class="py-4 publicacion">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="row mb-2">
                    <div class="col-12 text-md-left text-center">
                        <h1>Crear Propiedad</h1>
                    </div>
                </div>
                <div class="card card-publicacion">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 text-center">
                                <ul class="indicador-progreso paso-1">
                                    <li title="Datos de la Propiedad"  class="change-card activo" data-ref="#datos" data-step="paso-1">
                                        <a href="#">1</a>
                                        <h5>Datos de la propiedad</h5>
                                        <i class="fas fa-sort-up fa-2x"></i>
                                    </li>
                                    <li title="Ubicación"  class="change-card" data-ref="#ubicacion" data-step="paso-2">
                                        <a href="#">2</a>
                                        <h5>Ubicación</h5>
                                        <i class="fas fa-sort-up fa-2x"></i>
                                    </li>
                                    <li title="Características" class="change-card {{ old('tipo_propiedad_id') == null?'inactivo':'' }}" data-ref="#caracteristicas" data-step="paso-3">
                                        <a href="#">3</a>
                                        <h5>Características</h5>
                                        <i class="fas fa-sort-up fa-2x"></i>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body interchangeable" id="datos">
                        <div class="form-group">
                            <label for="usuario" class="form-label"><strong>E-Mail del anunciante</strong></label> 
                            <input type="text" id="usuarios-autocompletar" name="usuario-email" class="form-control" value="{{old('usuario-email')}}" autofocus>
                            <input type="hidden" name="usuario" id="usuarios-id" value="{{old('usuario')}}">
                        </div>
                        <h6>Datos de la Propiedad</h6>
                        <span class="divisor-titulo"></span>
                        <div class="form-group">
                            <label for="titulo" class="form-label"><strong>Título</strong></label>
                            <input id="titulo" type="text" class="form-control{{ $errors->has('titulo') ? ' is-invalid' : '' }}" name="titulo" value="{{ old('titulo') }}" autofocus>
                            @if ($errors->has('titulo'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('titulo') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="tipo_propiedad_id" class="form-label"><strong>Inmueble</strong></label>
                            <select id="tipo_propiedad_id" type="text" class="form-control{{ $errors->has('tipo_propiedad_id') ? ' is-invalid' : '' }} selectpicker show-tick" name="tipo_propiedad_id" value="{{ old('tipo_propiedad_id') }}">
                            <option value="" disabled selected>Seleccione</option>
                            @foreach ($tipos as $tip)
                                <option value="{{$tip->id}}" {{ old('tipo_propiedad_id') == $tip->id? 'selected':'' }} >{{$tip->nombre}}</option>
                            @endforeach
                            </select>
                            @if ($errors->has('tipo_propiedad_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('tipo_propiedad_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label"><strong>Operación</strong></label>
                        </div>
                        <div class="form-group">
                            @foreach ($operaciones as $op)
                                <div class="form-check form-check-inline">
                                    <label class="custom-radio" for="op[{{$op->id}}]">
                                        <input class="form-check-input{{ $errors->has('operacion_id') ? ' is-invalid' : '' }}" type="radio" name="operacion_id" value="{{$op->id}}" {{old('operacion_id') == $op->id? 'checked':''}} id="op[{{$op->id}}]">
                                        <span class="checkmark"></span>
                                        <label class="form-check-label form-check-operaciones" for="op[{{$op->id}}]">{{$op->nombre}}</label>
                                    </label>
                                </div>
                            @endforeach   
                            @if ($errors->has('operacion_id'))
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $errors->first('operacion_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="monto" class="form-label"><strong>Precio (opcional)</strong></label>
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <input id="precio_id" type="number" class="form-control{{ $errors->has('monto') ? ' is-invalid' : '' }} precio" name="monto" value="{{ old('monto') }}">
                                </div>
                                <div class="col-md-2 col-12">
                                    <select id="moneda_id" name="moneda_id" value="{{ old('moneda_id') }}" class="form-control{{ $errors->has('moneda_id') ? ' is-invalid' : '' }} selectpicker show-tick" type="text">
                                        @foreach ($monedas as $mon)
                                            <option value="{{$mon->id}}" {{old('moneda_id') == $mon->id? 'selected':''}}>{{$mon->nombre}}</option>
                                        @endforeach  
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-12">
                                    <div class="form-check form-check-inline custom-control custom-checkbox">
                                        <input id="precio_convenir" type="checkbox" name="precio_convenir" class="custom-control-input" value="1" {{ old('precio_convenir')== 1 ? 'checked':''}}>
                                        <label class="form-label custom-control-label" for="precio_convenir">Consultar Precio</label>
                                    </div>
                                </div>
                            </div>
                            @if ($errors->has('monto'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('monto') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <label for="superficie" class="form-label"><strong>Superficie Total (opcional)</strong></label>
                                    <input type="number" class="form-control{{ $errors->has('superficie') ? ' is-invalid' : '' }}" name="superficie" value="{{ old('superficie') }}">
                                </div>
                                <div class="col-md-2 col-12 mt-2">
                                    <label></label>
                                    <select name="unidad_superficie_id" value="{{old('unidad_superficie_id')}}" class="form-control{{ $errors->has('unidad_superficie_id') ? ' is-invalid' : '' }} selectpicker show-tick" type="text">
                                        @foreach($unidades as $unidad)
                                            <option value="{{$unidad->id}}" {{old('unidad_superficie_id') == $unidad->id? 'selected':''}}>{{$unidad->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="superficie_cubierta" class="form-label cubierta"><strong>Superficie Cubierta (opcional)</strong></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control{{ $errors->has('superficie_cubierta') ? ' is-invalid' : '' }}" name="superficie_cubierta" value="{{ old('superficie_cubierta') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">m2</span>
                                            @if ($errors->has('superficie_cubierta'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('superficie_cubierta') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 col-12">
                                <label for="expensas" class="form-label"><strong>Expensas Aproximadas (opcional)</strong></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                            @if ($errors->has('expensas'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('expensas') }}</strong>
                                                </span>
                                            @endif
                                    </div>
                                    <input type="number" class="form-control{{ $errors->has('expensas') ? ' is-invalid' : '' }}" name="expensas" value="{{ old('expensas') }}">
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="tipologia_id" class="form-label"><strong>Tipología (opcional)</strong></label>
                                    <select id="tipologia_id" type="text" class="form-control{{ $errors->has('tipologia_id') ? ' is-invalid' : '' }} selectpicker show-tick" name="tipologia_id" value="{{ old('tipologia_id') }}">
                                        <option value="" selected>Seleccione</option>
                                     @foreach ($tipologias as $tip)
                                            <option value="{{$tip->id}}" {{ old('tipologia_id') == $tip->id? 'selected':'' }} >{{$tip->nombre}}</option>
                                     @endforeach   
                                    </select>
                                    @if ($errors->has('tipologia_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('tipologia_id') }}</strong>
                                        </span>
                                    @endif
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label"><strong>Usos Permitidos</strong></label>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-6">
                                @for($i=0; $i<count($usos)/2; $i++)
                                    <div class="form-check custom-control custom-checkbox">
                                        <input class="custom-control-input" name="usos[]" type="checkbox" value="{{$usos[$i]->id}}" {{old('usos') && in_array($usos[$i]->id,old('usos'))? 'checked':''}} id="usos[{{$usos[$i]->id}}]">
                                        <label class="custom-control-label" for="usos[{{$usos[$i]->id}}]">{{$usos[$i]->nombre}}</label>
                                    </div>
                                @endfor
                            </div>
                            <div class="col-6">
                                @for($i=count($usos)/2+1; $i<count($usos); $i++)
                                    <div class="form-check custom-control custom-checkbox">
                                        <input class="custom-control-input" name="usos[]" type="checkbox" value="{{$usos[$i]->id}}" {{old('usos') && in_array($usos[$i]->id,old('usos'))? 'checked':''}} id="usos[{{$usos[$i]->id}}]">
                                        <label class="custom-control-label" for="usos[{{$usos[$i]->id}}]">{{$usos[$i]->nombre}}</label>
                                    </div>
                                @endfor

                            </div>
                        </div>
                        @if ($errors->has('usos'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('usos') }}</strong>
                            </span>
                        @endif
                        <div class="form-group">
                            <label for="descripcion" class="form-label"><strong>Descripción</strong></label>
                            <textarea id="descripcion" class="form-control{{ $errors->has('descripcion') ? ' is-invalid' : '' }}" name="descripcion">{{ old('descripcion') }} </textarea>
                            @if ($errors->has('descripcion'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('descripcion') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="pdf_url" class="col-form-label"><strong>Ficha en PDF (opcional)</strong></label>
                            <div class="input-group">
                                <div class="btn-file">Seleccionar archivo</div>
                                <input type="file" name="pdf_url" id="file-pdf">
                                <span class="message"></span>
                                @if ($errors->has('pdf_url'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('pdf_url') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="dropzone mb-4" id="myDropzone">
                           <div class="dz-message" data-dz-message><span>Suba o arrastre fotos de la propiedad</span></div>
                        </div>
                        <div class="form-group">
                            <label for="video_url" class="col-form-label"><strong>Video de Youtube (opcional)</strong></label>
                            <div class="input-group">
                                <input type="text"  name="video_url" placeholder="https://www.youtube.com/..." class="form-control {{ $errors->has('video_url') ? 'is-invalid' : '' }}">
                                @if ($errors->has('video_url'))
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $errors->first('video_url') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9 col-12 text-md-right text-center">
                                <a href="/admin/propiedades" class="btn btn-secondary">Volver</a>
                            </div>
                            <div class="col-md-3 change-card text-right" data-ref="#ubicacion" data-step="paso-2">
                                <a href="#" class="btn btn-info">Siguiente</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-none interchangeable" id="ubicacion">
                        <h6>Ubicación</h6>
                        <span class="divisor-titulo"></span>
                        <div class="form-group">
                            <label class="form-label"><strong>Provincia</strong></label>
                            <select id="provincia_id" class="form-control{{ $errors->has('provincia_id') ? ' is-invalid' : '' }} selectpicker show-tick" name="provincia_id" value="{{old('provincia_id')}}">
                                 <option value="" disabled selected>Seleccione</option>
                                @foreach ($provincias as $provincia)
                                    <option value="{{$provincia->id}}" {{old('provincia_id') == $provincia->id? 'selected':''}}>{{$provincia->nombre}}</option>
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
                            <input id="localidad_id" class="form-control{{ $errors->has('localidad_id') ? ' is-invalid' : '' }}" name="localidad_id" type="text" value="{{old('localidad_id')}}">
                            <ul id="predicciones_localidad" class="menu-predicciones"></ul>
                            @if ($errors->has('localidad_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('localidad_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <input id="loc_place_id" type="hidden" name="localidad_place_id">
                        <div class="form-group">
                            <label for="barrio_id" class="form-label"><strong>Barrio (opcional)</strong></label>
                            <input  id="barrios-autocompletar" class="form-control{{ $errors->has('barrio_id') ? ' is-invalid' : '' }}" name="barrio-nombre" value="{{old('barrio-nombre')}}">
                            <input type="hidden" name="barrio_id" id="barrio-id" value="{{old('barrio_id')}}">
                            @if ($errors->has('barrio_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('barrio_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="direccion" class="form-label"><strong>Dirección</strong></label>
                            <input id="direccion" type="text" class="form-control{{ $errors->has('direccion') ? ' is-invalid' : '' }}" name="direccion" value="{{ old('direccion') }}">
                            <ul id="predicciones_direccion" class="menu-predicciones"></ul>
                            @if ($errors->has('direccion'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('direccion') }}</strong>
                                </span>
                            @endif
                        </div>
                        <input id="dir_place_id" type="hidden" name="google_place_id">
                        <div class="form-group">
                            <label for="piso" class="form-label"><strong>Piso (opcional)</strong></label>
                            <input id="piso" type="text" class="form-control{{ $errors->has('piso') ? ' is-invalid' : '' }}" name="piso" value="{{ old('piso') }}">

                            @if ($errors->has('piso'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('piso') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="departamento" class="form-label"><strong>Departamento (opcional)</strong></label>
                            <input id="departamento" type="text" class="form-control {{ $errors->has('departamento')?'is-invalid':''}}"  name="departamento" value="{{ old('departamento') }}">
                            @if ($errors->has('departamento'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('departamento') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="referencia" class="form-label"><strong>Referencia (opcional)</strong></label>
                            <input id="referencia" type="text" class="form-control {{ $errors->has('referencia')?'is-invalid':''}}"  name="referencia" value="{{ old('referencia') }}">
                            @if ($errors->has('referencia'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('referencia') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-inline custom-control custom-checkbox">
                                <input type="checkbox" name="ubicacion_publica" class="custom-control-input" value="1" {{old('ubicacion_publica') == 1 ? 'checked' : ''}}>
                                <label class="form-label custom-control-label" for="ubicacion_publica">Mostrar domicilio completo de manera pública</label>
                            </div>
                        </div>
                        <input id="lat" type="hidden" name="lat" value="{{old('lat')}}">
                        <input id="long" type="hidden" name="long" value="{{old('long')}}"> 
                        <div id="map" class="mb-3"></div>
                        <div class="row">
                            <div class="col-md-9 col-12 text-md-right text-center change-card" data-ref="#datos" data-step="paso-1">
                                <a href="#" class="btn btn-secondary">Atrás</a>
                            </div>
                            <div class="col-md-3 col-12 text-right change-card {{ old('tipo_propiedad_id') != null?'activo':'inactivo' }}" data-ref="#caracteristicas" data-step="paso-3">
                                <a href="#" class="btn btn-info">Siguiente</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-none interchangeable" id="caracteristicas">
                        <h6>Características</h6>
                        <span class="divisor-titulo"></span>
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div id="basicas">
                                    @if (old('caracteristicas'))
                                        @foreach (old('caracteristicas') as $c)
                                            @if ($c['es_servicio']==0)
                                                @if ($c['tipo_caracteristica_id']==1 || $c['tipo_caracteristica_id']==2)
                                                    <div class="form-group">
                                                        <label class="form-label"><strong>{{$c['nombre']}}</strong></label>
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][tipo_caracteristica_id]" value="{{$c['tipo_caracteristica_id']}}">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][idx]" value="{{$c['idx']}}">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][caracteristica_id]" value="{{$c['caracteristica_id']}}">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][nombre]" value="{{$c['nombre']}}">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][unidad]" value="{{$c['unidad']}}">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][es_servicio]" value="0">
                                                        <input class="form-control" name="caracteristicas[{{$c['idx']}}][valor]" type="{{$c['tipo_caracteristica_id']==1 ? 'number' :'text' }}"value="{{$c['valor'] }}">
                                                    </div>
                                                @endif
                                                @if ($c['tipo_caracteristica_id']==3)
                                                    <div class="form-group">
                                                        <div class="form-check form-check-inline custom-control custom-checkbox">
                                                            <input type="hidden" name="caracteristicas[{{$c['idx']}}][tipo_caracteristica_id]" value="{{$c['tipo_caracteristica_id']}}">
                                                            <input type="hidden" name="caracteristicas[{{$c['idx']}}][idx]" value="{{$c['idx']}}">
                                                            <input type="hidden" name="caracteristicas[{{$c['idx']}}][caracteristica_id]" value="{{$c['caracteristica_id']}}">
                                                            <input type="hidden" name="caracteristicas[{{$c['idx']}}][nombre]" value="{{$c['nombre']}}">
                                                            <input type="hidden" name="caracteristicas[{{$c['idx']}}][es_servicio]" value="0">
                                                            <input type="hidden" class="checkeable-false" name="caracteristicas[{{$c['idx']}}][valor]" value="0" {{$c['valor'] == 1 ? 'disabled' : '' }}>
                                                            <input class="custom-control-input checkeable-true" name="caracteristicas[{{$c['idx']}}][valor]" type="checkbox" value="1" {{$c['valor'] == 1 ? 'checked' : '' }}>
                                                            <label class="custom-control-label">{{$c['nombre']}}</label>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($c['tipo_caracteristica_id']==4)
                                                    <div class="form-group">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][caracteristica_id]" value="{{$c['caracteristica_id']}}">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][tipo_caracteristica_id]" value="{{$c['tipo_caracteristica_id']}}">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][idx]" value="{{$c['idx']}}">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][es_servicio]" value="0">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][nombre]" value="{{$c['nombre']}}">

                                                        @foreach ($c['opciones'] as $o)
                                                            <input type="hidden" name="caracteristicas[{{$c['idx']}}][opciones][{{$loop->index}}][id]" value="{{$o['id']}}">
                                                            <input type="hidden" name="caracteristicas[{{$c['idx']}}][opciones][{{$loop->index}}][nombre]" value="{{$o['nombre']}}">
                                                        @endforeach

                                                        <label class="form-label">
                                                            <strong>{{$c['nombre']}}</strong>
                                                        </label>
                                                        <select class="form-control selectpicker show-tick" type="text" name="caracteristicas[{{$c['idx']}}][valor]">
                                                            <option value="" selected>Seleccione</option>
                                                            @foreach ($c['opciones'] as $o)
                                                                <option value="{{$o['id']}}" {{$c['valor'] == $o['id'] ? 'selected' : ''}}>{{$o['nombre']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 mb-4">
                                <div id="servicios">
                                    @if (old('caracteristicas'))
                                        @foreach (old('caracteristicas') as $c)
                                            @if ($c['es_servicio']==1)
                                                @if ($c['tipo_caracteristica_id']==3)
                                                    <div class="form-group">
                                                        <div class="form-check form-check-inline custom-control custom-checkbox">
                                                            <input type="hidden" name="caracteristicas[{{$c['idx']}}][tipo_caracteristica_id]" value="{{$c['tipo_caracteristica_id']}}">
                                                            <input type="hidden" name="caracteristicas[{{$c['idx']}}][idx]" value="{{$c['idx']}}">
                                                            <input type="hidden" name="caracteristicas[{{$c['idx']}}][caracteristica_id]" value="{{$c['caracteristica_id']}}">
                                                            <input type="hidden" name="caracteristicas[{{$c['idx']}}][nombre]" value="{{$c['nombre']}}">
                                                            <input type="hidden" name="caracteristicas[{{$c['idx']}}][es_servicio]" value="1">
                                                            <input type="hidden" class="checkeable-false" name="caracteristicas[{{$c['idx']}}][valor]" value="0" {{$c['valor'] == 1 ? 'disabled' : '' }}>
                                                            <input class="custom-control-input checkeable-true" name="caracteristicas[{{$c['idx']}}][valor]" type="checkbox" value="1" {{$c['valor'] == 1 ? 'checked' : '' }}>
                                                            <label class="custom-control-label">{{$c['nombre']}}</label>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($c['tipo_caracteristica_id']==1 || $c['tipo_caracteristica_id']==2)
                                                    <div class="form-group">
                                                        <label class="form-label"><strong>{{$c['nombre']}}</strong></label>
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][tipo_caracteristica_id]" value="{{$c['tipo_caracteristica_id']}}">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][idx]" value="{{$c['idx']}}">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][caracteristica_id]" value="{{$c['caracteristica_id']}}">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][nombre]" value="{{$c['nombre']}}">

                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][unidad]" value="{{$c['unidad']}}">

                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][es_servicio]" value="0">
                                                 
                                                        <input class="form-control" name="caracteristicas[{{$c['idx']}}][valor]" type="{{$c['tipo_caracteristica_id']==1 ? 'number' :'text' }}"value="{{$c['valor'] }}">
                                                    </div>
                                                @endif
                                                @if ($c['tipo_caracteristica_id']==4)
                                                    <div class="form-group">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][caracteristica_id]" value="{{$c['caracteristica_id']}}">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][tipo_caracteristica_id]" value="{{$c['tipo_caracteristica_id']}}">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][idx]" value="{{$c['idx']}}">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][es_servicio]" value="0">
                                                        <input type="hidden" name="caracteristicas[{{$c['idx']}}][nombre]" value="{{$c['nombre']}}">

                                                        @foreach ($c['opciones'] as $o)
                                                            <input type="hidden" name="caracteristicas[{{$c['idx']}}][opciones][{{$loop->index}}][id]" value="{{$o['id']}}">
                                                            <input type="hidden" name="caracteristicas[{{$c['idx']}}][opciones][{{$loop->index}}][nombre]" value="{{$o['nombre']}}">
                                                        @endforeach

                                                        <label class="form-label">
                                                            <strong>{{$c['nombre']}}</strong>
                                                        </label>
                                                        <select class="form-control selectpicker show-tick" type="text" name="caracteristicas[{{$c['idx']}}][valor]">
                                                            <option value="" selected>Seleccione</option>
                                                            @foreach ($c['opciones'] as $o)
                                                                <option value="{{$o['id']}}" {{$c['valor'] == $o['id'] ? 'selected' : ''}}>{{$o['nombre']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9 col-12 text-md-right text-center change-card" data-ref="#ubicacion" data-step="paso-2">
                                <a href="#" class="btn btn-secondary">
                                Atrás
                                </a>
                            </div>
                            <div class="col-md-3 col-12 text-right">
                                <button type="submit" class="btn btn-info">
                                Continuar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (old('fotos'))
            @foreach (old('fotos') as $foto)
                <div class="foto">
                    <input class="url" type="hidden" name="fotos[{{$loop->index}}][url]" value="{{$foto['url']}}" data-thumb-url="{{'/fotos/thumb_'.substr(strrchr($foto['url'], '/'), 1)}}">
                    <input class="ancho" type="hidden" name="fotos[{{$loop->index}}][ancho]" value="{{$foto['ancho']}}">
                    <input class="alto" type="hidden" name="fotos[{{$loop->index}}][alto]" value="{{$foto['alto']}}">
                </div>
            @endforeach
        @endif
    </div>
</form>
@endsection
@section('post-scripts')
    <script src="\js\propiedades\propiedades.js"></script>
    <script src="\js\propiedades\direccionesAutocompletar.js"></script>
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAPS_API_KEY', '')}}&libraries=places"></script>
    <script type="text/javascript">
      $(function() {
        $('#usuarios-autocompletar').autocomplete({
          source: "/admin/usuarios/autocompletar",
          autoFocus: true,
          select: function(event, p) {
            $('#usuarios-autocompletar').val(p.item.email);
            $('#usuarios-id').val(p.item.id);
            return false;
          }
        }).autocomplete('instance')._renderItem = function(ul, item) {
          return $('<li>').append($('<div>').html(item.email)).appendTo(ul);
        }
        $('#barrios-autocompletar').autocomplete({
        source: function(request, response) {
        $.get("/barrios/autocompletar",
            {
                loc_place_id : $('#loc_place_id').val(),
                term : request.term
            },
            function (data) {
                response(data);
            } 
        )
        },
        autoFocus: true,
        select: function(event, p) {
        $('#barrios-autocompletar').val(p.item.nombre);
        $('#barrio-id').val(p.item.id);
        return false;
        }
        }).autocomplete('instance')._renderItem = function(ul, item) {
        return $('<li>').append($('<div>').html(item.nombre)).appendTo(ul);
        }

        CKEDITOR.replace('descripcion');

        $('#precio_convenir').on('change', function() {
            if(this.checked) {
                $('#precio_id').prop('disabled', true).val('');
                $('#moneda_id').prop('disabled', true);
            }
            else {
                $('#precio_id').prop('disabled', false);
                $('#moneda_id').prop('disabled', false);
            }
            $('#moneda_id').selectpicker('refresh');
        });

        $('form.form-disable').submit(function (e) {
          
          let submit = $(this).find('button[type=submit]');
            $(submit).prop('disabled', true);
            $(submit).html('Guardando..');
        });

        if (jQuery('.btn-file').val() == '') {
           $('.message').addClass('message-file').html("No se eligió archivo");
        }

        $('.btn-file').on("click" , function () {
            $('#file-pdf').click();
        });
        $('#file-pdf').change(function () {
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
