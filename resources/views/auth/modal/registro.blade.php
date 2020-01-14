<div class="modal compartir fade login" id="modal-registrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog m-0" role="document">
    <form id="registro" method="POST" action="{{route('register')}}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h3 class="texto-blanco texto-medium mt-2 mr-3">Creá tu Cuenta</h3>
        <button class="close" data-dismiss="modal"><img src="{{asset('/images/close.png')}}"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <p>Para registrarte sólo tenés que completar los siguientes datos:</p>
            <div class="form-group row">
              <div class="col-12">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Tu e-mail" required autofocus>
                  <span class="invalid-feedback email" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                  </span>
                  <span class="icono-input">
                    <i class="fa fa-envelope"></i>
                  </span>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-12">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Elegí una contraseña" required>
                  <span class="invalid-feedback password" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                  </span>
                  <span class="icono-input">
                    <i class="fas fa-lock"></i>
                  </span>
              </div>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-verde registro">Continuar</button>
        <a href="{{ route('social.auth') }}" class="btn btn-block btn-facebook mt-3"><img src="{{asset('/images/log-face.svg')}}"> &nbsp; Continuar con Facebook</a>
      </div>
    </form>
  </div>
</div>