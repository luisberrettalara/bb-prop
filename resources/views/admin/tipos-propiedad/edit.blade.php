@extends('layouts.admin')
@section('content')
<div class="py-4">
	<div class="row justify-content-center">
		<div class="col-md-5 col-12">
			<form method="POST" action="{{ route('admin.tipos-propiedad.update',$tipos->id) }}" role="form">
			@csrf
	    	@method('PATCH')
				<div class="card">
					<div class="card-header">
						<h4>Editar tipo de propiedad</h4>	
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<div class="form-group">
									<input type="text" name="nombre" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{$tipos->nombre}}">
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