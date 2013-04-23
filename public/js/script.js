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

    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });

    var config = {};
        config.language = 'ru';
        config.toolbarGroups = [
            { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
            { name: 'editing',     groups: [ 'find', 'selection'] },
            { name: 'links' },
//            { name: 'insert' },
            { name: 'document',    groups: [ 'mode', 'document', 'doctools' ] },
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
            { name: 'styles' },
            { name: 'colors' },
            { name: 'forms' },
            { name: 'tools' },
            { name: 'others' }
        ];

    if ($('#content').length > 0) {
        CKEDITOR.replace('content', config);
    }
    if ($('#description').length > 0) {
        CKEDITOR.replace('description', config);
    }
});
