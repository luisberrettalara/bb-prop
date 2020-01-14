@component('mail::message')
# Tu Pago está siendo procesado

El Pago para tu Pedido #{{ $pedido->id }} se encuentra <strong>En Proceso</strong>.

En cuanto lo hayamos recibido los créditos serán asignados a tu cuenta y te notificaremos por e-mail.

@component('mail::panel')
<strong>Pedido:</strong> #{{ $pedido->id }}<br/>
<strong>Paquete:</strong> {{ $pedido->paquete }}<br/>
<strong>Créditos Normales:</strong> {{ $pedido->creditos_normales }}<br/>
<strong>Créditos Destacados:</strong> {{ $pedido->creditos_destacados }}<br/>
<strong>Total:</strong> ${{ $pedido->total }}<br/>
<strong>Estado:</strong> {{ $pedido->estado }}<br/>
@endcomponent

Podés consultar el estado de tu Pedido en todo momento desde la sección <strong>Mis Compras</strong> en tu perfil.

@component('mail::button', ['url' => route('perfil') ])
Mi Perfil
@endcomponent

Saludos,<br>
{{ config('app.name') }}
@endcomponent
