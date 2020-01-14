@extends(!Auth::user() || !Auth::user()->esAdmin() ? 'layouts.app' : 'layouts.admin', ['gris' => true])
@section('content')
<div class="perfil">
  <div class="row">
    <div class="col-12 mt-4 text-md-left text-center">
      <h3>Propiedades favoritas</h3>
    </div>
    @if($favoritas->count())
      @foreach($favoritas as $favorita)
        <div class="col-12 col-md-6 mt-3">
          @include('propiedades._propiedad-home', array('propiedad' => $favorita, 'destacada' => false))
        </div>
      @endforeach
    @else
      <div class="col-12 col-md-6">
        <span>Todavía no tenés propiedades marcadas como favoritas !</span>
            <div class="card mt-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-5">
                            <div class="placeholder-fotos">
                                <a class="btn-circulo btn btn-light"><i class="fas fa-heart texto-gris"></i></a>
                            </div>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="row">
                                <div class="col-9 col-md-9">
                                    <p class="placeholder-texto"></p>
                                </div>
                                <div class="col-3 col-md-3">
                                    <p class="placeholder-texto corto"></p>
                                </div>
                            </div>
                            <p class="placeholder-texto mediano"></p>
                            <br />
                            <p class="placeholder-texto extra-corto"></p>
                            <br />
                            <div class="row mt-4">
                                <div class="col-6 mt-2">
                                    <p class="placeholder-texto"></p>
                                </div>
                                <div class="col-6">
                                    <p class="placeholder-boton"></p>
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
@include('propiedades.modals.compartir')
@include('propiedades.modals.contactar')
@endsection
@section('post-scripts')
<script type="text/javascript" src="{{ asset('js/propiedades/favorita.js') }}"></script>
@endsection