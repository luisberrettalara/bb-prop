@extends('layouts.admin')
@section('content')
<div class="py-4">
	<div class="row justify-content-center">
		<div class="col-md-5 col-12">
			<form method="POST" action="{{ route('admin.barrios.update', $barrio->id) }}" role="form">
			@csrf
	    	@method('PATCH')
				<div class="card">
					<div class="card-header">
						<h4>Editar Barrio</h4>
					</div>	
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<div class="form-group">
                	<label for="provincia_id" class="form-label"><strong>Provincia</strong></label>
                    <select id="provincia_id" class="form-control{{ $errors->has('provincia_id') ? ' is-invalid' : '' }}" name="provincia_id" value="{{ old('provincia_id') }}">
                      <option value="" disabled selected>Seleccione</option>
                      @foreach ($provincias as $provincia)
                          <option value="{{$provincia->id}}" {{old('provincia_id', $barrio->localidad->provincia->id) == $provincia->id? 'selected':''}}>{{$provincia->nombre}}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('provincia_id'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('provincia_id') }}</strong>
                      </span>
                    @endif
            		</div>
								<div class="form-group">
                	<label for="localidad" class="form-label"><strong>Localidad</strong></label>
                  <input id="localidad_id" type="text" class="form-control{{ $errors->has('localidad') ? ' is-invalid' : '' }}" name="localidad" value="{{ old('localidad', $barrio->localidad->nombre) }}">
                    <ul id="predicciones_localidad" class="menu-predicciones"></ul>
                    @if ($errors->has('localidad'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('localidad') }}</strong>
                        </span>
                    @endif
            		</div>
								<div class="form-group">
									<label for="nombre" class="form-label"><strong>Nombre</strong></label>
									<input type="text" name="nombre" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{$barrio->nombre}}">
									@if ($errors->has('nombre'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('nombre') }}</strong>
                    </span>
              		@endif
								</div>
								<input id="loc_place_id" type="hidden" name="loc_place_id" value="{{$barrio->localidad->google_place_id}}">
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
              <div class="col-6">
                  <a href="{{ route('admin.barrios.index') }}" class="btn btn-info">
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
  <script src="\js\propiedades\direccionesAutocompletar.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAPS_API_KEY', '')}}&libraries=places"></script>
@endsection