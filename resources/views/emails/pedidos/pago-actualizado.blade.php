@component('mail::message')
# Actualización de Pago de Pedido

Se ha actualizado el estado de Pago para el Pedido #{{ $pedido->id }}.

@component('mail::panel')
<strong>Pedido:</strong> #{{ $pedido->id }}<br/>
<strong>Usuario:</strong> {{ $pedido->usuario }}<br/>
<strong>Paquete:</strong> {{ $pedido->paquete }}<br/>
<strong>Créditos Normales:</strong> {{ $pedido->creditos_normales }}<br/>
<strong>Créditos Destacados:</strong> {{ $pedido->creditos_destacados }}<br/>
<strong>Total:</strong> ${{ $pedido->total }}<br/>
<strong>Estado:</strong> {{ $pedido->estado }}<br/>
@endcomponent

Puede consultar el historial de estados del Pedido haciendo click a continuación:

@component('mail::button', ['url' => ''])
Ver Pedido
@endcomponent

Saludos,<br>
{{ config('app.name') }}
@endcomponent
