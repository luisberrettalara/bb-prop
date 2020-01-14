@extends('layouts.app', ['azul' => true])
@section('content')
<div class="estado-pago">
  <div class="row justify-content-center">
    <div class="col-12 text-center my-4">
      <img src="{{asset('/images/blue-check.svg')}}">
      <h3 class="mt-4">¡ Tu pago ha sido recibido con éxito !</h3>
    </div>
    <div class="col-md-9 col-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-12 text-center">
              <p>Muchas gracias por tu Pago, el estado de tu Pedido se actualizará en los próximos minutos y los créditos serán asignados a tu cuenta.</p>
              <p>Podés consultar tus <strong>Créditos Disponibles</strong> desde tu perfil.</p>
              <a class="btn btn-verde" href="{{ route('perfil') }}">Ir a Mi Cuenta</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop