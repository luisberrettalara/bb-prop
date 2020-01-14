$(document).ready(function () {
    $('.btn-modal-finalizar').click(function (ev) {
        ev.preventDefault();

        var url = $(this).attr('href');

        $('#modal-finalizar').find('a').attr('href', url);
        $('#modal-finalizar').modal();
    });
});