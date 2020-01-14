$(function() {

    if($("#basicas").find("div").length > 0) {
        $("#basicas").prepend($("<h5>").append($("<strong>").text("Características")));
    }
    if($("#servicios").find("div").length > 0) {
        $("#servicios").prepend($("<h5>").addClass("mt-2").append($("<strong>").text("Servicios")));
    }

    $('#localidad_id').on('change', function(e) {
            var localidad = $(this).val();
            $.get('/localidad/' + localidad + '/barrios/', function(data) {

                $('#barrio_id').empty();

                $('#barrio_id').append($("<option>").val("").prop("selected", true).text("Todos"));


                $.each(data, function(index, subcatObj) {
                    $('#barrio_id').append($("<option>").val(subcatObj.id).text(subcatObj.nombre));

                });
                $('#barrio_id').selectpicker('refresh');
            });
    });

    $('#provincia_id').on('change', function(e) {
        var provincia = $(this).val();
        $.get('/provincias/ '+ provincia + '/localidades/', function(data) {

            $('#localidad_id').empty();

            $('#localidad_id').append($("<option>").val("").prop("selected", true).text("Todas"));

            $.each(data, function(index, subcatObj) {

                $('#localidad_id').append($("<option>").val(subcatObj.id).text(subcatObj.nombre));

            });
            $('#localidad_id').selectpicker('refresh');
        });
    });

    $('#tipo_propiedad_id').on('change', function() {
        var tipo = $(this).val();
        $.get('/tipo-propiedad/'+ tipo + '/caracteristicas/', function(data) {
            $("#servicios, #basicas").empty();
            $.each(data, function (idx, c) {

                if (c.tipo_caracteristica_id === 4) {
                    var $group = $("<div>").addClass("form-group mt-3");
                    var $label = $("<label>").addClass("form-label")
                                             .attr("for", "caracteristicas[" + c.id + "]")
                                             .append($("<strong>").html(c.nombre));
                    var $select = $("<select>").attr("name", "caracteristicas[" + c.id + "]")
                                               .addClass("form-control selectpicker show-tick");

                    $select.append($("<option>").val("").html("Todas"));
                    $.each(c.opciones, function (idxOp, op) {
                        $select.append($("<option>").html(op.nombre).val(op.id));
                    });

                    $(c.es_servicio ? "#servicios" : "#basicas").append($group.append($label, $select));

                    $select.selectpicker();
                }

                if (c.tipo_caracteristica_id === 3) {
                    var $group = $("<div>");
                    var $switch = $("<label>").addClass("switch");
                    var $span  = $("<span>").addClass("slider round");
                    var $input = $("<input>").addClass("form-check-input")
                                             .attr("type", "checkbox")
                                             .attr("value", true)
                                             .attr("name", "caracteristicas[" + c.id + "]");
                    var $label = $("<label>").addClass("form-check-label form-check-caracteristicas").html(c.nombre);

                    $(c.es_servicio ? "#servicios" : "#basicas").append($group.append($switch.append($input, $span), $label));
                }
            });

            if($("#basicas").find("div").length > 0) { 
                $("#basicas").prepend($("<h5>").append($("<strong>").text("Características")));
            }
            if($("#servicios").find("div").length > 0) {
                $("#servicios").prepend($("<h5>").addClass("mt-2").append($("<strong>").text("Servicios")));
            }
        });
    });
});