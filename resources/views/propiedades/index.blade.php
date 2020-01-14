@extends(!Auth::user() || !Auth::user()->esAdmin() ? 'layouts.app' : 'layouts.admin', ['gris' => true])
@section('content') 
<div class="perfil">
    <form action="{{route('propiedades.index')}}" method="GET">
        <div class="row mt-4">
            <div class="col-md-3 col-12 text-left mb-md-0 mb-2">
                <h3 class="texto-medium">Mis Propiedades</h3>
            </div>
            @if($propiedades->count() || $filtro != null)
                <div class="col-md-1 col-2 mt-2 offset-md-4 offset-0 text-right">
                    <strong>Ver:</strong>
                </div>
                <div class="col-md-3 col-7">
                    <div class="form-group">
                        <select id="estado_id" class="form-control" name="estado_id" type="text" value="{{ old('estado_id') }}">
                            <option value="">Todas</option>
                            
                            @foreach ($estados as $estado)
                                <option value="{{$estado->id}}" @if($filtro) {{$filtro == $estado->id? 'selected' : ''}} @endif>{{$estado->nombre_filtros}}</option>

                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-">
                    <button type="submit" class="btn btn-primary btn-verde">Buscar</button>
                </div>
            @endif
        </div>
    </form>
    
    @if(session('exito'))
      <div class="alert alert-success" role="alert"> 
        {{session('exito')}}
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger" role="alert"> 
        {{session('error')}}
      </div>
    @endif
    <div class="row">
        @if($propiedades->count())
            @foreach($propiedades as $prop)
                @include('propiedades._propiedad-perfil', array('propiedad' => 'prop'))
            @endforeach
            <div class="col-12 mt-3">
                {{ $propiedades->render() }}
            </div>
        @elseif($filtro != null)
            <div class="col-md-6 col-12 text-md-left text-center">
                <span>No se encontraron propiedades <strong>{{$estadoPropiedad}}</strong></span>
            </div>
            
        @else
            <div class="col-md-6">
                <span>Todavía no tenés propiedades cargadas, apurate a cargar la primera !</span>
                <div class="card card-vacia mt-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-5">
                                <div class="placeholder-fotos">
                                    <a class="btn-circulo btn btn-light"><i class="fas fa-heart texto-gris"></i></a>
                                </div>
                            </div>
                            <div class="col-12 col-md-7 datos">
                                <div class="row">
                                    <div class="col-9 col-md-9">
                                        <p class="placeholder-texto"></p>
                                    </div>
                                    <div class="col-3 col-md-3 d-md-inline-block d-none">
                                        <p class="placeholder-texto corto"></p>
                                    </div>
                                </div>
                                <p class="placeholder-texto mediano"></p>
                                <br />
                                <div class="row">
                                    <div class="col-12 text-left">
                                    <p class="placeholder-texto extra-corto d-md-inline-block d-none"></p>
                                    </div>
                                </div>
                                <br />
                                <div class="row mt-2">
                                    <div class="col-12 col-md-12 text-md-right text-center">
                                        <a class="btn-redondo" href="{{ route('propiedades.create') }}">Publicar una nueva propiedad</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@include('propiedades.modals.baja')
@include('propiedades.modals.finalizar')

@endsection
@section('post-scripts')
<script type="text/javascript" src="{{ asset('js/propiedades/baja.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/propiedades/finalizar.js') }}"></script>
@endsection