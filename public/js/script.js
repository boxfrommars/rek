$(document).ready(function(){

    $('.image-upload').each(function(){
        var $input = $(this);
        if ($(this).val() !== '') {
            $('<img/>').attr('src', '/files/thumbnail/' + $(this).val()).insertBefore($input);
        }
        var $fileInput = $('<input/>').attr('type', 'file').attr('name', 'files[]').attr('data-url', "/vendor/jQuery-File-Upload/server/php/");
        $(this).before($fileInput);
//        <input id="fileupload" type="file" name="files[]" data-url="server/php/" />

        $fileInput.fileupload({
            dataType: 'json',
            done: function (e, data) {
                $.each(data.result.files, function (index, file) {
                    $('<img/>').attr('src', file.thumbnail_url).insertBefore($input);
                    $input.val(file.name);
                    console.log($input.val());
                });
            }
        });
    });
//
//    $('.image-upload').fileupload({
//        dataType: 'json',
//        done: function (e, data) {
//            $.each(data.result.files, function (index, file) {
//                $('<img/>').attr('src', file.thumbnail_url).appendTo(document.body);
//            });
//        }
//    });
});
