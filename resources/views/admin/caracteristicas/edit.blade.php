@extends('layouts.admin')
@section('content')
<div class="py-4">
  <div class="row justify-content-center">
    <div class="col-md-5 col-12">
      <form method="POST" action="{{ route('admin.caracteristicas.update',$caracteristica->id) }}" role="form">
      @csrf
        @method('PATCH')
        <div class="card">
          <div class="card-header">
            <h4>Editar Operación</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="tipo_caracteristica_id" class="form-label"><strong>Tipo Característica</strong></label>
                    <select id="tipo_caracteristica_id" type="text" class="form-control{{ $errors->has('tipo_caracteristica_id') ? ' is-invalid' : '' }}" name="tipo_caracteristica_id" value="{{ old('tipo_caracteristica_id') }}">
                        <option value="" disabled selected>Seleccione</option>
                       @foreach ($tipos as $tip)
                          <option value="{{$tip->id}}" {{ old('tipo_caracteristica_id', $caracteristica->tipo_caracteristica_id) == $tip->id? 'selected':'' }} >{{$tip->nombre}}</option>
                       @endforeach
                    </select>
                    @if ($errors->has('tipo_caracteristica_id'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('tipo_caracteristica_id') }}</strong>
                      </span>
                    @endif
                </div>
                <div class="form-group">
                  <label for="nombre" class="form-label"><strong>Nombre</strong></label>
                  <input type="text" name="nombre" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{ old('nombre', $caracteristica->nombre) }}">
                  @if ($errors->has('nombre'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('nombre') }}</strong>
                    </span>
                  @endif
                </div>
                <div class="form-group mb-0">
                  <label for="es_servicio" class="form-label"><strong>Es servicio</strong></label>
                </div>
                <div class="form-group">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input{{ $errors->has('es_servicio') ? ' is-invalid' : '' }}" name="es_servicio" type="checkbox" value="1" {{old('es_servicio', $caracteristica->es_servicio) ?'checked':''}}>
                    </div>
                    @if ($errors->has('es_servicio'))
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $errors->first('es_servicio') }}</strong>
                        </span>
                    @endif
                </div>
                <div id="grupo-unidad" class="form-group" style="{{$caracteristica->esDropdown() || $caracteristica->esOpcion() ? 'display: none;' : 'display:block;'}}">
                  <label for="unidad" class="form-label"><strong>Unidad</strong></label>
                  <input type="text" name="unidad" class="form-control{{ $errors->has('unidad') ? ' is-invalid' : '' }}" value="{{ old('unidad', $caracteristica->unidad) }}">
                  @if ($errors->has('unidad'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('unidad') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-6">
                  <a href="{{ route('admin.caracteristicas.index') }}" class="btn btn-info">
                  Atrás
                  </a>
              </div>
              <div class="col-6 text-right">
                  <button type="submit" class="btn btn-success">
                  Guardar
                  </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@section('post-scripts')
<script>
  $(document).ready(function () {
    $("#tipo_caracteristica_id").on("change", function () {
        let seleccionada = $(this).find('option:selected').text();
        if(seleccionada == 'Dropdown' ||seleccionada == "Opcion") {
            $("#grupo-unidad").hide();
        }
        else {
            $("#grupo-unidad").show();
        }
    })
  });
</script>
@endsection