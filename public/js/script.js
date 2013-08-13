$(document).ready(function() {
    $('.image-upload').each(function() {
        var insertImage = function(name, $insertBeforeElm, isNew) {
            var $insertedImage = $('<a/>')
                .addClass('fancybox admin-image ' + (isNew ? 'new-admin-image' : ''))
                .attr('href', '/files/' + name)
                .append(
                    $('<img/>').attr('src', '/files/' + name)
                ).insertBefore($insertBeforeElm);
            return $insertedImage;
        }
        var $input = $(this);
        var $currImage = null;
        if ($(this).val().length > 0) {
            $currImage = insertImage($(this).val(), $input, false);
        }

        var $fileInput = $('<input/>').attr('type', 'file').attr('name', 'files[]').attr('data-url', "/admin/system/upload");
        $(this).before($fileInput);

        $fileInput.fileupload({
            dataType: 'json',
            done: function (e, data) {
                if ($currImage) $currImage.remove();
//                $('.new-admin-image').remove();
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
        dateFormat: 'yy-mm-dd'
    });

    var config = {};
    config.language = 'ru';
    config.toolbarGroups = [
        { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
        { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
        { name: 'links' },
        { name: 'insert' },
        { name: 'forms' },
        { name: 'tools' },
        { name: 'document',    groups: [ 'mode', 'document', 'doctools' ] },
        { name: 'others' },
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
        { name: 'styles' },
        { name: 'colors' },
        { name: 'about' }
    ];
    config.filebrowserBrowseUrl = '/vendor/ckfinder/ckfinder.html';
    config.filebrowserImageBrowseUrl = '/vendor/ckfinder/ckfinder.html?Type=Images';
    config.filebrowserFlashBrowseUrl = '/vendor/ckfinder/ckfinder.html?Type=Flash';
    config.filebrowserUploadUrl = '/vendor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
    config.filebrowserImageUploadUrl = '/vendor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
    config.filebrowserFlashUploadUrl = '/vendor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';

    if ($('#content').length > 0) {
        CKEDITOR.replace('content', config);
    }
    if ($('#description').length > 0) {
        CKEDITOR.replace('description', config);
    }
});
