@component('mail::message')
# Solicitud de más información

Has recibido una solicitud de más información sobre tu publicación [{{ $propiedad->titulo }}]({{ route('propiedades.show', $propiedad->slug) }}).

@component('mail::panel')
## E-mail: [{{ $email }}](mailto:{{ $email }})
## Teléfono: {{ $telefono }}

{{ $mensaje }}
@endcomponent

Saludos,<br>
{{ config('app.name') }}
@endcomponent
