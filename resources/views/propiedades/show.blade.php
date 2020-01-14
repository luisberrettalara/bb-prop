@extends(!Auth::user() || !Auth::user()->esAdmin() ? 'layouts.app' : 'layouts.admin', ['container' => true])

@section('title')
{{ $propiedad->getTituloCorto() . ' - ' . config('app.name', 'La Nueva') }}
@endsection

@section('post-head')
  <meta property="og:url" content="{{ url(route('propiedades.show', $propiedad->slug)) }}" />
  <meta property="og:title" content="{{ $propiedad->getTituloCorto() . ' - ' . config('app.name', 'La Nueva') }}" />
  <meta property="og:description" content="{{$propiedad->tipoPropiedad->nombre}} en {{$propiedad->operacion->nombre}} - {{ $propiedad->getDescripcionCorta() }}" />
  @if($propiedad->fotoPortada)
  <meta property="og:image" content="{{ url($propiedad->fotoPortada->thumb_url) }}" />
  <meta property="og:image:width" content="370">
  <meta property="og:image:height" content="270">
  @endif
  <meta property="og:type" content="website" />
  <meta property="fb:app_id" content="{{ config('fb.app.id', '') }}" />
@endsection

@section('content')

@if(session('exito'))
  <div class="container py-3">
    <div class="alert alert-success" role="alert"> 
      {{session('exito')}} 
    </div>
  </div>
@endif

@if(session('info'))
  <div class="container py-3">
    <div class="alert alert-info" role="alert"> 
      {{session('info')}} 
    </div>
  </div>
@endif

@if ($propiedad->estaCreada())
  <div class="container py-3">
    <div class="alert alert-info" role="alert">
      Tu Propiedad ha sido <strong>Creada</strong>, ahora sólo tienes que
      <a href="{{ route('propiedades.publicar', $propiedad) }}">Publicarla</a>.
    </div>
  </div>
@endif

@if ($propiedad->estaPausada())
  <div class="container py-3">
    <div class="alert alert-info" role="alert">
      Esta Publicación está <strong>Pausada</strong> por lo que sólo tú puedes verla,
      <a href="{{ route('propiedades.reanudar', $propiedad) }}">Reanudar Publicación</a>.
    </div>
  </div>
@endif

@if ($propiedad->estaFinalizada())
  <div class="container py-3">
    <div class="alert alert-info" role="alert">
      Esta Publicación está <strong>Finalizada</strong> por lo que sólo tú puedes verla,
      <a href="{{ route('propiedades.publicar', $propiedad) }}">Publicarla nuevamente</a>.
    </div>
  </div>
@endif

<div class="banner mb-4">
  <div id="fotos" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
      @foreach($propiedad->fotos as $foto)
      <div class="carousel-item {{$loop->first ? ' active': ''}}" data-idx="{{$loop->index}}">
        <img src="{{$foto->url}}" class="d-block w-100" data-w="{{$foto->ancho}}" data-h="{{$foto->alto}}">
      </div>
      @endforeach 
    </div>
    <a class="carousel-control-prev" href="#fotos" role="button" data-slide="prev">
      <span class="slider-control left"><i class="fa fa-chevron-left"></i></span>
    </a>
    <a class="carousel-control-next" href="#fotos" role="button" data-slide="next">
      <span class="slider-control right"><i class="fa fa-chevron-right"></i></span>
    </a>
  </div>
  <div class="icono">
    <div class="d-none d-md-block">
      <a href="{{route('propiedades.show', $propiedad->slug)}}" data-toggle="modal" data-target="#modal-compartir" data-propiedad="{{ $propiedad->id }}" class="btn btn-redondo btn-fav btn-blanco">
       <i class="fas fa-share-alt fa-lg"></i> Compartir
      </a>
        @if(!Auth::user() || !Auth::user()->favoritas->find($propiedad->id))
          <a href="{{ Auth::user()? route('propiedades.users.fav', [$propiedad->id, Auth::user()? Auth::user()->id : '']) : route('login')}}" class="btn btn-redondo btn-fav btn-blanco favorita">
            <i class="far fa-heart fa-lg"></i> <span>Añadir a favoritos</span>
          </a>
        @else
          <a href="{{route('propiedades.users.fav', [$propiedad->id, Auth::user()? Auth::user()->id : ''])}}" class="btn btn-redondo btn-fav btn-blanco favorita">
            <i class="fas fa-heart fa-lg text-danger"></i> <span>Quitar de favoritos</span>
          </a>
        @endif
    </div>
  </div>
