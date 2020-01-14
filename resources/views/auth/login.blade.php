@extends('layouts.app')
@section('content')
<div class="p-0">
  <div class="login mt-3 mt-md-0">
    <div class="row img-fondo-login">
      <div class="col-12 col-md-10 offset-md-1">
        <div class="row bg-white p-md-4">
          <div class="col-md-6 col-12">
            <div class="row mt-3">
              <div class="col-12 text-center text-md-left">
                <h1>Abrir una cuenta en BB-Prop es gratis</h1>
                <h5 class="my-3">Tendrás los siguientes beneficios</h5>
              </div>
            </div>
            <div class="row mt-4">
              <div class="col-12">
                <div class="card card-pequeña-login">
                  <div class="card-body card-body-gris">
                    <p><img src="{{ asset('images/heart.svg') }}" class="mr-2">Guardá las propiedades que más te interesan</p>
                    <p><img src="{{ asset('images/house.svg') }}">Contactá propiedades rápidamente</p>
                    <p><img src="{{ asset('images/house-dollar.svg') }}" class="mr-2">Publicá tus propiedades en venta o alquiler</p>
                    <div class="row">
                      <div class="col-12 text-center">
                        <a class="btn btn-outline-info btn-redondo largo" href="" data-toggle="modal" data-target="#modal-registrar">Crear cuenta</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-12 mt-4">
            @if(session('mensaje'))
              <div class="alert alert-warning" role="alert">
                {{session('mensaje')}}
              </div> 
            @endif 
            <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
              <h3>Iniciar sesión</h3>
              <div class="form-group row mt-4">
                <div class="col-12">
                  <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                  @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('email') }}</strong>
                    </span>
                  @endif
                  <span class="icono-input">
                    <i class="fa fa-envelope"></i>
                  </span>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-12">
                  <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Contraseña" required>
                  @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('password') }}</strong>
                    </span>
                  @endif
                  <span class="icono-input">
                    <i class="fas fa-lock"></i>
                  </span>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-12">
                  <div class="form-check form-check-inline custom-control custom-checkbox">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="custom-control-input">
                    <label class="custom-control-label" for="remember">Recordar mi contraseña</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12 col-md-4 mb-3 text-center text-md-left">
                  <button type="submit" class="btn btn-primary btn-verde">
                    Ingresar
                  </button>
                </div>
                <div class="col-12 col-md-8 offset-md-0 mb-3">
                  <a class="btn btn-link btn-block pr-0 text-center text-md-right" href="{{ route('password.request') }}">
                    Olvidé mi contraseña
                  </a>
                </div>
              </div>
              <div class="row">
                <div class="col-12 text-center text-md-left">
                  <a href="{{ route('social.auth') }}" class="btn btn-block btn-facebook"><img src="{{asset('/images/log-face.svg')}}"> &nbsp; Continuar con Facebook</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('auth.modal.registro')
@endsection
@section('post-scripts')
  <script type="text/javascript">
    $(function() {
      $('#registro').on('submit', function(ev) {
        ev.preventDefault();
        var form = $(this);
        $.post($(this).attr('action'), $(this).serialize(), function(data) {
          window.location.href = '/';
        }).fail(function(xhr) {
          if(xhr.responseJSON.errors.email) {
            let $feedbackEmail = form.find('.invalid-feedback.email');
            $feedbackEmail.empty();
            $.each(xhr.responseJSON.errors.email, function (k, e) {
              $feedbackEmail.append($('<p>').text(e));
            });
            $feedbackEmail.show();
          }
          
          if (xhr.responseJSON.errors.password) {
            let $feedbackPassword = form.find('.invalid-feedback.password');
            $feedbackPassword.empty();
            $.each(xhr.responseJSON.errors.password, function (k, e) {
              $feedbackPassword.append($('<p>').text(e));
            });
            $feedbackPassword.show();
          }
        });
      });
    })
  </script>
@endsection