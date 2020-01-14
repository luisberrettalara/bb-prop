@component('mail::message')
# Tu Factura por tu Pedido

¡Muchas gracias por tu compra! Adjuntamos la factura electrónica por tu Pedido #{{ $pedido->id }}.

@component('mail::panel')
<strong>Pedido:</strong> #{{ $pedido->id }}<br/>
<strong>Factura:</strong> {{ $pedido->getNumeroFacturaFormato() }}<br/>
<strong>Total:</strong> ${{ $pedido->getImporteTotalFormato() }}<br/>
@endcomponent

Puedes ver tus <strong>Créditos Disponibles</strong> y comenzar a Publicar tus Inmuebles ingresando a tu Perfil.

@component('mail::button', ['url' => route('perfil') ])
Mi Perfil
@endcomponent

Saludos,<br>
{{ config('app.name') }}
@endcomponent
