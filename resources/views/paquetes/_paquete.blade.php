<div class="col-12 col-md-3">
  <div class="card card-paquete">
    <div class="card-header text-center">
      <h4>{{$paquete}}</h4>
    </div>
    <div class="card-body">
      <div class="icono">
        @if($paquete->icono_url)
          <img src="{{ asset($paquete->icono_url) }}">
        @endif
      </div>
      <ul>
        @if($paquete->creditosDestacados()>0)
          <li>{{$paquete->creditosDestacados()}} {{$paquete->creditosDestacados()>0? 'Destacados' : 'Destacado'}} durante {{$paquete->obtenerDuracionCreditosDestacados()}} días</li>
        @endif
       
        @if($paquete->creditosStandard()>0)
          <li>{{$paquete->creditosStandard()}} Standard durante {{$paquete->obtenerDuracionCreditosStandard()}} días</li>
        @endif
        <li class="mt-5">{{$paquete->obtenerVencimiento()}} días desde la compra</li>
      </ul>
    </div>
    <div class="card-footer text-center">
      <h4 class="mb-0">$ {{$paquete->precio}}</h4>
      <p>Sin impuestos <br/> Sin comisión de Mercado Pago</p>
      <a class="btn-redondo btn btn-outline-info btn-paquete">Seleccionar</a>
      <input type="hidden" name="paquete" class="input-paquete" value="{{ $paquete->id }}" disabled>
    </div>
  </div>
</div>