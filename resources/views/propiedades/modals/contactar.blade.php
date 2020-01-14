<div class="modal compartir fade" id="modal-contactar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="texto-blanco texto-medium mt-2 mr-3">Contactar al Anunciante</h3>
        <button class="close" data-dismiss="modal"><img src="{{asset('/images/close.png')}}"></button>
      </div>
      <div class="modal-body">
        <form method="GET" action="">
          <p>Colocá tu email para poder ver los datos de <strong class="razon-social"></strong>:</p>

          <div class="form-group">
            <input type="email" name="email" placeholder="Email" class="form-control" required>
          </div>

          <div class="form-check form-check-inline custom-control custom-checkbox">
            <input type="checkbox" name="suscribirse" value="1" class="custom-control-input" checked>
            <label for="suscribirse" class="custom-control-label">Quiero recibir novedades sobre Inmuebles similares</label>
          </div>
          <div class="mt-4 text-center text-md-right">
            <button type="submit" class="btn btn-verde">Continuar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>