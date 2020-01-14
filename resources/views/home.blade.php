@extends(!Auth::user() || !Auth::user()->esAdmin() ? 'layouts.app' : 'layouts.admin', ['transparente' => true, 'container' => false])
@section('post-head')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css"/>
@stop
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center img-fondo-home">
        <div class="col-12">
            <div class="busqueda">
                <form class="form-row" action="{{route('propiedades.buscar')}}" method="GET">
                    <div class="col-md-5 col-12">
                        <img src="{{ asset('images/build.svg') }}">
                        <select name="tipo_propiedad_id" class="selectpicker" data-width="87%">
                            <option value="">Todas</option>
                            @foreach ($tipos_propiedad as $tip)
                                <option value="{{$tip->id}}" {{ old('tipo_propiedad_id') == $tip->id? 'selected':'' }} >{{$tip->nombre}}</option>
                            @endforeach
                        </select>
                        <span class="divisor"></span>
                        <img src="{{ asset('images/pin.svg') }}">
                        <select name="localidad_id" class="selectpicker" data-width="87%">
                            <option value="">Todas</option>
                            @foreach ($localidades as $loc)
                                <option value="{{$loc->id}}" {{ old('localidad_id') == $loc->id? 'selected':'' }}>{{$loc->nombre}}</option>
                            @endforeach
                        </select>
                        <span class="divisor"></span>
                    </div>
                    <div class="col-md-5 text-left">
                        <img src="{{ asset('images/key.svg') }}" style="margin-left: 8px;">
                        @foreach ($operaciones as $op)
                            <div class="form-check form-check-inline">
                                <label class="custom-radio">
                                    <input class="form-check-input{{ $errors->has('operacion_id') ? ' is-invalid' : '' }}" type="radio" name="operacion_id" value="{{$op->id}}" {{old('operacion_id') == $op->id? 'checked':''}} id="op[{{$op->id}}]">
                                    <span class="checkmark-home"></span>
                                    <label class="form-check-label form-check-operaciones" for="op[{{$op->id}}]">{{$op->nombre}}</label>
                                </label>
                            </div>
                        @endforeach 
                    </div>
                    <div class="col-md-2 text-right">
                        <button type="submit" class="btn btn-verde btn-mitad btn-mobile">Buscar</button>
                    </div>
                </form>
            </div>
        </div>
        @if(session('mensaje'))
            <div class="alert alert-info mb-5" role="alert">
              {{session('mensaje')}}
            </div>
        @endif
    </div>
    <div class="container">
        <h4 class="mt-5 texto-medium home">Propiedades destacadas</h4>
        <div class="row home mt-3">
            @foreach($propiedades_recientes as $propiedad)
                @include('propiedades._propiedad-home', [ 'propiedad' => $propiedad, 'destacada' => false ])
            @endforeach
        </div>
        <div class="row my-3">
            <div class="col-12 text-center text-md-left">
                <a href="{{ route('propiedades.buscar') }}" class="texto-todas mt-2">Ver todas</a>
            </div>
        </div>
        <div class="card img-fondo-2-home my-4">
            <div class="card-body texto-card-home">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <h3>Publicá con nosotros</h3>
                        <p>Publicar tu propiedad en <strong>BB-Prop</strong> es muy fácil y seguro.
                        <br/>
                        Además, contás con más de 10.000 potenciales clientes que están buscando comprar o alquilar.</p>
                        <a href="{{!Auth::user() || !Auth::user()->esAdmin()? route('propiedades.create') : route('admin.propiedades.create')}}" class="btn-redondo btn-verde">Publicar una propiedad</a>
                    </div>
                </div>
            </div>
        </div>
        @if($propiedades->count())
        <h4 class="mt-4 texto-medium home">Emprendimientos de pozo</h4>
        <div class="row home my-3">
            @foreach($propiedades as $propiedad)
                @include('propiedades._propiedad-home', [ 'propiedad' => $propiedad, 'destacada' => false ])
            @endforeach
        </div>
        <div class="row mt-3">
            <div class="col-12 text-center text-md-left">
                <a href="{{ route('propiedades.buscar', [ 'tipo_propiedad_id' => $tipoPozo->id ]) }}" class="texto-todas mt-2">Ver todas</a>
            </div>
        </div>
        @endif
        @if($usuarios->count())
        <div class="slider">
            <h4 class="text-center mb-4">Inmobiliarias que confían en nosotros</h4>
            <div class="multiple-items">
                @foreach($usuarios as $usuario)
                <div>
                    <img src="{{$usuario->foto_url}}">
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@include('propiedades.modals.compartir')
@include('propiedades.modals.contactar')

@endsection
@section('post-scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
<script type="text/javascript" src="{{ asset('js/propiedades/favorita.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/propiedades/contactar.js') }}"></script>
<script type="text/javascript">
    var emailInteresado = '{{ $emailInteresado }}';
    $(function() {
        $('.multiple-items').slick({
            autoplay: true,
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 4,
            arrows: false,
            responsive: [
                {
                  breakpoint: 1024,
                  settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                  }
                },
                {
                  breakpoint: 600,
                  settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                  }
                },
            ]
        });
    })
</script>
@endsection