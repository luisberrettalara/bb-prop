@component('mail::message')
# Tu Pago ha sido Rechazado

Lamentablemente el Pago para tu Pedido #{{ $pedido->id }} ha sido rechazado por MercadoPago.

Es posible que debas ponerte en contacto con tu entidad de pagos.

@component('mail::panel')
<strong>Pedido:</strong> #{{ $pedido->id }}<br/>
<strong>Paquete:</strong> {{ $pedido->paquete }}<br/>
<strong>Créditos Normales:</strong> {{ $pedido->creditos_normales }}<br/>
<strong>Créditos Destacados:</strong> {{ $pedido->creditos_destacados }}<br/>
<strong>Total:</strong> ${{ $pedido->total }}<br/>
<strong>Estado:</strong> {{ $pedido->estado }}<br/>
@endcomponent

Puedes ver tus <strong>Créditos Disponibles</strong> y comenzar a Publicar tus Inmuebles ingresando a tu Perfil.

@component('mail::button', ['url' => route('perfil') ])
Mi Perfil
@endcomponent

Saludos,<br>
{{ config('app.name') }}
@endcomponent
