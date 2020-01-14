<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

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

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/images/bb-prop-01.png') }}">

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />

    <!-- Core CSS file -->
    <link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.structure.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.theme.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/estilos.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    
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
<body class="{{ isset($gris) ? 'fondo-gris' : '' }}">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel {{isset($transparente) ? 'transparente' : ''}}">
            <div class="container">
                <button class="navbar-toggler" type="button" data-sidebar="#sidebar-principal">
                    <span class="navbar-toggler-icon {{isset($transparente)? 'blanco' : 'negro'}}"></span>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">
                    <div class="row">
                        <div class="col-12">
                            <img src="{{!isset($transparente)? asset('/images/logo-bbp.svg') : asset('/images/logo-transparente.svg') }}">
                        </div>
                    </div>
                </a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                              Propiedades
                            </a>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="/admin/propiedades">Listar</a>
                              <a class="dropdown-item" href="/admin/propiedades/create">Nueva</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                              Usuarios
                            </a>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="/admin/usuarios">Listar</a>
                              <a class="dropdown-item" href="/admin/usuarios/create">Nuevo</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                              Paquetes
                            </a>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="/admin/paquetes">Paquetes</a>
                              <a class="dropdown-item" href="/admin/tipos-credito">Tipos de Créditos</a>
                            </div>
                        </li>
                         <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                              Pedidos
                            </a>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="/admin/pedidos">Listar</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                              Parámetros
                            </a>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="/admin/operaciones">Operaciones</a>
                              <a class="dropdown-item" href="/admin/caracteristicas">Características</a>
                              <a class="dropdown-item" href="/admin/tipologias">Tipologías</a>
                              <a class="dropdown-item" href="/admin/tipos-propiedad">Tipos de Propiedades</a>
                              <a class="dropdown-item" href="/admin/barrios">Barrios</a>
                              <a class="dropdown-item" href="/admin/usos">Usos</a>
                            </div>
                        </li>
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                              {{Auth::user()->razon_social}}
                            </a>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="{{route('perfil')}}">Mi cuenta</a>
                              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Salir 
                                 <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                 </form>
                              </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <nav id="sidebar-principal" role="navigation" class="sidebar d-block d-sm-none">
            <button class="close"></button>
            <ul class="menu">
                <li class="titulo-nav-mobile">Menu</li>
                <li>
                    <a href="{{ route('admin.propiedades.index') }}">Propiedades</a>
                </li>
                <li>
                    <a href="{{ route('admin.usuarios.index') }}">Usuarios</a>
                </li>
                <li>
                    <a href="{{ route('admin.paquetes.index') }}">Paquetes</a>
                </li>
                <li>
                    <a href="{{ route('admin.pedidos.index') }}">Pedidos</a>
                </li>
                <li>
                    <a href="{{ route('admin.operaciones.index') }}">Operaciones</a>
                </li>
                <li>
                    <a href="{{ route('admin.caracteristicas.index') }}">Características</a>
                </li>
                <li>
                    <a href="{{ route('admin.tipologias.index') }}">Tipologías</a>
                </li>
                <li>
                    <a href="{{ route('admin.tipos-propiedad.index') }}">Tipos de Propiedades</a>
                </li>
                <li>
                    <a href="{{ route('admin.barrios.index') }}">Barrios</a>
                </li>
                <li>
                    <a href="{{ route('admin.usos.index') }}">Usos</a>
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
                        @inject('propiedades', 'Inmuebles\Services\PropiedadesService')
                            @foreach($propiedades->linksBusquedasComunesFooter() as $nombre => $links)
                                <p><a href="{{$links}}">{{$nombre}}</a></p>
                            @endforeach
                        <span class="divisor-mobile d-block d-sm-none"></span>
                    </div>
                    <div class="col-12 col-md-4 asset-footer">
                        <h5 class="pb-2">BB-Prop Inmuebles</h5>
                        <p><img src="{{asset('images/pin-gris.svg')}}"> Rodríguez 55, Bahía Blanca, Argentina</p>
                        <p><img src="{{asset('images/phone.svg')}}" class="mr-3 pl-1"> (0291) 459-0000</p>
                        <p><img src="{{asset('images/sobre-gris.svg')}}"> hola@bb-prop.com</p>
                        <br />
                        <p>
                            <a href=""><img src="{{asset('images/facebook.svg')}}" class="mt-1"></a>
                            <a href="https://www.instagram.com/bb.prop/" class="btn-instagram"><i class="fab fa-instagram"></i></a>
                        </p>
                        <span class="divisor-mobile d-block d-sm-none"></span>
                    </div>
                    <div class="col-12 col-md-4">
                        <h5 class="pb-2">Contacto</h5>
                        <p><input type="text" name="email" placeholder="Email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"></p>
                        <p><input type="text" name="mensaje" placeholder="Mensaje" class="form-control{{ $errors->has('mensaje') ? ' is-invalid' : '' }}"></p>
                        <a class="btn btn-outline-dark">Enviar mensaje</a>
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
    <script src="{{ asset('js/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/propiedades/compartir.js') }}" defer></script>
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
        });
    </script>

    @yield('post-scripts')

</body>
</html>