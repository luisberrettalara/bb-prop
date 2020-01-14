@extends('layouts.app', ['grisAzul' => true])
@section('content')
<div class="paquete">
  <div class="row">
    <div class="col-12">
      <h3 class="mt-4 mb-3 text-md-left text-center">Nuestros Planes</h3>
      <div class="row mt-5">
        <div class="col-12 text-center">
          <h4>Nuestros planes están especialmente preparados para vos</h4>
          <span class="mb-5">No pierdas la oportunidad de destacarte de otras propiedades</span>
        </div>
      </div>
      <form method="POST" action="" class="frm-comprar">
        <div class="row align-items-center justify-content-center mt-5">
          @foreach ($paquetes as $paquete)
              @include('paquetes._paquete', ['paquete' => $paquete])
          @endforeach
        </div>
        <div class="row">
          <div class="col-12 text-center">
            <div class="invalid-feedback"></div>
            <p>A continuación podrás pagar con <img src="{{ asset('/images/mp-logo.svg') }}"></p> 
            <button type="submit" class="btn btn-redondo btn-verde btn-primary btn-confirmar" id="btn-confirmar-paquete">Seleccionar un paquete</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('post-scripts')
<script src="//secure.mlstatic.com/mptools/render.js"></script>
<script>
  $(document).ready(function () {

    var submit = $('#btn-confirmar-paquete');

    $('.frm-comprar').submit(function (ev) {
      ev.preventDefault();

      $(submit).prop('disabled', true).html('Cargando...');

      $.post('/paquetes/comprar', $(this).serialize(), function (init_point) {
        $MPC.openCheckout ({
          url: init_point,
          mode: 'modal',
          onreturn: function (json) {
            if (json.collection_status == null) {
              $(submit).prop('disabled', false).text('Continuar con el paquete seleccionado');
            }
          }
        });
      }).fail(function(xhr) {
        let mensajes = '';
        $(submit).prop('disabled', false);
        $(submit).html('Seleccionar un paquete');
        $.each(xhr.responseJSON.errors, function (k, e) {
          $.each(e, function (mk, m) {
            mensajes = mensajes.concat('<p>').concat(m).concat('</p>');
          });
        });
         $('.btn-redondo').siblings('.invalid-feedback').html(mensajes).show();
      });

      return false;
    });

    $('.btn-paquete').on('click', function(){
      var $span = $('<span>').addClass('tick');
      $('.card-paquete').removeClass('seleccionada');
      $(this).parents('.card-paquete').addClass('seleccionada');
      $('.card-paquete span').remove();
      $(this).parents('.card-paquete').find('.card-header').append($span);
      $('.btn-paquete').text('Seleccionar');
      $(this).text('Seleccionado');
      $('.input-paquete').prop('disabled', true);
      $(this).siblings('.input-paquete').prop('disabled', false);
      $(submit).prop('disabled', false).text('Continuar con el paquete seleccionado');
    });
  });
</script>
@stop