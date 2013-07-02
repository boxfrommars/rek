$(document).ready(function(){
    $(".niceCheck").parent('p').mousedown(
    /* при клике на чекбоксе меняем его вид и значение */
    function() {
         changeCheck($(this).find('.niceCheck'));
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
   	 if(!input.prop("checked")) {
		el.css("background-image","url(/img/checkbox.png)");
		input.prop("checked", true)
	} else {
		el.css("background-image","url(/img/empty_checkbox.png)");
		input.prop("checked", false)
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
      if(input.prop("checked")) {
		el.css("background-image","url(/img/checkbox.png)");
		}
     return true;
}


var prevData = {};
var reloadProducts = function(){
    var data = {};
    $('.product-filter').each(function(k, v) {
        if(data.hasOwnProperty($(v).attr('name'))){
            data[$(v).attr('name')] += ',' + $(v).val();
        } else {
            data[$(v).attr('name')] = '' + $(v).val();
        }
    });
    $('.product-filter-checkbox:checked').each(function(k, v) {
        if(data.hasOwnProperty($(v).attr('name'))){
            data[$(v).attr('name')] += ',' + $(v).val();
        } else {
            data[$(v).attr('name')] = '' + $(v).val();
        }
    });
    if (prevData != data) {
        $('#product-container').load('/catalog/api', data);
        prevData = data;
    }
//    var url = '/catalog/api/index/';
//    for (part in data) {
//        url += '/' + (part + '/' + data[part]);
//    }
//    console.log(url);
//    location.href = url;
}



$(function() {
    var $costSlider = $( ".slider-cost" );
    var $depthSlider = $( ".slider-depth" );

    $costSlider.slider({
        min: ~~$("input#minCost").attr('data-value'),
        max: ~~$("input#maxCost").attr('data-value'),
        range: true,
        values: [~~$("input#minCost").attr('data-value'), ~~$("input#maxCost").attr('data-value')],
        stop: function(event, ui) {
            $("input#minCost").val($costSlider.slider("values",0));
            $("input#maxCost").val($costSlider.slider("values",1));
            reloadProducts();
        },
        slide: function(event, ui){
            $("input#minCost").val($costSlider.slider("values",0));
            $("input#maxCost").val($costSlider.slider("values",1));
        }
    });
    $depthSlider.slider({
        min: ~~$("input#minDepth").attr('data-value'),
        max: ~~$("input#maxDepth").attr('data-value'),
        range: true,
        values: [~~$("input#minDepth").attr('data-value'), ~~$("input#maxDepth").attr('data-value')],
        stop: function(event, ui) {
            $("input#minDepth").val($depthSlider.slider("values",0));
            $("input#maxDepth").val($depthSlider.slider("values",1));
            reloadProducts();
        },
        slide: function(event, ui){
            $("input#minDepth").val($depthSlider.slider("values",0));
            $("input#maxDepth").val($depthSlider.slider("values",1));
        }
    });

    $('.niceCheck').on('click', function(){
        reloadProducts();
    });
});

