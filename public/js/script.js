$(document).ready(function() {
    $('.image-upload').each(function() {
        var insertImage = function(name, $insertBeforeElm, isNew) {
            $('<a/>')
                .addClass('fancybox admin-image ' + (isNew ? 'new-admin-image' : ''))
                .attr('href', '/files/' + name)
                .append(
                    $('<img/>').attr('src', '/files/thumbnail/' + name)
                ).insertBefore($insertBeforeElm);
        }
        var $input = $(this);

        if ($(this).val().length > 0) {
            insertImage($(this).val(), $input, false);
        }

        var $fileInput = $('<input/>').attr('type', 'file').attr('name', 'files[]').attr('data-url', "/admin/system/upload");
        $(this).before($fileInput);

        $fileInput.fileupload({
            dataType: 'json',
            done: function (e, data) {
                $('.new-admin-image').remove();
                $.each(data.result.files, function (index, file) {
                    insertImage(file.name, $input, true);
                    $input.val(file.name);
                });
            }
        });
    });

    $('body .fancybox').fancybox({
        padding : 0
    });
});
