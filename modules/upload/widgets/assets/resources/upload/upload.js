function initUpload(config) {

    $(config.file_id).fileupload({
        url: config.url_upload,
        dataType: 'json',
        formData: config.formData,
        maxNumberOfFiles: 1,
        autoUpload: true,

        add: function (e, data) {
            var uploadErrors = [];
            if (data.originalFiles[0].size && data.originalFiles[0].size > config.max_filesize) {
                uploadErrors.push('Размер файла превышает допустимый');
            }
            if (config.file_validate_type) {
                if (data.originalFiles[0].type && !data.originalFiles[0].type.match(config.file_regex_type)) {
                    uploadErrors.push('Недопустимый файл');
                }
            }
            if (uploadErrors.length > 0) {
                alert(uploadErrors.join('\\n'));
            } else {
                data.submit();
            }
        },

        done: function (e, data) {
            if (data.result.error) {
                alert(data.result.error);
                return;
            }
            var img = data.result.files;
            $(config.id).val(img.id).trigger('change');

            var img_src = img.type.match('image.*')
                ? (img.thumbnailUrl ? img.thumbnailUrl : img.url)
                : config.application_image;

            $(config.img_id).attr('src', img_src);
            $(config.img_id).show();
        },

        fail: function (e, data) {
            alert('Что то пошло не так...');
            console.log(data.jqXHR.responseText);
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
}