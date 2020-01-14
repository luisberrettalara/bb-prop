@extends('layouts.admin')
@section('content')
<div class="py-4">
	<div class="row justify-content-center">
		<div class="col-md-5 col-12">
			<form method="POST" action="{{route('admin.caracteristicas.opciones.update', [$caracteristica->id, $opcion->id])}}" role="form">
			@csrf
	    	@method('PATCH')
				<div class="card">
					<div class="card-header">
						<h4>Editar opción</h4>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<div class="form-group">
									<label for="nombre" class="form-label"><strong>Nombre</strong></label>
									<input type="text" name="nombre" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{$opcion->nombre}}">
									@if ($errors->has('nombre'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('nombre') }}</strong>
                    </span>
              		@endif
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
              <div class="col-6">
                  <a href="{{ route('admin.caracteristicas.opciones.index', $caracteristica->id) }}" class="btn btn-info">
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