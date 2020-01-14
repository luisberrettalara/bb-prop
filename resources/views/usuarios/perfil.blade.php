@extends(!Auth::user() || !Auth::user()->esAdmin() ? 'layouts.app' : 'layouts.admin', ['gris' => true])
@section('content')
<div class="perfil">
    <div class="row justify-content-center">
        <div class="col-12">
            <h3 class="mt-4 mb-3 text-md-left text-center">Mi Perfil</h3>
            @if(session('mensaje'))
                <div class="alert alert-warning" role="alert">
                  {{session('mensaje')}}
                </div>
            @endif
            <div class="row justify-content-start">
                <div class="col-12 col-md-4 mt-2">
                    <div class="card card-perfil">
                        <div class="card-body">
                           <p class="texto-semibold">Nombre de perfil</p>
                            <div class="row">
                                @if ($usuario_facebook != null)
                                    <div class="col-12 col-md-12 text-center">
                                        <img src="{{$usuario_facebook['foto']}}" class="imagen-circular">
                                    </div>
                                @else
                                    <div class="col-12 col-md-12 text-center">
                                        @if($usuario->foto_url)
                                            <img src="{{ $usuario->foto_url }}" class="imagen-circular">
                                        @else
                                            <div class="foto-vacia redonda">
                                                <img src="{{asset('/images/camera.svg')}}">
                                                <p>Sin foto</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <span>Razón social</span>
                                    <p class="mt-2">{{ $usuario->razon_social ? $usuario->razon_social : 'Vacío' }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <span>Descripción de la empresa</span>
                                    <p class="mt-2">{{ $usuario->descripcion ? $usuario->descripcion : 'Vacío'}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ route('perfil.editar') }}" class="texto-azul">Cambiar</a>
                                </div>
                            </div>
                        </div> 
                    </div> 
                </div>
                <div class="col-12 col-md-4 mt-2">
                    <div class="card card-perfil">
                        <div class="card-body">
                            <p class="texto-semibold">Datos de contacto</p>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <span>Teléfono fijo</span>
                                    <p class="mt-2">{{$usuario->telefono ? $usuario->telefono : 'Vacío'}}</p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <span>Teléfono celular</span>
                                    <p class="mt-2">{{$usuario->telefono_celular ? $usuario->telefono_celular : 'Vacío'}}</p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <span>Email</span>
                                    <p class="mt-2">{{$usuario->email}}</p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <span>Persona de contacto</span>
                                    <p class="mt-2">{{$usuario->persona_contacto ? $usuario->persona_contacto : 'Vacío'}}</p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <span>Domicilio completo</span>
                                    <p class="mt-2">{{$usuario->getDomicilioCompleto() ? $usuario->getDomicilioCompleto() : 'Vacío'}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ route('perfil.editar') }}" class="texto-azul">Cambiar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3 mt-2">
                    <div class="card card-perfil pequeña">
                        <div class="card-body">
                            <p class="texto-semibold">Configuración de la cuenta</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <span>Contraseña</span>
                                    <p class="mt-1">• • • • • • • • • • • •</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ route('perfil.editar') }}" class="texto-azul">Cambiar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4"> 
                <div class="col-12 col-md-6 text-md-left text-center">
                    <h3>Propiedades favoritas</h3>
                </div>
                @if ($favoritas->count())
                    <div class="col-md-6 text-right">
                        <a href="{{ route('propiedades.favoritas') }}" class="btn btn-outline-info d-none d-md-inline-block">Ver todas</a>
                    </div>
                @endif
            </div>
            <div class="row mt-3">
                @if($favoritas->count())
                    @foreach($favoritas as $fav)
                        <div class="col-12 col-md-6 mt-2">
                            @include('propiedades._propiedad-home', ['propiedad' => $fav, 'destacada' => false])
                        </div>
                    @endforeach
                    <div class="col-12 text-center d-sm-none d-block mt-4">
                        <a href="{{ route('propiedades.favoritas') }}" class="texto-todas">Ver todas</a>
                    </div>
                @else
                    <div class="col-12 col-md-6 text-md-left text-center">
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
                                                <p class="placeholder-boton"></p>
                                            </div>
                                            <div class="col-6 mt-2">
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
            <div class="row mt-4"> 
                <div class="col-12 col-md-6 text-md-left text-center">
                    <h3>Propiedades</h3>
                </div>
                <div class="col-md-6 text-right d-none d-md-inline-block">
                    @if ($propiedades->count())
                        <a href="{{ route('propiedades.index') }}" class="btn btn-outline-info">Ver todas</a>
                    @endif
                </div>
            </div>
            <div class="row mt-3">
                @if($propiedades->count())
                    @foreach($propiedades as $prop)
                        @include('propiedades._propiedad-perfil', array('propiedad' => 'prop'))
                    @endforeach
                <div class="col-12 text-center d-sm-none d-block mt-4">
                    <a href="{{ route('propiedades.index') }}" class="texto-todas">Ver todas</a>
                </div>
                @else
                    <div class="col-md-6 col-12 text-center">
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
                                                <a class="btn-redondo" href="{{ route('propiedades.create') }}">Publicar una propiedad</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if(!Auth::user()->noEsAnunciante())
                <div class="row mt-4" id="plan">
                    <div class="col-md-6 col-12">
                        <h3 class="text-md-left text-center">Mi Plan</h3>
                        <div class="card plan mt-4">
                            <div class="card-body">
                                <div class="row mb-4">
                                    @if ($creditos->count()>0)
                                    <div class="col-12">
                                        <h5>Créditos disponibles para publicar</h5>
                                        <div class="table-container creditos acordion">
                                          <table class="table table-striped mt-2">
                                            <thead>
                                                <tr>
                                                    <th width="35%">Destacado</th>
                                                    <th>{{ $destacados }} disponibles</th>
                                                    <th></th>
                                                    <th width="15px"><img src="{{asset('/images/page-1.svg')}}" data-toggle="collapse" class="arrow collapsed" data-target="#collapseOne"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="collapseOne" class="collapse">
                                                @if($destacados>0)
                                                    <tr>
                                                        <th width="35%">Tipo de crédito</th>
                                                        <th>Vencimiento</th>
                                                        <th colspan="2">Duración</th>
                                                    </tr>
                                                    @foreach($creditos as $credito)
                                                        @if($credito->destacado==1)
                                                            <tr>
                                                                <td>Destacado</td>
                                                                <td>{{$credito->fecha_vencimiento->format('d/m/y')}}</td>
                                                                <td colspan="2">{{$credito->dias_totales}} días</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <th colspan="4" scope="row">No tenés créditos destacados</th>
                                                    </tr>
                                                @endif
                                            </tbody>
                                            <thead>
                                                <tr>
                                                    <th>Estándar</th>
                                                    <th>{{ $standard }} disponibles</th>
                                                    <th></th>
                                                    <th width="15px"><img src="{{asset('/images/page-1.svg')}}" data-toggle="collapse" class="arrow collapsed" data-target="#collapseTwo"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="collapseTwo" class="collapse">
                                            @if($standard>0)
                                                <tr>
                                                    <th width="35%">Tipo de crédito</th>
                                                    <th>Vencimiento</th>
                                                    <th colspan="2">Duración</th>
                                                </tr>
                                                @foreach($creditos as $credito)
                                                    @if($credito->destacado==0)
                                                        <tr>
                                                            <td width="35%">Estándar</td>
                                                            <td>{{$credito->fecha_vencimiento->format('d/m/y')}}</td>
                                                            <td colspan="2">{{$credito->dias_totales}} días</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @else
                                                <tr>
                                                    <th colspan="4" scope="row">No tenés créditos estándar</th>
                                                </tr>
                                            @endif
                                            </tbody>
                                          </table>
                                        </div>
                                        <div class="text-md-right text-center">
                                            <a class="btn-redondo btn-mediano mt-3" href="{{ route('paquetes.comprar') }}">Comprar más Publicaciones</a>
                                        </div>
                                    </div>
                                    @else
                                        <div class="col-12 col-md-4 img-casa">
                                            <img src="{{ asset('images/real-estate.svg') }}">
                                        </div>
                                        <div class="col-12 col-md-8 mt-4 text-center">
                                            <span>Todavía no hay un plan seleccionado</span>
                                            <a class="btn-redondo btn-mediano mt-4" href="{{ route('paquetes.comprar') }}">Comprar Publicaciones</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <h3 class="texto-medium">Mis Compras</h3>
                        <div class="card plan mt-4">
                            <div class="card-body">
                                <div class="row mb-4">
                                    @if ($pedidos->count() > 0)
                                    <div class="col-12">
                                        <h5>Pedidos realizados</h5>
                                        <div class="table-container">
                                          <table class="table table-striped mt-2">
                                            <thead>
                                              <th scope="col">#</th>
                                              <th scope="col">Fecha</th>
                                              <th scope="col">Paquete</th>
                                              <th scope="col">Monto total</th>
                                              <th scope="col">Estado</th>
                                           </thead>
                                            <tbody>
                                              @foreach($pedidos as $pedido)
                                              <tr>
                                                <td>{{ $pedido->id }}</td>
                                                <td>{{ $pedido->created_at->format('d/m/Y') }}</td>
                                                <td>{{ $pedido->paquete }}</td>
                                                <td> $ {{ $pedido->getImporteTotalFormato() }}</td>
                                                <td>{{ $pedido->estado }}</td>
                                              </tr>
                                              @endforeach
                                            </tbody>
                                          </table>
                                        </div>

                                        <div class="text-md-right text-center">
                                            <a class="btn-redondo btn-mediano mt-3" href="{{ route('paquetes.comprar') }}">Comprar más Publicaciones</a>
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-12 col-md-4 img-casa">
                                        <img src="{{ asset('images/real-estate.svg') }}">
                                    </div>
                                    <div class="col-12 col-md-8 mt-4 text-center">
                                        <span>Todavía no tienes créditos disponibles</span>
                                        <a class="btn-redondo btn-mediano mt-3" href="{{ route('paquetes.comprar') }}">Comprar Publicaciones</a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@include('propiedades.modals.baja')
@include('propiedades.modals.finalizar')

@endsection
@section('post-scripts')
<script type="text/javascript" src="{{ asset('js/propiedades/finalizar.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/propiedades/baja.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/propiedades/favorita.js') }}"></script>
@endsection
