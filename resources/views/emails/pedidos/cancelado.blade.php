@component('mail::message')
# Tu Pago ha sido Cancelado

El Pago para tu Pedido #{{ $pedido->id }} ha sido <strong>Cancelado</strong>.

Lamentamos lo ocurrido, puedes ponerte en <strong>Contacto</strong> con nosotros o realizar otra Compra.

@component('mail::panel')
<strong>Pedido:</strong> #{{ $pedido->id }}<br/>
<strong>Paquete:</strong> {{ $pedido->paquete }}<br/>
<strong>Créditos Normales:</strong> {{ $pedido->creditos_normales }}<br/>
<strong>Créditos Destacados:</strong> {{ $pedido->creditos_destacados }}<br/>
<strong>Total:</strong> ${{ $pedido->total }}<br/>
<strong>Estado:</strong> {{ $pedido->estado }}<br/>
@endcomponent

Saludos,<br>
{{ config('app.name') }}
@endcomponent
