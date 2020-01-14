$(function() {
    var marker;
    var style = [
      {
        "featureType": "poi.business",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "poi.government",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }
    ];
    initMap();

    function initMap() {
        
        var map = new google.maps.Map($('#map')[0], {
            zoom: 16,
            center: {
                lat: -38.718400122181706,
                lng: -62.266513552514766
            },
            styles : style,
            mapTypeId : google.maps.MapTypeId.ROADMAP,
            disableDefaultUI : true
        });
        var geocoder = new google.maps.Geocoder();

        marker = new google.maps.Marker({
            map: map,
            draggable: true
        });
        marker.addListener('dragend', function(event) {
            $('#lat').val(event.latLng.lat());
            $('#long').val(event.latLng.lng());
        });

        let lat = $('#lat').val();
        let long = $('#long').val();

        if (lat.length > 0 && long.length > 0) {
            var loc = new google.maps.LatLng(lat, long);
            map.setCenter(loc);
            marker.setPosition(loc);
        }

        $('#direccion, #provincia_id, #localidad_id, #barrio_id').on('change', function() {
            geocodeAddress(geocoder, map);
        });
    }

    function geocodeAddress(geocoder, resultsMap) {
        var direccion = $('#direccion').val() || '';
        var provincia = $('#provincia_id option[value="'+$('#provincia_id').val()+'"]').text() || '';
        var localidad = $('#localidad_id').val() || '';
        var barrio = $('#barrio_id option:selected').text() || '';
        var address = direccion + 
        (barrio.length > 0 ? ', ' + barrio : '') + 
        (localidad.length > 0 ? ', ' + localidad : '') +
        (provincia.length > 0 ? ', ' + 'Provincia de ' + provincia : '');
        geocoder.geocode({
            'address': address,
            'componentRestrictions' : {country : 'Ar'},
        }, function(results, status) {
            if (status === 'OK') {
                var loc = results[0].geometry.location;
                resultsMap.setCenter(loc);
                marker.setPosition(loc);
                $('#lat').val(loc.lat());
                $('#long').val(loc.lng());
            } else {
                console.log('Geocode was not successful for the following reason: ' + status);
            }
        });
    }

    if($("#basicas").find("div").length > 0) { 
      $("#basicas").prepend($("<h3>").addClass("mb-3").append($("<strong>").text("Básicas")));
    }
    if($("#servicios").find("div").length > 0) {
      $("#servicios").prepend($("<h3>").append($("<strong>").text("Servicios")));
    }

    $(".change-card").on('click', function() {
      if(!$(this).hasClass('inactivo')) {
        $(".card-body.interchangeable").not('.d-none').addClass("d-none");
        $($(this).attr("data-ref")).removeClass("d-none");
        $(".indicador-progreso").removeClass().addClass('indicador-progreso ' + $(this).attr("data-step"));
      }
      else {
        return false;
      } 
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#loc_place_id').on('change', function(e) {
        var localidad = $(this).val();
        $.get('/localidades/' + localidad + '/barrios/', function(data) {

            $('#barrio_id').empty();

            $('#barrio_id').append($("<option>").val("").prop("selected", true).prop("disabled", true).text("Seleccione"));

            $.each(data, function(index, subcatObj) {
                $('#barrio_id').append($("<option>").val(subcatObj.id).text(subcatObj.nombre));
            });
            $('#barrio_id').selectpicker('refresh');
        });
    });

    $('#provincia_id').on('change', function(e) {
        var provincia = $(this).val();
        $.get('/provincias/' + provincia + '/localidades/', function(data) {
            $.each(data, function(index, subcatObj) {
 
                $('#localidad_id').append($("<option>").val(subcatObj.id).text(subcatObj.nombre));
 
            });
        });
    });

    $('#tipo_propiedad_id').on('change', function() {
        var tipo = $(this).val();
        $.get('/tipo-propiedad/'+ tipo + '/caracteristicas/', function(data) {
            $("#servicios, #basicas").empty();
            $.each(data, function (idx, c) {
                var $control;
                switch(c.tipo_caracteristica_id) {
                    case 1:
                        $control = crearControl(idx, c.nombre, c.unidad, true, c.id, c.es_servicio);
                    break;
                    case 2:
                        $control = crearControl(idx, c.nombre, c.unidad, false,  c.id, c.es_servicio);
                    break;
                    case 3:
                        $control = crearControlOpcion(idx, c.nombre,  c.id, c.es_servicio);
                    break;
                    case 4:
                        $control = crearControlDropdown(idx, c.nombre, c.id, c.es_servicio, c.opciones);
                    break;
                }
                $(c.es_servicio ? "#servicios" : "#basicas").append($control);
            });

            if($("#basicas").find("div").length > 0) {
              $("#basicas").prepend($("<h3>").addClass("mb-3").append($("<strong>").text("Básicas")));
            }
            if($("#servicios").find("div").length > 0) {
              $("#servicios").prepend($("<h3>").append($("<strong>").text("Servicios")));
            }

            $("#servicios .selectpicker, #basicas .selectpicker").selectpicker();
        });

      if ($(".change-card[data-ref='#caracteristicas']").hasClass("inactivo")) {
        $(".change-card[data-ref='#caracteristicas']").removeClass("inactivo");
      }
    });

    $(document).on('change', ".checkeable-true", function() {
        $(this).parents(".form-check")
               .find(".checkeable-false")
               .prop("disabled", $(this).is(":checked"));
    });

    function crearControl(idx, nombre, unidad, numerico, id, es_servicio) {
        var $group = $("<div>").addClass("form-group");
        var $label = $("<label>").addClass("form-label");
        var $strong = $("<strong>").text(nombre);
        var $hidid = $("<input>").attr("type","hidden")
                                 .attr("value", id)
                                 .attr("name", "caracteristicas[" + idx + "][caracteristica_id]");

        var $hidti = $("<input>").attr("type","hidden")
                                 .attr("value", numerico ? 1 : 2)
                                 .attr("name", "caracteristicas[" + idx + "][tipo_caracteristica_id]");

        var $hidix = $("<input>").attr("type","hidden")
                                 .attr("value", idx)
                                 .attr("name", "caracteristicas[" + idx + "][idx]");

        var $hidse = $("<input>").attr("type","hidden")
                                 .attr("value", es_servicio)
                                 .attr("name", "caracteristicas[" + idx + "][es_servicio]");

        var $hidno = $("<input>").attr("type","hidden")
                                 .attr("value", nombre)
                                 .attr("name", "caracteristicas[" + idx + "][nombre]");

        var $hiduni = $("<input>").attr("type","hidden")
                                 .attr("value", unidad)
                                 .attr("name", "caracteristicas[" + idx + "][unidad]");

        var $input = $("<input>").addClass("form-control")
                                 .attr("type", numerico ? "number" : "text")
                                 .attr("name", "caracteristicas[" + idx + "][valor]");

        if(unidad != undefined && unidad.length > 0) {
            var $inpgr = $("<div>").addClass("input-group");
            var $inpap = $("<div>").addClass("input-group-append");
            var $inptx = $("<span>").addClass("input-group-text").text(unidad);

            $inpap.append($inptx);
            $inpgr.append($input, $inpap);
            $group.append($label, $strong, $hidti, $hidix, $hidid, $hidse, $hidno, $hiduni, $inpgr);
        }
        else {
            $group.append($label, $strong, $hidti, $hidix, $hidid, $hidse, $hidno, $hiduni, $input);
        }

        return $group;
    }

    function crearControlOpcion(idx, nombre, id, es_servicio) {
        var $group = $("<div>").addClass("form-group");
        var $hidid = $("<input>").attr("type","hidden")
                                 .attr("value", id)
                                 .attr("name", "caracteristicas[" + idx + "][caracteristica_id]");

        var $hidti = $("<input>").attr("type","hidden")
                                 .attr("value", 3)
                                 .attr("name", "caracteristicas[" + idx + "][tipo_caracteristica_id]");

        var $hidix = $("<input>").attr("type","hidden")
                                 .attr("value", idx)
                                 .attr("name", "caracteristicas[" + idx + "][idx]");

        var $hidse = $("<input>").attr("type","hidden")
                                 .attr("value", es_servicio)
                                 .attr("name", "caracteristicas[" + idx + "][es_servicio]");

        var $hidno = $("<input>").attr("type","hidden")
                                 .attr("value", nombre)
                                 .attr("name", "caracteristicas[" + idx + "][nombre]"); 

        var $hidfa = $("<input>").addClass("checkeable-false")
                                 .attr("type","hidden")
                                 .attr("value", "0")
                                 .attr("name", "caracteristicas[" + idx + "][valor]");
        var $check = $("<div>").addClass("form-check form-check-inline custom-control custom-checkbox");
        var $input = $("<input>").addClass("checkeable-true custom-control-input")
                                 .attr("type","checkbox")
                                 .attr("value", "1")
                                 .attr("name", "caracteristicas[" + idx + "][valor]");
                                 
        var $label = $("<label>").addClass("custom-control-label").attr("for", "caracteristicas[" + idx + "][valor]").text(nombre);

        $check.append($input, $label);
        return $group.append($hidid, $hidti, $hidix, $hidse, $hidfa, $hidno, $check);

    }

    function crearControlDropdown(idx, nombre, id, es_servicio, opciones) {
        var $group = $("<div>").addClass("form-group");

        var $label = $("<label>").addClass("form-label");

        var $strong = $("<strong>").text(nombre);

        var $hidid = $("<input>").attr("type","hidden")
                                 .attr("value", id)
                                 .attr("name", "caracteristicas[" + idx + "][caracteristica_id]");

        var $hidti = $("<input>").attr("type","hidden")
                                 .attr("value", 4)
                                 .attr("name", "caracteristicas[" + idx + "][tipo_caracteristica_id]");

        var $hidix = $("<input>").attr("type","hidden")
                                 .attr("value", idx)
                                 .attr("name", "caracteristicas[" + idx + "][idx]");

        var $hidse = $("<input>").attr("type","hidden")
                                 .attr("value", es_servicio)
                                 .attr("name", "caracteristicas[" + idx + "][es_servicio]");

        var $hidno = $("<input>").attr("type","hidden")
                                 .attr("value", nombre)
                                 .attr("name", "caracteristicas[" + idx + "][nombre]"); 

        var $select = $("<select>").addClass("form-control selectpicker show-tick")
                                   .attr("name", "caracteristicas[" + idx + "][valor]")
                                   .append($("<option>").val("").prop("selected", true).text("Seleccione"));

        var $hidop = [];
        $.each(opciones, function(i, v) {
          $hidop.push($("<input>").attr("type", "hidden")
                                  .attr("name", "caracteristicas[" + idx + "][opciones][" + i + "][id]")
                                  .attr("value", v.id)
                      );          
          $hidop.push($("<input>").attr("type", "hidden")
                                  .attr("name", "caracteristicas[" + idx + "][opciones][" + i + "][nombre]")
                                  .attr("value", v.nombre)
                      );
          $select.append($("<option>").val(v.id).text(v.nombre));
        })

        $label.append($strong);
        return $group.append($hidid, $hidti, $hidix, $hidse, $hidno, $label, $select, $hidop);
    }

    Dropzone.options.myDropzone = {
        url: '/fotos/temporal',
        paramName: "foto",
        acceptedFiles: 'image/*',
        maxFilesize: 12, 
        maxFiles: 35, 
        addRemoveLinks: true,
        dictRemoveFile: 'Eliminar',
        dictCancelUpload: 'Cancelar',
        dictInvalidFileType: 'El tipo de archivo es incorrecto, solo se aceptan imagenes',
        dictFileTooBig: 'El tamaño máximo de imagen es {{maxFilesize}}MB',
        dictMaxFilesExceeded: 'La cantidad maxima de imagenes es {{maxFiles}}',
        init: function() {
          var $dz = this;
          var count = 0;
          
          $(".foto").each(function () {
            var url = $(this).find(".url").val();
            var thumb = $(this).find(".url").attr("data-thumb-url");
            var fid = $(this).attr("data-fid");
            var fip = $(this).attr("data-pid");
            var mockFile = { name: url, size: 12345, fid: fid };
            $dz.files.push(mockFile);
            $dz.emit("addedfile", mockFile);
            $dz.emit("success", mockFile);  
            $dz.emit("complete", mockFile);
            $dz.emit("thumbnail", mockFile, thumb);
            if(fid != undefined && fip != 1) {
              let pid = $("#propiedad-id").val();
              let url = "/propiedades/"+ pid +"/fotos/" + fid + "/portada";
              let lin = $("<a>").attr("href", url).text("Definir portada");
              mockFile.previewTemplate.appendChild(lin[0]);
            }
            count ++;
          });

          $dz.options.maxFiles = $dz.options.maxFiles - count;

          $dz.on("success", function(file, response) {
            var idx = $(".foto").length;
            var $div = $("<div>").addClass("foto")
                                 .attr("data-uuid", file.upload.uuid)
                                 .attr("data-fid", file.upload.fid)
                                 .attr("data-pid", file.upload.pid);
            var $inputUrl = $("<input>").attr("type", "hidden")
                        .attr("name", "fotos[" + idx + "][url]")
                        .attr("value", response.url)
                        .attr("data-thumb-url", response.thumbUrl)
            var $inputAlto = $("<input>").attr("type", "hidden")      
                        .attr("name", "fotos[" + idx + "][alto]")
                        .attr("value", response.alto)
            var $inputAncho = $("<input>").attr("type", "hidden")
                        .attr("name", "fotos[" + idx + "][ancho]")
                        .attr("value", response.ancho)
            $("#propiedad").append($div);
            $div.append($inputUrl, $inputAncho, $inputAlto);
          });

          $dz.on("removedfile", function(file) {
            if (file.fid != undefined) {
              $(".foto[data-fid='" + file.fid + "']").remove();

              var $input = $("<input>").attr("type", "hidden")
                                       .attr("name", "fotos-eliminar[]")
                                       .attr("value", file.fid);
              $("#propiedad").append($input);
            }
            else {
              $(".foto[data-uuid='" + file.upload.uuid + "']").remove();
            }
          });

          $dz.on("error", function(file, error, xhr) {
            $(file.previewTemplate).find(".dz-error-message").text(error.message);
            console.log(file, error, xhr);
          });
        }
    };
});