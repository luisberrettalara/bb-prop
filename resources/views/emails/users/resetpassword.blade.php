@component('mail::message')
# Hola!

¿Olvidaste tu contraseña?
<br/>
No hay problema, nos pasa a todos.
<br/>
Para reestablecer tu contraseña solo tenés que hacer click a continuación.
@component('mail::button', ['url' => $url])
Reestablecer contraseña
@endcomponent

Saludos,<br>
{{ config('app.name') }}
@endcomponent