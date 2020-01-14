@component('mail::message')
# Quizás te pueda interesar

Has recibido una Propiedad compartida!

@component('mail::panel')
## {{ $propiedad->getTituloCorto() }}

{{{$propiedad->getDescripcionCorta()}}}

@if ($propiedad->fotoPortada)
![{{ $propiedad->getTituloCorto() }}]({{ url($propiedad->fotoPortada->thumb_url) }} "{{ $propiedad->getTituloCorto() }}")
@endif


### {{$propiedad->tipoPropiedad->nombre}} | {{number_format($propiedad->superficie,0, ',', '.')}}m2. | {{$propiedad->operacion->nombre}}

@if ($propiedad->monto>0 && $propiedad->precio_convenir==0)
# Precio: {{$propiedad->moneda->nombre}} {{number_format($propiedad->monto, 0, ',', '.')}}
@else
# Consultar Precio
@endif
@endcomponent

@component('mail::button', [ 'url' => route('propiedades.show', $propiedad->slug) ])
Más información
@endcomponent

Saludos,<br>
{{ config('app.name') }}
@endcomponent
