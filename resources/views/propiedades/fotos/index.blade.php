@extends('layouts.app')
@section('content')
	<h1>Fotos de {{$propiedad->titulo}}</h1>
	<form action="{{ route('propiedades.fotos.store', $propiedad->id) }}" enctype="multipart/form-data" method="post">
		@csrf
	<input type="file" class="form-control{{ $errors->has('foto') ? ' is-invalid' : '' }}" name="foto" value="{{ old('foto') }}">
    @if ($errors->has('foto'))
    <span class="invalid-feedback" role="alert">
        <strong>{{ $errors->first('foto') }}</strong>
    </span>
	@endif
	<textarea name="descripcion"></textarea>
	<br>
	<button type="submit" name="button">Subir Foto</button>
	</form>
	@foreach($propiedad->fotos as $foto)
		<img src="{{$foto->url}}" width="200px" />
	@endforeach
@endsection