$(document).ready(function () {
    $('.btn-modal-contactar').click(function (ev) {
        ev.preventDefault();

        var url = $(this).attr('href');
        var razonSocial = $(this).data('razon-social');

        if (emailInteresado) {
            window.location = $(this).attr('href') + '?email=' + emailInteresado;
        }
        else {
            $('#modal-contactar').find('form').attr('action', url)
                                 .find('.razon-social').html(razonSocial);

            $('#modal-contactar').modal();
        }
    });
});