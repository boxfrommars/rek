$(document).ready(function(){

    $('.image-upload').each(function(){
        var $input = $(this);

        if ($(this).val().length > 0) {
            $('<a/>').addClass('fancybox admin-image').attr('href', '/files/' + $(this).val()).append($('<img/>').attr('src', '/files/thumbnail/' + $(this).val())).insertBefore($input);
        }

        var $fileInput = $('<input/>').attr('type', 'file').attr('name', 'files[]').attr('data-url', "/vendor/jQuery-File-Upload/server/php/");
        $(this).before($fileInput);

        $fileInput.fileupload({
            dataType: 'json',
            done: function (e, data) {
                $('.new-admin-image').remove();
                $.each(data.result.files, function (index, file) {
                    $('<a/>').addClass('fancybox admin-image new-admin-image').attr('href', '/files/' + file.name).append($('<img/>').attr('src', file.thumbnail_url)).insertBefore($input);
                    $input.val(file.name);
                });
            }
        });
    });

    $('body .fancybox').fancybox({
        padding : 0
    });
});