</div>
<div class="container">
  <div class="row no-gutters">
    <div class="col-12 col-md-7 pr-md-4">
      <span class="texto-azul texto-medium">{{$propiedad->tipoPropiedad->nombre}} en {{$propiedad->operacion->nombre}}</span>
      <h3 class="titulo mt-2">{{$propiedad->titulo}}</h3>

      <section class="descripcion mt-4 mb-4">{!!$propiedad->descripcion!!}</section>

      @if($propiedad->tieneVideo())
      <iframe class="mt-4" width="100%" height="315" src="https://www.youtube.com/embed/{{ $propiedad->getYoutubeVideoId() }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      @endif

      @if($propiedad->ubicacion_publica == 1)
        <section class="ubicacion mt-4 mb-4">
          <h4>Ubicación</h4>
          <p>
            {{ $propiedad->getUbicacionFormateada() }}
            @if ($propiedad->tieneReferenciaDeUbicacion())
              <br/><strong>Referencia:</strong> {{ $propiedad->referencia }}
            @endif
          </p>
        </section>
      @endif
      <input id="lat" type="hidden" name="lat" value="{{ $propiedad->lat }}">
      <input id="long" type="hidden" name="long" value="{{ $propiedad->long }}">
      <div id="map" class="mapa-mobile mt-4 d-none"></div>

      <p class="descripcion-mapa-mobile d-block d-md-none text-center">Hacé click en el mapa para poder interactuar</p>
      <div class="mapa">
        <img class="foto-mapa" src="{{$propiedad->imagen_mapa}}">
        <div class="overlay">
          <p class="descripcion-mapa">Hacé click en el mapa para poder interactuar</p>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-5">
      <div class="card card-celeste py-3 pl-3 pr-1 mt-3 {{ $propiedad->tieneFotos() ? 'card-desplazada' : '' }}">
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-6 mt-3">
              <h1 class="precio-propiedad">{{$propiedad->getPrecioFormato()}}</h1>
            </div>
            @if ($propiedad->expensas>0)
              <div class="col-12 col-md-6 mt-3">
                <img src="{{asset('/images/calculator.svg')}}">
                <p class="texto-gris mb-1">Expensas aproximadas</p>
                <h4>${{number_format($propiedad->expensas, 0, ',', '.')}}</h4>
              </div>
            @endif
          </div>
          <div class="row">
            @if ($propiedad->superficie>0)
            <div class="col-12 col-md-6 mt-3">
              <img src="{{asset('/images/superficie.svg')}}">
              <p class="texto-gris mb-1">Superficie Total</p>
              <h4>{{number_format($propiedad->superficie,0, ',', '.')}} {{$propiedad->getUnidad()}}</h4>
            </div>
            @endif
            @if ($propiedad->superficie_cubierta>0)
            <div class="col-12 col-md-6 mt-3">
              <img src="{{asset('/images/supCub.svg')}}">
              <p class="texto-gris mb-1">Superficie Cubierta</p>
              <h4>{{number_format($propiedad->superficie_cubierta, 0, ',', '.')}} m2</h4>
            </div>
            @endif
          </div>
          <div class="row">
            <div class="col-12 col-md-6 mt-3">
              <img src="{{asset('/images/edificio.svg')}}">
              <p class="texto-gris mb-1">Inmueble</p>
              <h4>{{$propiedad->tipoPropiedad->nombre}}</h4>
            </div>
            <div class="col-12 col-md-6 mt-3">
              <img src="{{asset('/images/llave.svg')}}">
              <p class="texto-gris mb-1">Operación</p>
              <h4>{{$propiedad->operacion->nombre}}</h4>
            </div>
          </div>
          <div class="row">
            @if ($propiedad->tipologia!=null || $propiedad->tipologia!='')
              <div class="col-12 col-md-6 mt-3">
                <img src="{{asset('/images/bed.svg')}}">
                <p class="texto-gris mb-1">Tipología</p>
                <h4>{{$propiedad->tipologia->nombre}}</h4>
              </div>
            @endif
            <div class="col-12 col-md-6 mt-3">
              <img src="{{asset('/images/usos.svg')}}" class="mb-4">
              <p class="texto-gris mb-1">Usos Permitidos</p>
              @foreach($propiedad->usos as $uso)
              <h4>{{$uso->nombre}}</h4>
              @endforeach
            </div>
          </div>
          @if ($propiedad->pdf_url)
            <div class="row mt-3">
              <div class="col-12">
                <img src="{{asset('/images/pdf.svg')}}" class="img-pdf">
                <a href="{{route('propiedades.descargar', $propiedad->id)}}" class="texto-azul" style="margin-left: 25px;">Descargar ficha del inmueble</a>
              </div>
            </div>
          @endif
        </div>
      </div>

      @if(count($propiedad->caracteristicasFormateadas()) > 0)
      <div class="card card-celeste card-caracteristicas mt-4">
        <div class="card-body">
          <div class="row mt-2">
            @if(array_key_exists('basicas', $propiedad->caracteristicasFormateadas()))
              <div class="col-12 col-md-6">
                <p class="texto-gris mb-1">Características</p>
                @for($i = 0; $i < count($propiedad->caracteristicasFormateadas()['basicas']); $i++)
                  @if($propiedad->caracteristicasFormateadas()['basicas'][$i]['opcion'])
                    <p class="texto-medium mb-0">{{$propiedad->caracteristicasFormateadas()['basicas'][$i]['titulo']}}</p>
                  @else
                    <p class="texto-gris mb-1 mt-3">{{$propiedad->caracteristicasFormateadas()['basicas'][$i]['titulo']}}</p>
                    <p class="texto-medium mb-0">
                      {{$propiedad->caracteristicasFormateadas()['basicas'][$i]['valor']}}
                      {{$propiedad->caracteristicasFormateadas()['basicas'][$i]['unidad']}}
                    </p>
                  @endif
                @endfor
              </div>
            @endif
            @if(array_key_exists('servicios', $propiedad->caracteristicasFormateadas()))
              <div class="col-12 col-md-6">
                <p class="texto-gris mb-1">Amenities</p>
                @for($i = 0; $i < count($propiedad->caracteristicasFormateadas()['servicios']); $i++)
                  @if($propiedad->caracteristicasFormateadas()['servicios'][$i]['opcion'])
                    <p class="texto-medium mb-0">{{$propiedad->caracteristicasFormateadas()['servicios'][$i]['titulo']}}</p>
                  @else
                    <p class="texto-gris mb-1 mt-3">{{$propiedad->caracteristicasFormateadas()['servicios'][$i]['titulo']}}</p>
                    <p class="texto-medium mb-0">
                      {{$propiedad->caracteristicasFormateadas()['servicios'][$i]['valor']}}
                      {{$propiedad->caracteristicasFormateadas()['servicios'][$i]['unidad']}}
                    </p>
                  @endif
                @endfor
              </div>
            @endif
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
  <span class="divisor-propiedad mt-4"></span>
  <h2 class="mt-5">{{$propiedad->usuario->razon_social}}</h2> 
  <div class="row">
    <div class="col-12 col-md-7">
      <div class="row mt-4">
        <div class="col-12 col-md-4 text-center">
          @if ($propiedad->usuario->foto_url)
            <img src="{{$propiedad->usuario->foto_url}}" class="img-fluid">
          @else
            <div class="foto-vacia">
              <img src="{{asset('/images/camera.svg')}}">
              <p>Sin foto</p>
            </div>
          @endif
        </div>
        <div class="col-12 col-md-8 mt-2">
          <p>
          {{$propiedad->usuario->descripcion}}
          </p>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-5 mt-4">
      <div class="card card-contacto" id="contacto">
        <div class="card-body">
          <div class="row no-gutters">
            <div class="col-12 d-block px-4 pt-3">
              @if(!$mostrarDatosDeContacto)
              <div class="row row-degrade">
                <div class="col-12 col-md-6 degrade-contacto">
                  <p><img src="{{asset('/images/blackphone.svg')}}"> <span>{{$propiedad->usuario->getTelefonoRecortado()}}</span></p>
                  <p><img src="{{asset('/images/correo.svg')}}"> <span>{{$propiedad->usuario->getEmailRecortado()}}</span></p>
                </div>
                <div class="col-12 col-md-6 text-center pt-2">
                   <button type="button" class="btn-verde btn-redondo btn-comprobar-interesado">Contactar</button>
                </div>
              </div>
              @endif
              <div class="row">
                <div class="col-12">
                  <form class="form-interesarse" style="display: none;" action="{{route('propiedades.interesarse', $propiedad->id)}}" method="POST">
                    <p>Colocá tu email para poder ver los datos de <strong>{{$propiedad->usuario->razon_social}}</strong>:</p>

                    <div class="form-group">
                      <input type="email" name="email" placeholder="Email" class="form-control" required>
                    </div>

                    <div class="form-check">
                      <input type="checkbox" name="suscribirse" value="1" class="form-check-input" checked>
                      <label for="suscribirse" class="form-check-label">Quiero recibir novedades sobre Inmuebles similares</label>
                    </div>
                    <div class="mt-2 mb-4 recaptcha">
                      <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
                      <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group text-center text-md-right">
                      <button type="submit" class="btn-verde btn-redondo btn-continuar">Continuar</button>
                    </div>
                  </form>

                  <form class="form-contactar" style="display: {{ $mostrarDatosDeContacto ? 'block' : 'none' }};" action="{{route('propiedades.contactar', $propiedad->id)}}" method="POST">
                    @if($propiedad->usuario->telefono_celular)
                      <p>
                        <a class="btn btn-verde btn-redondo btn-block" href="{{$propiedad->getLinkWhatsApp(url(route('propiedades.show', $propiedad->slug)))}}"><i class="fab fa-whatsapp"></i> Contactar por WhatsApp</a>
                      </p>
                      <hr>
                    @endif
                    <p>
                      <img src="{{asset('/images/blackphone.svg')}}">
                      <span class="telefono-contacto">Llamanos {{ $mostrarDatosDeContacto ? $propiedad->usuario->telefono : '' }}</span>
                    </p>
                    <p>
                      <img src="{{asset('/images/correo.svg')}}">
                      <span class="email-contacto">{{ $mostrarDatosDeContacto ? $propiedad->usuario->email : '' }}</span>
                    </p>
                    @if($propiedad->usuario->sitio_web)
                    <p>
                      <i class="fas fa-globe"></i>
                      <a href="{{ $propiedad->usuario->getSitioWebUrl() }}" target="_blank">{{ $propiedad->usuario->sitio_web }}</a>
                    </p>
                    @endif
                    <div class="form-group">
                      <input class="form-control" type="email" name="email" placeholder="E-mail" required>
                    </div>
                    <div class="form-group">
                      <input class="form-control" type="text" name="telefono" placeholder="Teléfono">
                    </div>
                    <div class="form-group">
                      <textarea class="form-control" name="mensaje" required>Quisiera tener más detalles del inmueble por favor.</textarea>
                    </div>
                    <div class="mt-2 mb-4 recaptcha">
                      <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
                      <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group text-center text-md-right">
                      <button type="submit" class="btn-verde btn-redondo btn-contactar btn-block"><i class="fas fa-envelope"></i> Contactar por email</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@include('propiedades.galeria')

