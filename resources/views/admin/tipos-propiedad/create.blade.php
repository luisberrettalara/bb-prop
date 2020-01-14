@extends('layouts.admin')
@section('content')
<div class="py-4">
	<div class="row justify-content-center">
		<div class="col-md-5 col-12">
			<form method="POST" action="{{ route('admin.tipos-propiedad.store') }}" role="form">
			@csrf
				<div class="card">
					<div class="card-header">
						<h4>Nuevo Tipo de Propiedad</h4>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<div class="form-group">
									<input type="text" name="nombre" id="nombre" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" placeholder="Nombre">
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
                  <a href="{{ route('admin.tipos-propiedad.index') }}" class="btn btn-info">
                  Atrás
                  </a>
              </div>
              <div class="col-6 text-right">
                  <button type="submit" class="btn btn-success">
                  Crear
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