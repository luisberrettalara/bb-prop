@extends(!Auth::user() || !Auth::user()->esAdmin() ? 'layouts.app' : 'layouts.admin')
@section('content')
  <div class="filtros">
    <div class="row">
      <div class="col-md-3">
        <nav id="sidebar-busqueda" role="navigation" class="sidebar">
          <form action="{{route('propiedades.buscar')}}" method="GET">
            <div>
              <button class="close"></button>
              <h3>Filtrar</h3>
              <div class="form-group">
                <label for="tipo_propiedad_id" class="form-label"><strong>Inmueble</strong></label>
                <select id="tipo_propiedad_id" class="form-control selectpicker show-tick" name="tipo_propiedad_id" value="{{ old('tipo_propiedad_id') }}">
                  <option value="">Todas</option>
                  @foreach ($tipos as $tip)
                    <option value="{{$tip->id}}"
                    @if(array_key_exists('tipo_propiedad_id', $filtros)) 
                      {{ $filtros['tipo_propiedad_id'] == $tip->id? 'selected':'' }}
                    @endif
                    >{{$tip->nombre}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="provincia_id" class="form-label"><strong>Provincia</strong></label>
                <select id="provincia_id" class="form-control selectpicker show-tick" name="provincia_id" value="{{ old('provincia_id') }}">
                  <option value="">Todas</option>
                    @foreach ($provincias as $provincia)
                      <option value="{{$provincia->id}}" 
                      @if(array_key_exists('provincia_id', $filtros))
                        {{$filtros['provincia_id'] == $provincia->id? 'selected':''}}
                      @endif
                      >{{$provincia->nombre}}</option>
                    @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="localidad_id" class="form-label"><strong>Localidad</strong></label>
                <select id="localidad_id" class="form-control selectpicker show-tick" name="localidad_id"
                  @if(array_key_exists('localidad_id', $filtros)) 
                    value="{{ $filtros['localidad_id'] }}"
                  @endif>
                  <option value="">Todas</option>
                    @foreach ($localidades as $localidad)
                      <option value="{{$localidad->id}}" 
                      @if(array_key_exists('localidad_id', $filtros))
                        {{$filtros['localidad_id'] == $localidad->id? 'selected':''}}
                      @endif
                      >{{$localidad->nombre}}</option>
                    @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="barrio_id" class="form-label"><strong>Barrio</strong></label> 
                <select  id="barrio_id" class="form-control selectpicker show-tick" name="barrio_id" 
                  @if(array_key_exists('barrio_id', $filtros))
                    value="{{ $filtros['barrio_id'] }}"
                  @endif>
                  <option value="">Todos</option>
                  @foreach ($barrios as $barrio)
                    <option value="{{$barrio->id}}" 
                    @if(array_key_exists('barrio_id', $filtros))
                      {{$filtros['barrio_id'] == $barrio->id? 'selected':''}}
                    @endif
                    >{{$barrio->nombre}}</option>
                   @endforeach
                </select>
              </div>
              <div class="form-group mb-0">
                <label class="form-label"><strong>Operación</strong></label>
              </div>
              <div class="form-group">
                @foreach ($operaciones as $op)
                  <div class="form-check">
                    <label class="custom-radio">
                      <input id="op[{{$op->id}}]" class="form-check-input" type="radio" name="operacion_id" value="{{$op->id}}"
                      @if(array_key_exists('operacion_id', $filtros))
                        {{$filtros['operacion_id'] == $op->id? 'checked':''}}
                      @endif>
                      <span class="checkmark"></span>
                      <label class="form-check-label form-check-operaciones" for="op[{{$op->id}}]">{{$op->nombre}}</label>
                    </label>
                  </div>
                @endforeach
              </div>
              <label class="form-label"><strong>Usos Permitidos</strong></label>
              <div class="form-group">
                @foreach ($usos as $us)
                  <div>
                    <label class="switch">
                      <input id="usos[{{$us->id}}]" class="form-check-input" name="usos[]" type="checkbox" value="{{$us->id}}"
                      @if(array_key_exists('usos', $filtros))
                        {{in_array($us->id, $filtros['usos'])? 'checked':''}}
                      @endif>
                      <span class="slider round"></span>
                    </label>
                    <label class="form-check-label label-usos" for="usos[{{$us->id}}]">{{$us->nombre}}</label>
                  </div>
                @endforeach
              </div>
              <div class="form-group">
                <label for="monto" class="form-label"><strong>Precio</strong></label>
                <div class="input-group">
                  <select id="moneda_id" name="moneda_id" value="{{ old('moneda_id') }}" class="custom-select append-precio select-monedas" type="text">
                    @foreach ($monedas as $mon)
                      <option value="{{$mon->id}}" 
                        @if(array_key_exists('moneda_id', $filtros))
                          {{$filtros['moneda_id'] == $mon->id? 'selected':''}}
                        @endif>{{$mon->nombre}}
                      </option>
                    @endforeach
                  </select>
                  <input type="number" class="form-control{{ $errors->has('monto') ? ' is-invalid' : '' }}" name="precioMinimo"
                  @if(array_key_exists('precioMinimo', $filtros)) 
                    value="{{$filtros['precioMinimo']}}"
                  @endif
                  placeholder="Mínimo">
                </div> 
                <div class="input-group mt-1">
                  <select id="moneda_id" name="moneda_id" value="{{ old('moneda_id') }}" class="custom-select append-precio select-monedas" type="text">
                    @foreach ($monedas as $mon)
                      <option value="{{$mon->id}}"
                      @if(array_key_exists('moneda_id', $filtros))
                        {{$filtros['moneda_id'] == $mon->id? 'selected':''}}
                      @endif>{{$mon->nombre}}
                      </option>
                    @endforeach
                  </select>
                  <input type="number" class="form-control" name="precioMaximo"
                  @if(array_key_exists('precioMaximo', $filtros)) 
                    value="{{$filtros['precioMaximo']}}"
                  @endif 
                  placeholder="Máximo">
                </div>
              </div>
              <div class="form-group">
                <label for="expensas" class="form-label"><strong>Expensas aproximadas</strong></label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <strong class="input-group-text prepend-peso">$</strong>
                  </div>
                  <input type="number" class="form-control input-expensa" name="expensaMinima" placeholder="Mínimo"
                  @if(array_key_exists('expensaMinima', $filtros)) 
                    value="{{$filtros['expensaMinima']}}"
                  @endif>
                </div>
                <div class="input-group mt-1">
                  <div class="input-group-prepend">
                    <strong class="input-group-text prepend-peso">$</strong>
                  </div>
                  <input type="number" class="form-control input-expensa" name="expensaMaxima" placeholder="Máximo"
                  @if(array_key_exists('expensaMaxima', $filtros)) 
                    value="{{$filtros['expensaMaxima']}}"
                  @endif 
                  >
                </div>
              </div>
              <div class="form-group">
                <label for="monto" class="form-label"><strong>Superficie Total</strong></label>
                <div class="input-group">
                  <input type="number" class="form-control{{ $errors->has('monto') ? ' is-invalid' : '' }}" name="precioMinimo"
                  @if(array_key_exists('precioMinimo', $filtros)) 
                    value="{{$filtros['precioMinimo']}}"
                  @endif
                  placeholder="Mínimo">
                  <select id="unidad_superficie_id" name="unidad_superficie_id" value="{{ old('unidad_superficie_id') }}" class="custom-select append-superficie select-monedas" type="text">
                    @foreach ($unidades as $unidad)
                      <option value="{{$unidad->id}}" 
                      @if(array_key_exists('unidad_superficie_id', $filtros))
                        {{$filtros['unidad_superficie_id'] == $unidad->id? 'selected':''}}
                      @endif>{{$unidad->nombre}}
                      </option>
                    @endforeach
                  </select>
                </div> 
                <div class="input-group mt-1">
                  <input type="number" class="form-control" name="precioMaximo"
                  @if(array_key_exists('precioMaximo', $filtros)) 
                      value="{{$filtros['precioMaximo']}}"
                  @endif 
                  placeholder="Máximo">
                  <select id="unidad_superficie_id" name="unidad_superficie_id" value="{{ old('unidad_superficie_id') }}" class="custom-select append-superficie select-monedas" type="text">
                    @foreach ($unidades as $unidad)
                      <option value="{{$unidad->id}}"
                        @if(array_key_exists('unidad_superficie_id', $filtros))
                          {{$filtros['unidad_superficie_id'] == $unidad->id? 'selected':''}}
                        @endif>{{$unidad->nombre}}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="superficie_cubierta" class="form-label"><strong>Superficie cubierta</strong></label>
                <div class="input-group">
                  <input type="number" class="form-control input-superficie" name="cubierta_minima" placeholder="Minima" 
                  @if(array_key_exists('cubierta_minima', $filtros)) 
                    value="{{$filtros['cubierta_minima']}}"
                  @endif>
                  <div class="input-group-append">
                    <strong class="input-group-text append-superficie">m2</strong>
                  </div>
                </div>
                <div class="input-group mt-1">
                  <input type="number" class="form-control input-superficie" name="cubierta_maxima" placeholder="Máxima"
                  @if(array_key_exists('cubierta_maxima', $filtros)) 
                    value="{{$filtros['cubierta_maxima']}}"
                  @endif 
                  >
                  <div class="input-group-append">
                    <strong class="input-group-text append-superficie">m2</strong>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="tipologia_id" class="form-label"><strong>Tipología</strong></label>
                <select id="tipologia_id" class="form-control selectpicker show-tick" name="tipologia_id" value="{{ old('tipologia_id') }}">
                  <option value="">Todas</option>
                   @foreach ($tipologias as $tip)
                    <option value="{{$tip->id}}" {{array_key_exists('tipologia_id', $filtros) && $filtros['tipologia_id'] == $tip->id ? 'selected' : ''}}>
                      {{$tip->nombre}}
                    </option>
                   @endforeach
                </select>
              </div>
              <div id="basicas">
                @if ($tipo_propiedad && $tipo_propiedad->caracteristicas->count() > 0)
                  @foreach ($tipo_propiedad->caracteristicas as $c)
                    @if ($c->tipo_caracteristica_id == 3)
                      <div>
                        <label class="switch">
                            <input class="form-check-input" type="checkbox" name="caracteristicas[{{$c->id}}]" value="true"
                            {{ array_key_exists('caracteristicas', $filtros) && array_key_exists($c->id, $filtros['caracteristicas']) ? 'checked' : ''}} />
                          <span class="slider round"></span>
                        </label>
                        <label class="form-check-label">{{$c->nombre}}</label>
                      </div>
                    @endif
                    @if ($c->tipo_caracteristica_id == 4)
                      <div class="form-group mt-2">
                        <label for="tipologia_id" class="form-label"><strong>{{$c->nombre}}</strong></label>
                        <select id="tipologia_id" class="form-control selectpicker show-tick" name="caracteristicas[{{$c->id}}]">
                          <option value="">Todas</option>
                          @foreach ($c->opciones as $opcion)
                            <option value="{{$opcion->id}}" {{ array_key_exists('caracteristicas', $filtros) && array_key_exists($c->id, $filtros['caracteristicas']) && $filtros['caracteristicas'][$c->id] == $opcion->id ? 'selected' :'' }}>
                                {{$opcion->nombre}}
                            </option>
                          @endforeach
                        </select>
                      </div>
                    @endif
                  @endforeach
                @endif
              </div>
              <div id="servicios">
                @if ($tipo_propiedad && $tipo_propiedad->caracteristicas->count()>0)
                  @foreach ($tipo_propiedad->caracteristicas as $c)
                    @if ($c->es_servicio==1)
                      @if ($c->tipo_caracteristica_id == 3)
                        <div>
                          <label class="switch">
                            <input class="form-check-input" type="checkbox" name="caracteristicas[{{$c->id}}]" value="true"
                            {{ array_key_exists('caracteristicas', $filtros) && array_key_exists($c->id, $filtros['caracteristicas']) ? 'checked' : ''}} />
                            <span class="slider round"></span>
                          </label>
                          <label class="form-check-label">{{$c->nombre}}</label>
                        </div>
                      @endif
                      @if ($c->tipo_caracteristica_id == 4)
                        <div class="form-group mt-2">
                          <label class="form-label"><strong>{{$c->nombre}}</strong></label>
                          <select class="form-control selectpicker show-tick" name="caracteristicas[{{$c->id}}]">
                          <option value="">Todas</option>
                          @foreach ($c->opciones as $opcion)
                            <option value="{{$opcion->id}}" {{ array_key_exists('caracteristicas', $filtros) && array_key_exists($c->id, $filtros['caracteristicas']) && $filtros['caracteristicas'][$c->id] == $opcion->id ? 'selected' :'' }}>
                                {{$opcion->nombre}}
                            </option>
                          @endforeach
                          </select>
                        </div>
                      @endif
                    @endif
                  @endforeach
                @endif
              </div>
              <div class="row">
                <div class="col-12 text-right">
                  <button type="submit" class="btn-redondo btn-verde mt-2">Aplicar filtros</button>
                </div>
              </div>
            </div>
          </form>
        </nav>
      </div>
      <div class="col-12 col-md-9 py-md-4">
        <div class="row">
          <div class="col-12">
            <h2>Buscar 
            @if($tipo_propiedad)
              {{$tipo_propiedad->nombre}}s
            @else
              propiedades
            @endif
            @if($operacion) 
              en {{$operacion->nombre}}
            @endif
            </h2>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <button class="navbar-toggler btn-redondo btn-filtrar mobile" type="button" data-sidebar="#sidebar-busqueda">
              Filtrar
            </button>
          </div>
          <span class="divisor-filtro"></span>
          <div class="col-md-12 col">
            @if ($propiedades->total()>1 || $propiedades->total()==0)
              <strong>{{$propiedades->total() }} resultados</strong>
            @else
              <strong>{{$propiedades->total()}} resultado</strong>
            @endif
          </div>
        </div>
        <div class="row">
          @foreach($propiedades as $propiedad)
            <div class="col-12 mt-3">
              @if ($propiedad->destacada == 1)
                @include('propiedades._propiedad-home', [ 'propiedad' => $propiedad, 'destacada' => true ])
              @else 
                @include('propiedades._propiedad-busqueda', [ 'propiedad' => $propiedad ])
              @endif
            </div>
          @endforeach
        </div>
        <div class="mt-5">
          
            {{ $propiedades->links() }}
          
        </div>
      </div>
    </div>
  </div>
@include('propiedades.modals.compartir')
@include('propiedades.modals.contactar')

@endsection
@section('post-scripts')
<script type="text/javascript" src="{{ asset('js/propiedades/busqueda.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/propiedades/contactar.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/propiedades/favorita.js') }}"></script>
<script>
  var emailInteresado = '{{ $emailInteresado }}';
</script>
@endsection