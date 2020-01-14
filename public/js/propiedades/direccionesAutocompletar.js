$(function() {

  let googleAutocompleteService = new google.maps.places.AutocompleteService();

  $('#localidad_id').on('keyup', function(e) {
    let provincia = $('#provincia_id').val() ? $('#provincia_id option[value="'+$('#provincia_id').val()+'"]').text() : '';
    if(provincia) {
      let valor = provincia + ' ' + $(this).val();
      if(valor) {
        googleAutocompleteService.getPlacePredictions({
            'input' : valor,
            'types' : ['(cities)'],
            'componentRestrictions' : {country : 'Ar'},
          },
          (predictions, status) => {
            if(status === 'OK') {
              $('#predicciones_localidad').empty();
              predictions.forEach(function(p) {
                let li = $('<li>').attr('data-nombre', p.structured_formatting.main_text).attr('data-place', p.place_id).html(p.description);
                $('#predicciones_localidad').append(li);
              })
            }
            else {
              console.log(predictions, status);
            }
          }
        );
      }
      else {
        $('#predicciones_localidad').empty();
      }
    }

    else {
      $('#predicciones_localidad').empty();
      $('#predicciones_localidad').append($('<li>').text('Selecciona una provincia'));
    }
  });

  $('#predicciones_localidad').on('click', 'li', function() {
    let place_id = $(this).attr('data-place');
    let nombre = $(this).attr('data-nombre');
    if(place_id) {
      $('#localidad_id').val(nombre).attr('data-nombre', nombre);
      $('#loc_place_id').attr('value', place_id);
      $('#loc_place_id').trigger('change');
      $('#predicciones_localidad').empty();
    }
  });

  $('#direccion').on('keyup', function(e) {
    let provincia = $('#provincia_id').val() ? $('#provincia_id option[value="'+$('#provincia_id').val()+'"]').text() : '';
    let localidad = $('#localidad_id').val() ? $('#localidad_id').attr('data-nombre') : '';

     if(provincia && localidad) {
      let valor = provincia + ' ' + localidad + ' ' + $(this).val();
      if(valor) {
        googleAutocompleteService.getPlacePredictions({
            'input' : valor,
            'types' : ['address'],
            'componentRestrictions' : {country : 'Ar'},
          },
          (predictions, status) => {
            if(status === 'OK') {
              $('#predicciones_direccion').empty();
              predictions.forEach(function(p) {
                let li = $('<li>').attr('data-nombre', p.structured_formatting.main_text).attr('data-place', p.place_id).html(p.description);
                $('#predicciones_direccion').append(li);

              })
            }
            else {
              console.log(predictions, status);
            }
          }
        );
      }
      else {
        $('#predicciones_direccion').empty();
      }
    }
    else {
      $('#predicciones_direccion').empty();
      $('#predicciones_direccion').append($('<li>').text('Especifica una localidad'));
    }
  });

  $('#predicciones_direccion').on('click', 'li', function() {
    let place_id = $(this).attr('data-place');
    let nombre = $(this).attr('data-nombre');
    if(place_id) {
      $('#direccion').val(nombre).attr('data-nombre', nombre);
      $('#dir_place_id').attr('value', place_id);
      $('#predicciones_direccion').empty();
    }
  });
});