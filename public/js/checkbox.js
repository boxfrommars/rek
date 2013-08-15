$(document).ready(function(){
    $(".niceCheck").parent('p').click(function(e) { /* при клике на чекбоксе меняем его вид и значение */
        changeCheck($(this).find('.niceCheck'));
    });

    /* при загрузке страницы нужно проверить какое значение имеет чекбокс и в соответствии с ним выставить вид */
    $(".niceCheck").each(function() {
         changeCheckStart($(this));
    });
    paginate();
});

/*
 функция смены вида и значения чекбокса
 el - span контейнер для обычного чекбокса
 input - чекбокс
 */
function changeCheck(el) {
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

/*
 если установлен атрибут checked, меняем вид чекбокса
 */
function changeCheckStart(el) {
    var el = el,
        input = el.find("input").eq(0);

    if(input.prop("checked")) {
        el.css("background-image","url(/img/checkbox.png)");
    }

    return true;
}

var perPage = 30;
var countPages = 0;

var paginate = function() {
    var $allProducts = $('#product-container .product_from_catalog');
    var count = $allProducts.length;
    $allProducts.not($allProducts.slice(0, perPage)).hide();
    countPages = Math.ceil(count/perPage);
    refreshPaginator(1);
    console.log('total: ' + count, 'perPage: ' + perPage, 'pages: ' + countPages);
}

var refreshPaginator = function(curPage) {
    if (countPages <= 1) return;
    $('.product-pagination').html('');

    var $allLink = (~~curPage == 0) ? $('<span></span>').text('все') : $('<a></a>').addClass('pagination-link-all').attr('href', '#').text('все');
    $('.product-pagination').append($('<li></li>').append($allLink));

    var $prevLink = (~~curPage > 1) ? $('<a></a>').addClass('pagination-link').attr('href', '#').attr('data-show-page', curPage - 1).text('<') : $('<span></span>').text('<');
    $('.product-pagination').append($('<li></li>').append($prevLink));
    var $link;
    for (var i = 1; i <= countPages; i++) {
        if (i === ~~curPage) {
            $link = $('<span></span>').text(i);
        } else {
            $link = $('<a></a>').addClass('pagination-link').attr('href', '#').attr('data-show-page', i).text(i);
        }
        $('.product-pagination').append($('<li></li>').append($link));
    }
    var $nextLink = (~~curPage < countPages) ? $('<a></a>').addClass('pagination-link').attr('href', '#').attr('data-show-page', curPage + 1).text('>') : $('<span></span>').text('>');
    $('.product-pagination').append($('<li></li>').append($nextLink));

}

var showProductPage = function(pageNum) {
    pageNum = ~~ pageNum;
    var $allProducts = $('#product-container .product_from_catalog');
    var $productsToShow = (pageNum > 0) ? $allProducts.slice((pageNum - 1) * perPage, pageNum * perPage) : $allProducts;
    $allProducts.hide();
    refreshPaginator(pageNum);
    $productsToShow.fadeIn();

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
        $('#product-container').load('/catalog/api', data, function(){
            paginate();
        });
        prevData = data;
    }
}

$(function() {

    $('.product-pagination').on('click', '.pagination-link', function(){
        showProductPage($(this).attr('data-show-page'));
        return false;
    });
    $('.product-pagination').on('click', '.pagination-link-all', function(){
        showProductPage();
        return false;
    });
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

    $('.niceCheck').parent('p').on('click', function(){
        reloadProducts();
    });
});

