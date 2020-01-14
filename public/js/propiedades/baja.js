$(document).ready(function () {
    $('.btn-modal-baja').click(function (ev) {
        ev.preventDefault();

        var url = $(this).attr('href');

        $('#modal-baja').find('a').attr('href', url);
        $('#modal-baja').modal();
    });
});