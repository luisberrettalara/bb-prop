<div class="modal compartir fade" id="modal-compartir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <form method="POST" action="" class="modal-content share-email">
      <div class="modal-header">
        <h3 class="texto-blanco texto-medium mt-2 mr-3">Compartir propiedad</h3>
        <button class="close" data-dismiss="modal"><img src="{{asset('/images/close.png')}}"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12 col-md-6 align-self-center">
            <p class="m-0">Compartir en Redes</p>
          </div>
          <div class="col-12 col-md-6 align-self-center">
            <a href="" target="_blank" class="whatsapp mr-2">
              <i class="fab fa-whatsapp fa-3x"></i>
            </a>
            <a href="" target="_blank" class="facebook">
              <i class="fab fa-facebook-square fa-3x"></i>
            </a>
          </div>
        </div>
        <span class="divisor mt-4"></span>

        <div class="row">
          <div class="col-12">
            <p class="mt-4">Compartir por mail</p>
            <input type="hidden" name="propiedad" value="">
            <input type="email" name="email" placeholder="Email" class="form-control mt-3" required>
          </div>
        </div>
        <div class="mt-4 text-center text-md-right">
          <button type="submit" class="btn btn-verde">Compartir</button>
        </div>
      </div>
    </form>
  </div>
</div>