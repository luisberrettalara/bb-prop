<div class="col-12 col-md-6 mb-2 mt-3">
  <div class="card propiedad-perfil">
    <div class="card-body">
      <div class="row">
        <div class="col-md-5 col-12">
          <a href="{{route('propiedades.show', $prop->slug)}}">
            @if ($prop->fotoPortada)
              <img src="{{$prop->fotoPortada->thumb_url}}" class="img-fluid">
            @else
              <div class="placeholder-fotos"></div>
            @endif
          </a>
        </div>
        <div class="col-md-7 col-12 datos">
          <h5><a href="{{route('propiedades.show', $prop->slug)}}" class="link-propiedad">{{$prop->getTituloCorto()}}</a></h5>
          <p>{!!$prop->getDescripcionCorta()!!}</p>
          <p class="mb-2 texto-semibold">
            {{$prop->tipoPropiedad->nombre}}&nbsp;|&nbsp;
            @if($prop->superficie > 0)
              {{number_format($prop->superficie, 0, ',', '.')}}{{$prop->getUnidad()}} &nbsp;|&nbsp;
            @endif
            {{$prop->operacion->nombre}}
          </p>
          <div class="row">
            <div class="col-6">
              <strong class="precio-convenir">{{ $prop->getPrecioFormato() }} </strong>
            </div>
            <div class="col-6 text-right">
              Estado: <strong>{{ $prop->estado }}</strong>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-7 offset-md-5 text-md-right text-center mb-2 mt-2">
          @if ($prop->sePuedePublicar())
            <a class="btn btn-outline-link btn-sm" href="{{route('propiedades.publicar', $prop)}}">
            Publicar
            </a> 
          @endif

          @if ($prop->sePuedePausar())
          <a class="btn btn-outline-link btn-sm" href="{{route('propiedades.pausar', $prop)}}">
            Pausar
          </a>
          @endif

          @if ($prop->sePuedeReanudar())
          <a class="btn btn-outline-link btn-sm" href="{{route('propiedades.reanudar', $prop)}}">
            Reanudar
          </a>
          @endif

          @if ($prop->sePuedeFinalizar())
          <a class="btn btn-outline-link btn-sm btn-modal-finalizar" href="{{route('propiedades.finalizar', $prop)}}">
            Finalizar
          </a>
          @endif

          <a class="btn btn-outline-link btn-sm" href="{{route('propiedades.edit', $prop)}}">
             Editar
          </a>

          @if ($prop->sePuedeDarDeBaja())
            <a class="btn btn-outline-danger btn-sm btn-modal-baja" href="{{route('propiedades.baja', $prop)}}">Dar de Baja</a>
          @endif

          <a class="btn btn-outline-secondary btn-sm" href="{{route('propiedades.imprimir', $prop)}}">
            <img src="{{asset('/images/pdf-black.svg')}}">
          </a>
        </div>
      </div>
    </div>
  </div>
</div>