<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/images/bb-prop-01.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="{{ asset('css/basic.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dropzone.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/solid.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/regular.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/brands.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/fontawesome.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.1/photoswipe.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.1/default-skin/default-skin.min.css">

    <link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.structure.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.theme.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/estilos.css') }}" rel="stylesheet">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-139348427-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-139348427-1');
    </script>
    
    @yield('post-head')
</head>
<body class="{{ isset($gris) ? 'fondo-gris' : '' }} {{ isset($grisAzul) ? 'fondo-gris-azul' : ''}} {{isset($azul) ? 'fondo-azul' : ''}} {{isset($naranja) ? 'fondo-naranja' : ''}} {{isset($roja) ? 'fondo-rojo' : ''}}">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel {{isset($transparente) ? 'transparente' : ''}}">
            <div class="container">
                <button class="navbar-toggler" type="button" data-sidebar="#sidebar-principal">
                    <span class="navbar-toggler-icon {{isset($transparente)? 'blanco' : 'negro'}}"></span>
                </button>
                  <a class="navbar-brand" href="{{ url('/') }}">
                    <div class="row">
                        <div class="col">
                            <img src="{{!isset($transparente)? asset('/images/logo-bbp.svg') : asset('/images/logo-transparente.svg') }}">
                        </div>
                    </div>
                  </a>
                  <a class="btn btn-verde d-block d-sm-none" href="/propiedades/create">
                   Publicar<img src="{{asset('images/moon.svg')}}" style="margin-left: 2px; margin-right: 1px;"></a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        @guest
                            <li class="nav-item">
                               <a class="btn-redondo {{isset($transparente) ? 'btn-blanco' : 'btn-verde'}}" href="{{ route('propiedades.create') }}">Publicar</a> 
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{isset($transparente) ? 'texto-blanco' : ''}}" href="{{ route('login') }}">Ingresar</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="btn-redondo {{isset($transparente) ? 'btn-blanco' : 'btn-verde'}}" href="{{ route('propiedades.create') }}">
                                    Publicar
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{isset($transparente) ? 'texto-blanco' : ''}}" href="{{ route('paquetes.comprar') }}">Paquetes</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{isset($transparente) ? 'texto-blanco' : ''}}" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                                    Mi Cuenta
                                </a>
                                <div class="dropdown-menu">
                                    @if(!Auth::user()->noEsAnunciante())
                                        <a class="dropdown-item" href="/usuarios/mi-perfil/#plan">Plan</a>
                                        <a class="dropdown-item" href="{{ route('perfil') }}">Preferencias</a>
                                        <a class="dropdown-item" href="{{ route('propiedades.favoritas') }}">Favoritos</a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('perfil') }}">Preferencias</a>
                                    @endif
                                </div>
                            </li>
                            <li class="nav-item">
                                @if(!Auth::user()->noEsAnunciante())
                                    <a class="nav-link {{isset($transparente) ? 'texto-blanco' : ''}}" href="{{ route('propiedades.index') }}">
                                        Propiedades
                                    </a>
                                @else
                                    <a class="nav-link {{isset($transparente) ? 'texto-blanco' : ''}}" href="{{ route('propiedades.favoritas') }}">
                                        Favoritos
                                    </a>
                                @endif
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{isset($transparente) ? 'texto-blanco' : ''}}" href="{{ route('logout') }}" 
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >
                                        Salir
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <nav id="sidebar-principal" role="navigation" class="sidebar d-block d-sm-none">
            <button class="close"></button>
            <ul class="menu">
                @guest
                    <li class="titulo-nav-mobile">Menu</li>
                    <li>
                        <a href="{{ route('login') }}">Ingresar</a>
                    </li>
                @else
                    <li class="texto-bold">Menu</li>
                    <li>
                        <a href="{{ route('perfil') }}">Mi cuenta</a>
                    </li>
                    @if(Auth::user()->esAnunciante())
                        <li>
                            <a href="{{ route('propiedades.index') }}">Propiedades</a>
                        </li>
                        <li>
                            <a href="/usuarios/mi-perfil/#plan">Plan</a>
                        </li>
                    @endif
                    <li>
                        <a href="{{route('perfil')}}">Preferencias</a>
                    </li>
                    <li>
                        <a href="{{route('propiedades.favoritas')}}">Favoritos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="padding: 0;" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); $('#logout-form').submit();" >
                                Salir
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </a>
                    </li>
                @endguest
            </ul>
        </nav>
        <main class="{{!isset($container) ? 'container' : ''}}">
            @yield('content')
        </main>
        <footer class="footer">
            <span class="divisor"></span>
            <div class="container py-4">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <h5 class="pb-2">Propiedades</h5>
                        <div id="categorias-url">
                            @inject('propiedades', 'Inmuebles\Services\PropiedadesService')
                            @foreach($propiedades->linksBusquedasComunesFooter() as $nombre => $links)
                                <p><a href="{{$links}}">{{$nombre}}</a></p>
                            @endforeach
                        </div>
                        <span class="divisor-mobile d-block d-sm-none"></span>
                    </div>
                    <div class="col-12 col-md-4 asset-footer">
                        <h5 class="pb-2">BB-Prop Inmuebles</h5>
                        <p><img src="{{asset('images/pin-gris.svg')}}"> Rodríguez 55, Bahía Blanca, Argentina</p>
                        <p><img src="{{asset('images/phone.svg')}}" class="mr-3 pl-1"> (0291) 459-0000</p>
                        <p><img src="{{asset('images/sobre-gris.svg')}}"> hola@bb-prop.com</p>
                        <br />
                        <p>
                            <a href=""><img src="{{ asset('images/facebook.svg')}}" class="mt-1"></a>
                            <a href="https://www.instagram.com/bb.prop/" class="btn-instagram"><i class="fab fa-instagram"></i></a>
                        </p>
                        <span class="divisor-mobile d-block d-sm-none"></span>
                    </div>
                    <div class="col-12 col-md-4">
                        <h5 class="pb-2">Contacto</h5>
                        <form class="contactar" action="{{route('admin.contactar')}}" method="POST">
                            <p><input id="email_id" type="text" name="email" placeholder="Email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"></p>
                            <p><input id="mensaje_id" type="text" name="mensaje" placeholder="Mensaje" class="form-control{{ $errors->has('mensaje') ? ' is-invalid' : '' }}"></p>
                            <div class="mt-2 mb-4 recaptcha-footer">
                                <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <button type="submit" class="btn btn-outline-dark">Enviar mensaje</button>
                        </form>
                    </div>
                </div>
            </div>
            <span class="divisor"></span>
            <div class="container">
                <div class="row my-3">
                    <div class="col-12 col-md-12 text-center">
                        <img src="{{asset('/images/logo-bbp.svg')}}" height="32px"><span class="texto-logo"> - 2019</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/propiedades/compartir.js') }}"></script>
    <script src="{{ asset('js/dropzone.min.js') }}"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script type="text/javascript">
        $(function(){
            $.fn.selectpicker.Constructor.BootstrapVersion = '4';
            $('.navbar-toggler').on('click', function() {
                $($(this).data('sidebar')).addClass('open');
                $('body').addClass('sidebar-open');
                return false;
            });
            $('.sidebar .close').on('click', function() {
                $(this).parents('.sidebar').removeClass('open');
                $('body').removeClass('sidebar-open');
                return false;
            });

            $('.recaptcha-footer').hide();

            $('#email_id, #mensaje_id').on('change', function() {
                if($('#email_id').val() !='' && $('#mensaje_id').val() !='') {
                    $('.recaptcha-footer').show();
                }
                else {
                    $('.recaptcha-footer').hide();
                }
            });

            $('.contactar').submit(function (ev) {
              ev.preventDefault();
              let contactar = $(this);
              let submit = $(this).find('button[type=submit]');
              $(submit).html('Enviando...').prop('disabled', true);

              $.post($(this).attr('action'), $(this).serialize(),
                function (data) {
                  $(submit).html('Enviado!');
                  setTimeout(function() {
                    $(submit).prop('disabled', false);
                    $(submit).html('Contactar');
                    $(contactar).trigger('reset');
                  }, 1000 * 4);
                }
              ).fail(function (xhr) {
                let mensajes = '';
                if(xhr.responseJSON.errors) {
                  $.each(xhr.responseJSON.errors, function (k, e) {
                    console.log(e);
                    $.each(e, function (mk, m) {
                      mensajes = mensajes.concat('<p>').concat(m).concat('</p>');
                    });
                  });
                }

                contactar.find('.recaptcha-footer .invalid-feedback').html(mensajes).show();

                $(submit).html('Ha ocurrido un error');

                setTimeout(function() {
                  $(submit).prop('disabled', false);
                  $(submit).html('Contactar');
                }, 1000 * 4);
              });

              return false;
            });
        });
    </script>
    @yield('post-scripts')
</body>
</html>