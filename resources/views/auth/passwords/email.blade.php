@extends('layouts.app')
@section('content')
<div class="py-4 registro">
  <div class="row">
    <div class="col-12">
      @if (session('status'))
        <div class="alert alert-success" role="alert">
          {{ session('status') }}
        </div>
      @endif
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header pb-3"><h4>Recuperar contraseña</h4></div>
        <div class="card-body">

          <form method="POST" action="{{ route('password.email') }}" aria-label="{{ __('Reset Password') }}">
            @csrf
            <div class="form-group">
              <p>A continuación ingresa la dirección de email de tu cuenta y te enviaremos un enlace para recuperar tu contraseña.</p>
              <label for="email">{{ __('E-Mail Address') }}</label>
              <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
              @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
              @endif
            </div>
            <button type="submit" class="btn btn-verde mt-3">
              Enviar link de recuperación
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
