@component('mail::message')
# Solicitud de más información

{{$email}} quiere ponerse en contacto con BB-Prop

@component('mail::panel')
## E-mail: [{{ $email }}](mailto:{{ $email }})

{{ $mensaje }}
@endcomponent

@endcomponent
