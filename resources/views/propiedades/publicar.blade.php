@extends('layouts.app')
@section('content')
<div class="py-4">
  <div class="row">
    <div class="col-12">
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

      <form method="POST" action="{{ route('propiedades.publicar', $propiedad) }}">
        <div class="row">
          <div class="col-12 mb-0">
              <h3>Publicá tu Propiedad</h3>
              <p>Para poder publicar tu propiedad en el Buscador primero debes asignarle un Crédito. Tené en cuenta que al publicar una Propiedad con un Crédito no podrás volver a usarlo.</p>
          </div>
        </div>
        <div class="row">
          @forelse($creditos as $credito)
            <div class="col-md-3 col-6 mt-4">
              <div class="card card-credito">
                <div class="card-header text-center">
                  <span class="tick"></span>
                  <h5>Crédito {{$credito->destacado ? 'Destacado' : 'Standard'}}</h5>
                </div>
                <div class="card-body text-center">
                  <span>Disponibles</span>
                  <h4>{{$credito->total}}</h4>
                  <span>Duración</span>
                  <h4>{{$credito->dias_totales}} días</h4>
                  <span>Vencimiento</span>
                  <h5>{{$credito->fecha_vencimiento->format('d/m/Y')}}</h5>
                  <input type="hidden" name="credito" class="input-credito" value="{{ $credito->id }}" disabled>
                </div>
              </div>
            </div>
          @empty
            <div class="col-12">
              No tienes Créditos disponibles para publicar, <a href="{{ route('paquetes.comprar') }}">Comprá un Paquete ahora</a>.
            </div>
          @endforelse
        </div>

        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <div class="row mt-5">
          <div class="col-6">
              <a class="btn btn-secondary" href="{{ route('propiedades.index') }}">Más Tarde</a>
          </div>
          <div class="col-6 text-right">
              <button class="btn btn-verde">Publicar ahora</button>
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
      $('.card-credito').on('click', function(){
        $('.card-credito').removeClass('seleccionada');
        $(this).addClass('seleccionada');
        $('.input-credito').prop('disabled', true);
        $(this).find('.input-credito').prop('disabled', false);
      });
    });
  </script>
@endsection