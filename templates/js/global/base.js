$(document).ready(function() {

  $(".slider").slider({
    min: 0,
    max: 99
  });

  $(".slider").bind("slide slidestop", function(event, ui) {
    $('#' + $(this).attr('field') + '_display').html(ui.value);
  });

});
