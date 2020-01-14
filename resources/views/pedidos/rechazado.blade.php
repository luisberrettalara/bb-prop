@extends('layouts.app', ['roja' => true])
@section('content')
<div class="estado-pago">
  <div class="row justify-content-center">
    <div class="col-12 text-center my-4">
      <img src="{{asset('/images/fail.svg')}}">
      <h3 class="mt-4">¡ Tu pago ha sido rechazado !</h3>
    </div>
    <div class="col-md-9 col-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-12 text-center">
              <p>Tu Pago <strong>ha sido rechazado</strong> por MercadoPago, es posible que debas ponerte en contacto con tu entidad de pagos.</p>
              <p>Ante cualquier consulta podés ponerte en <strong>Contacto</strong> con nosotros usando el formulario al pie.</p>
              <a class="btn btn-verde" href="{{ route('perfil') }}">Ir a Mi Cuenta</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop