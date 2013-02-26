$(document).ready(function(){
   $('#toggle_1').click(function() {
        $('.hide_block').hide();
        $('#granit').show();
        });
   $('#toggle_2').click(function() {
        $('.hide_block').hide();
        $('#fasad').show();
   });
   $('#toggle_3').click(function() {
        $('.hide_block').hide();
        $('#kamen').show();
   });
   $('#toggle_4').click(function() {
        $('.hide_block').hide();
        $('#rezka').show();
    });
});

$(document).click(function(e){
    if ($(e.target).parents().filter('#toggle_1:visible').length != 1) {
        $('#granit').hide();
    };
     if ($(e.target).parents().filter('#toggle_2:visible').length != 1) {
        $('#fasad').hide();
    };
    if ($(e.target).parents().filter('#toggle_3:visible').length != 1) {
        $('#kamen').hide();
    };
    if ($(e.target).parents().filter('#toggle_4:visible').length != 1) {
        $('#rezka').hide();
    }
});

$(document).ready(function() {
  var opacity = 1.0, toOpacity = 0.5, duration = 400;
  $('h2>a').css('opacity',opacity).hover(function() {
          $(this).fadeTo(duration,toOpacity);
      }, function() {
          $(this).fadeTo(duration,opacity);
      }
  );
});