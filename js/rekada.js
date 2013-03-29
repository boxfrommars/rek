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
    
  // полупрозрачность   
  var opacity = 1.0, toOpacity = 0.5, duration = 400;
  $('h2>a').css('opacity',opacity).hover(function() {
          $(this).fadeTo(duration,toOpacity);
      }, function() {
          $(this).fadeTo(duration,opacity);
      });
     
     //подключение галереи 
    $(function() {
         $('.pict_gallery a').lightBox();
         });
    
    //поиск
    $('#search_menu a').click(function(){
        $(this).parent('#search_menu').find('a').css({ 'opacity':'0.6' });
        $(this).css({'opacity':'1' });
        var menuIndex = $(this).index();
         $('.result_block').hide();
        $('.result_block').eq(menuIndex).show();
    });
    
    $('#search_catalog_result p').hover(function(){
        $(this).addClass('hover_search');
        }, function() {
            $(this).removeClass('hover_search');
    });
    
    //мираж
    $('#collection p').hover(function(){
        $(this).addClass('hover_search');
        }, function() {
            $(this).removeClass('hover_search');
    });
}); 
