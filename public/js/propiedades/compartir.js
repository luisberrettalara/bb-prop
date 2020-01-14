$(document).ready(function () {
  $('#modal-compartir').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('propiedad');
    var url = button.attr('href');
    console.log('Compartiendo propiedad', id, url);

    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this);
    modal.find('input[name=propiedad]').val(id);

    modal.find('form').attr('action', url + '/compartir');
    modal.find('a.whatsapp').attr('href', 'https://api.whatsapp.com:/send?text=' + url);
    modal.find('a.facebook').attr('href', 'https://www.facebook.com/sharer/sharer.php?u=' + url);
  });

  $('form.share-email').submit(function (ev) {
    ev.preventDefault();

    let email  = $(this).find('input[type=email]');
    let submit = $(this).find('button[type=submit]');

    $(submit).prop('disabled', true);
    $(submit).html('Enviando...');

    $.post($(this).attr('action'), $(this).serialize(),
      function (data) {
        $(submit).html('Enviado!');

        setTimeout(function() {
          $(submit).prop('disabled', false);
          $(submit).html('Compartir');
          $(email).val('');
        }, 1000 * 4);
      }
    ).fail(function () {
      $(submit).html('Ha ocurrido un error');

      setTimeout(function() {
        $(submit).prop('disabled', false);
        $(submit).html('Compartir');
        $(email).val('');
      }, 1000 * 4);
    });

    return false;
  });
});