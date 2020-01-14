@component('mail::message')
# Tus créditos están próximos a vencer

@component('mail::panel')

# A tu publicación <a href="{{route('propiedades.show', $propiedad->slug)}}"> {{ $propiedad->getTituloCorto() }} </a> le quedan {{$propiedad->credito->dias_disponibles}} días de crédito disponibles.
@endcomponent

Saludos,<br>
{{ config('app.name') }}
@endcomponent
