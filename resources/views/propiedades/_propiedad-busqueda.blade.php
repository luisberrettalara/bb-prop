<div class="card card-propiedad filtro">
  <div class="card-body">
    <div class="row">
      <div class="col-12">
        <a href="{{route('propiedades.show', $propiedad->slug)}}" class="link-propiedad d-none d-sm-block"><h5>{{$propiedad->getTituloCorto()}}</h5></a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 col-12">
        @if ($propiedad->fotoPortada)
          <a href="{{route('propiedades.show', $propiedad->slug)}}"><img src="{{$propiedad->fotoPortada->thumb_url}}" class="foto"></a>
        @else
          <a href="{{route('propiedades.show', $propiedad->slug)}}"><div class="placeholder-fotos"></div></a>
        @endif
        <a href="{{route('propiedades.show', $propiedad->slug)}}" class="link-propiedad d-block d-sm-none"><h5>{{$propiedad->getTituloCorto()}}</h5></a>
        <div class="iconos">
          <a href="{{route('propiedades.show', $propiedad->slug)}}" data-toggle="modal" data-target="#modal-compartir" data-propiedad="{{ $propiedad->id }}" class="{{Auth::user()? 'share-log':'share'}}">
           <img src="{{ asset('images/compartir.png') }}">  
          </a>
          @if(!Auth::user() || !Auth::user()->favoritas->find($propiedad->id))
            <a href="{{Auth::user()? route('propiedades.users.fav', [$propiedad->id, Auth::user()? Auth::user()->id : '']) : route('login')}}" class="favorita unlike">
            </a>
          @else
             <a href="{{route('propiedades.users.fav', [$propiedad->id, Auth::user()? Auth::user()->id : ''])}}" class="favorita like">
            </a>
          @endif
        </div>
      </div>
      <div class="col-md-8">
        <p class="mt-1">{!!$propiedad->getDescripcionCorta()!!}</p>
          <div class="row pt-4">
            <div class="col">
              <span>{{$propiedad->tipoPropiedad->nombre}}</span>
              @if($propiedad->superficie>0)
                <span>&nbsp;|&nbsp;</span>
                <span>{{number_format($propiedad->superficie,0, ',', '.')}}{{$propiedad->getUnidad()}}.</span>
              @endif
              <span>&nbsp;|&nbsp;</span>
              <span>{{$propiedad->operacion->nombre}}</span>
            </div>
          </div>
          <div class="row mt-5">
            <div class="col-md-6 col-7">
              @if ($propiedad->monto>0 && $propiedad->precio_convenir==0)
                <strong class="texto-precio">{{$propiedad->moneda->nombre}} {{number_format($propiedad->monto,0, ',', '.')}}</strong>
              @else
                <strong class="precio-convenir">Consultar Precio</strong>
              @endif
            </div>
            <div class="col-md-6 col-5 text-right">
                <a href="{{route('propiedades.show', $propiedad->slug)}}" data-razon-social="{{ $propiedad->usuario->razon_social }}" class="btn-redondo grande btn-modal-contactar mr-4">CONTACTAR</a>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>