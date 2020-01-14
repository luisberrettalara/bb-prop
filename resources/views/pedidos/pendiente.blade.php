@extends('layouts.app', ['naranja' => true])
@section('content')
<div class="estado-pago">
  <div class="row justify-content-center">
    <div class="col-12 text-center my-4">
      <img src="{{asset('/images/pending.svg')}}">
      <h3 class="mt-4">¡ Tu pago está pendiente de acreditación !</h3>
    </div>
    <div class="col-md-9 col-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-12 text-center">
              <p>Tu Pago se encuentra <strong>Pendiente de Acreditación</strong>. En cuanto lo hayamos recibido los créditos serán asignados a tu cuenta y te notificaremos por e-mail.</p>
              <p>Podés consultar el estado de tu Pedido en todo momento desde la sección <strong>Mis Compras</strong> en tu perfil.</p>
              <a class="btn btn-verde" href="{{ route('perfil') }}">Ir a Mi Cuenta</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop