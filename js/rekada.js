$(document).ready(function(){
   $('#toggle_1, #granit').hover(function() {
       $('#granit').css({"display": "block"});
       }, function(){
       $('#granit').css({'display': 'none'});
    });
   $('#toggle_2, #fasad').hover(function() {
       $('#fasad').css({"display": "block"});
       }, function(){
       $('#fasad').css({'display': 'none'});
   });
   $('#toggle_3, #kamen').hover(function() {
       $('#kamen').css({"display": "block"});
       }, function(){
       $('#kamen').css({'display': 'none'});
   });
   $('#toggle_4, #rezka').hover(function() {
       $('#rezka').css({"display": "block"});
       }, function(){
       $('#rezka').css({'display': 'none'});
    });
}); 

$(document).ready(function() {
  var opacity = 1.0, toOpacity = 0.5, duration = 400;
  $('h2>a').css('opacity',opacity).hover(function() {
          $(this).fadeTo(duration,toOpacity);
      }, function() {
          $(this).fadeTo(duration,opacity);
      });
});