@include('propiedades.modals.compartir')

@endsection
@section('post-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.1/photoswipe.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.1/photoswipe-ui-default.min.js"></script>
<script src='https://www.google.com/recaptcha/api.js' async defer></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAPS_API_KEY', '')}}&libraries=places"></script>
<script>
  $(document).ready(function () {

    function initMap() {
      var map = new google.maps.Map($('#map')[0], {
        zoom: 16,
        zoomControl: true,
        streetViewControl: true,
        mapTypeId : google.maps.MapTypeId.ROADMAP,
        disableDefaultUI : true
      });

      marker = new google.maps.Marker({
        map: map,
        draggable: false
      });

      let lat = $('#lat').val();
      let long = $('#long').val();

      if (lat.length > 0 && long.length > 0) {
        var loc = new google.maps.LatLng(lat, long);
        map.setCenter(loc);
        marker.setPosition(loc);
      }
    }

    $('.mapa').on('click', function() {
      $(this).remove();
      $('#map').removeClass('d-none');
      $('.descripcion-mapa-mobile').removeClass('d-block').addClass('d-none');
      initMap();
    });

    @if(session('imprimir'))
      window.print();
    @endif

    @if($mostrarDatosDeContacto)
    $([document.documentElement, document.body]).animate({
        scrollTop: $('form.form-contactar').offset().top
    }, 500);
    @endif

    $('.form-contactar').submit(function (ev) {
      ev.preventDefault();

      let formContactar = $(this);
      let submit = $(this).find('button[type=submit]');
      $(submit).html('Enviando...').prop('disabled', true);

      $.post($(this).attr('action'), $(this).serialize(),
        function (data) {
          $(submit).html('Enviado!');

          setTimeout(function() {
            $(submit).prop('disabled', false);
            $(submit).html('Contactar');
            $(formContactar).trigger('reset');
          }, 1000 * 4);
        }
      ).fail(function (xhr) {
        let mensajes = '';
        if(xhr.responseJSON.errors) {
          $.each(xhr.responseJSON.errors, function (k, e) {
            console.log(e);
            $.each(e, function (mk, m) {
              console.log(m);
              mensajes = mensajes.concat('<p>').concat(m).concat('</p>');
            });
          });
        }

        formContactar.find('.recaptcha .invalid-feedback').html(mensajes).show();

        $(submit).html('Ha ocurrido un error');

        setTimeout(function() {
          $(submit).prop('disabled', false);
          $(submit).html('Contactar');
        }, 1000 * 4);
      });

      return false;
    });

    $('.form-interesarse').submit(function (ev) {
      ev.preventDefault();
      let formInteresarse = $(this);

      // inhabilitamos el botón
      formInteresarse.find('.btn-continuar').html('Cargando...').prop('disabled', true);

      $.post('{{route('propiedades.interesarse', $propiedad->id)}}', $(this).serialize(),
        function (data) {
          // si ya está interesado mostramos el form de contacto
          if (data) {
            // ocultamos este form
            formInteresarse.hide();

            // y mostramos los datos de contacto
            mostrarDatosDeContacto(data);
            $('.form-contactar').show();
            $('.form-contactar').find('input[name=email]').val(formInteresarse.find('input[name=email]').val());
          }
        }
      ).fail(function (xhr) {
        let mensajes = '';
        if(xhr.responseJSON.errors) {
          $.each(xhr.responseJSON.errors, function (k, e) {
            $.each(e, function (mk, m) {
              mensajes = mensajes.concat('<p>').concat(m).concat('</p>');
            });
          });
        }
        formInteresarse.find('.recaptcha .invalid-feedback').html(mensajes).show();
        formInteresarse.find('.btn-continuar').html('Continuar').prop('disabled', false);
      });

      return false;
    });

    $('.btn-comprobar-interesado').click(function (ev) {

      // inhabilitamos el botón
      $(this).html('Cargando...').prop('disabled', true);

      $.get('{{route('propiedades.estaInteresado', $propiedad->id)}}', function (data) {

        // ocultamos el degradé
        $('.row-degrade').hide();

        // si ya está interesado mostramos el form de contacto
        if (data) {
          mostrarDatosDeContacto(data);
          $('.form-contactar').show();
        }
        else {
          $('.form-interesarse').show();
        }
      });
    });

    $('.favorita').on('click', function(e) {
      var url = $(this).attr('href');
      if(url.includes("login")) {
        window.location = url;
      }

      else {
        if($(this).find('i').hasClass('far')){
          $(this).find('i').removeClass('far');
          $(this).find('i').addClass('fas text-danger');
          $('.favorita').find('i').siblings('span').text('');
          $(this).find('i').siblings('span').text('Quitar de favoritos');
        }
        else {
          $(this).find('i').addClass('far');
          $(this).find('i').removeClass('fas text-danger');
          $('.favorita').find('i').siblings('span').text('');
          $(this).find('i').siblings('span').text('Añadir a favoritos');
        }
        $.get(url, function(data) {
            
        });
        return false;
      }
    });

    var items = [];
    $.each($('#fotos .carousel-item img'), function() {
      items.push({src : $(this).attr('src'), w :$(this).attr('data-w') , h : $(this).attr('data-h')});
    });

    $('.carousel-item').on('click', function() {
      var idx = parseInt($(this).attr('data-idx'));
      var options = {
        index: idx,
      };
      var gallery = new PhotoSwipe($("#galeria")[0], PhotoSwipeUI_Default, items, options);
      gallery.init();
    });
  });

  function mostrarDatosDeContacto(datos) {
    $('.form-contactar').find('.telefono-contacto').html(datos.telefono);
    $('.form-contactar').find('.email-contacto').html(datos.email);
  }
</script>
@endsection