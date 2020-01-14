<div class="card card-propiedad {{$destacada? 'destacada' : ''}}">
  <div class="card-body">
    <div class="row">
      <a href="{{route('propiedades.show', $propiedad->slug)}}">
      <div class="col-md-5 col-12">
        @if ($propiedad->fotoPortada)
          <img src="{{$propiedad->fotoPortada->thumb_url}}" class="foto">
        @else
          <div class="placeholder-fotos"></div>
        @endif 
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
      </a>
      <div class="col-md-7 col-12 datos">
        <a href="{{route('propiedades.show', $propiedad->slug)}}" class="link-propiedad"><h5>{{$propiedad->getTituloCorto()}}</h5></a>
        <p>{!!$propiedad->getDescripcionCorta()!!}</p> 
        <div class="row">
            <div class="col">
                <span>{{$propiedad->tipoPropiedad->nombre}}</span>
                @if($propiedad->superficie>0)
                  <span>&nbsp;|&nbsp;</span>
                  <span>
                  {{number_format($propiedad->superficie,0, ',', '.')}}{{$propiedad->getUnidad()}}.</span>
                @endif
                <span>&nbsp;|&nbsp;</span>
                <span>{{$propiedad->operacion->nombre}}</span>
            </div>
        </div>
        <div class="row {{$destacada? 'mt-5' : 'mt-3'}}">
          <div class="col-7">
            <strong class="precio-convenir">{{ $propiedad->getPrecioFormato() }}</strong>
          </div>
          <div class="col-5 text-right">
            <a href="{{route('propiedades.show', $propiedad->slug)}}" data-razon-social="{{ $propiedad->usuario->razon_social }}" class="btn-redondo grande btn-modal-contactar">CONTACTAR</a>
          </div>
        </div>
      </div>
    </div>
  </div> 
</div>