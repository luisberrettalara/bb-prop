$(function() {
  $('.favorita').on('click', function() {
    var url = $(this).attr('href');
    if(url.includes("login")) {
      window.location = url;
    }
    else {
      if($(this).hasClass('unlike')){
        $(this).removeClass('unlike');
        $(this).addClass('like');
      }
      else {
        $(this).addClass('unlike');
        $(this).removeClass('like');
      }

      $.get(url, function(data) {
         return false;
      });
      return false;
    }
  });
});