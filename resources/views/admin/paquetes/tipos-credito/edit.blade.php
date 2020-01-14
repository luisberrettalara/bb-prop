@extends('layouts.admin')
@section('content')
<div class="py-4">
  <div class="row justify-content-center">
    <div class="col-md-5 col-12">
      <form method="POST" action="{{ route('admin.tipos-credito.update', $tipo->id) }}" role="form">
        @method('PATCH')
        <div class="card">
          <div class="card-header">
            <h4>Editar Tipo de Crédito</h4>
          </div>
          <div class="card-body">
            <p>La edición de este <strong>Tipo de Crédito</strong> no afectará a los Paquetes ya contratados.</p>

            <div class="form-group">
              <label for="duracion_en_dias">Duración en días</label>
              <input type="number" min="1" max="9999" step="1" name="duracion_en_dias" id="duracion_en_dias" class="form-control{{ $errors->has('duracion_en_dias') ? ' is-invalid' : '' }}" value="{{ old('duracion_en_dias', $tipo->duracion_en_dias) }}">
              @if ($errors->has('duracion_en_dias'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('duracion_en_dias') }}</strong>
                </span>
              @endif
            </div>

            <div class="form-group">
              <label for="dias_al_vencimiento">Días al vencimiento</label>
              <input type="number" min="1" max="9999" step="1" name="dias_al_vencimiento" id="dias_al_vencimiento" class="form-control{{ $errors->has('dias_al_vencimiento') ? ' is-invalid' : '' }}" value="{{ old('dias_al_vencimiento', $tipo->dias_al_vencimiento) }}">
              @if ($errors->has('dias_al_vencimiento'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('dias_al_vencimiento') }}</strong>
                </span>
              @endif
            </div>

            <div class="form-group form-check">
              <input type="checkbox" class="form-check-input" name="destacado" value="1" {{ $tipo->destacado ? 'checked' : '' }}>
              <label class="form-check-label" for="destacado">Destacado</label>

              @if ($errors->has('destacado'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('destacado') }}</strong>
                </span>
              @endif
            </div>

          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-6">
                <a href="{{ route('admin.tipos-credito.index') }}" class="btn btn-info">
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