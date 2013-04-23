$(document).ready(function(){
    $(".niceCheck").mousedown(
    /* при клике на чекбоксе меняем его вид и значение */
    function() {
         changeCheck($(this));
    });
    $(".niceCheck").each(
    /* при загрузке страницы нужно проверить какое значение имеет чекбокс и в соответствии с ним выставить вид */
    function() {
         changeCheckStart($(this));
    });
});

function changeCheck(el)
/* 
	функция смены вида и значения чекбокса
	el - span контейнер для обычного чекбокса
	input - чекбокс
*/
{
     var el = el,
        input = el.find("input").eq(0);
   	 if(!input.attr("checked")) {
		el.css("background-image","url(/img/checkbox.png)");
		input.attr("checked", true)
	} else {
		el.css("background-image","url(/img/empty_checkbox.png)");
		input.attr("checked", false)
	}
     return true;
}

function changeCheckStart(el)
/* 
	если установлен атрибут checked, меняем вид чекбокса
*/
{
var el = el,
		input = el.find("input").eq(0);
      if(input.attr("checked")) {
		el.css("background-image","url(/img/checkbox.png)");
		}
     return true;
}


$(function() {
		$( "#slider" ).slider({
			min: 1,
            max: 20,
            step: 1,
			range: 'min',
            value: 3
		});
	});

$(function() {
		$( ".slider" ).slider({
			min: 0,
            max: 800,
			range: true,
            values: [250, 600],
            stop: function(event, ui) {
            $("input#minCost").val(jQuery(".slider").slider("values",0));
            $("input#maxCost").val(jQuery(".slider").slider("values",1));
            },
            slide: function(event, ui){
            $("input#minCost").val(jQuery(".slider").slider("values",0));
            $("input#maxCost").val(jQuery(".slider").slider("values",1));
            }
		});
	});

