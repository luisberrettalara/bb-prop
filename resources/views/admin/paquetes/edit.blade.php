@extends('layouts.admin')
@section('content')
<div class="py-4">
  <div class="row justify-content-center">
    <div class="col-12">
      <form method="POST" action="{{ route('admin.paquetes.update', $paquete) }}" role="form">
        @method('PATCH')
        <div class="card">
          <div class="card-header">
            <h4>Editar {{ $paquete }}</h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="nombre">Nombre</label>
              <input type="text" name="nombre" id="nombre" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{ old('nombre', $paquete->nombre) }}" required="">
              @if ($errors->has('nombre'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('nombre') }}</strong>
                </span>
              @endif
            </div>

            <div class="form-group">
              <label for="precio">Precio sin comisión</label>
              <input type="number" min="0" max="9999999" step="0.01" name="precio" id="precio" class="form-control{{ $errors->has('precio') ? ' is-invalid' : '' }}" value="{{ old('precio', $paquete->precio) }}" required>
              @if ($errors->has('precio'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('precio') }}</strong>
                </span>
              @endif
            </div>
            <div class="row">
              <div class="col-md-8 col-12">
                <div class="form-group">
                  <select class="form-control{{ $errors->has('icono_url') ? ' is-invalid' : '' }}" name="icono_url" value="{{ old('icono_url') }}" id="icono_url_id">
                    <option value="" disabled selected>Seleccione un ícono</option>
                    <option value="/images/house-1.svg" {{old('icono_url', $paquete->icono_url) == '/images/house-1.svg'? 'selected' : ''}}>Casa muy pequeña</option>
                    <option value="/images/house-2.svg" {{old('icono_url', $paquete->icono_url) == '/images/house-2.svg'? 'selected' : ''}}>Casa pequeña</option>
                    <option value="/images/house-3.svg" {{old('icono_url', $paquete->icono_url) == '/images/house-3.svg'? 'selected' : ''}}>Casa mediana</option>
                    <option value="/images/house-4.svg" {{old('icono_url', $paquete->icono_url) == '/images/house-4.svg'? 'selected' : ''}}>Casa grande</option>
                    <option value="/images/house-5.svg" {{old('icono_url', $paquete->icono_url) == '/images/house-5.svg'? 'selected' : ''}}>Casa muy grande</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4 col-12 text-center">
                <img id="img_url_id" src="{{$paquete->icono_url}}" min-height="100px">
              </div>
            </div>
            <div class="form-group form-check">
              <input type="checkbox" class="form-check-input" name="disponible" value="1" {{ old('disponible', $paquete->disponible)?'checked':'' }}>
              <label class="form-check-label" for="disponible">Disponible</label>

              @if ($errors->has('disponible'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('disponible') }}</strong>
                </span>
              @endif
            </div>
           <div class="form-group form-check">
              <input type="checkbox" class="form-check-input" name="por_defecto" value="1" {{ old('por_defecto', $paquete->por_defecto)?'checked':'' }}>
              <label class="form-check-label" for="por_defecto">Paquete por defecto</label>

              @if ($errors->has('por_defecto'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('por_defecto') }}</strong>
                </span>
              @endif
            </div>
            <h3>Créditos a incluir</h3>
            <div class="table-container table-responsive">
              <table class="table table-striped">
               <thead>
                 <th>Días de Duración</th>
                 <th>Destacado</th>
                 <th>Días al Vencimiento</th>
                 <th>Cantidad</th>
               </thead>
                <tbody>
                  @if($creditos->count())
                  @foreach($creditos as $tipo)
                  <tr class="{{ $errors->has('creditos.' . $tipo->id) ? 'table-danger' : '' }}">
                    <td>{{ $tipo->duracion_en_dias }}</td>
                    <td>{{ $tipo->destacado ? 'Destacado' : 'Standard' }}</td>
                    <td>{{ $tipo->dias_al_vencimiento }}</td>
                    <td>
                      <input type="number" name="creditos[{{ $tipo->id}}]" min="1" step="1" class="form-control" value="{{ old('creditos.' . $tipo->id, $paquete->getCantidadParaTipo($tipo)) }}">
                    </td>
                  </tr>
                  @endforeach 
                  @else
                   <tr>
                    <td colspan="4">Aún no hay registros disponibles</td>
                  </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-6">
                <a href="{{ route('admin.paquetes.index') }}" class="btn btn-info">
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
<script type="text/javascript">
  $(function() {
    $('#icono_url_id').on('change', function() {
      $('#img_url_id').attr('src', $(this).val());
    });
  });
</script>
@endsection