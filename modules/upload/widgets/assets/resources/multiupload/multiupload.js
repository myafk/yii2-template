"use strict";

function Multiupload(config, attachment_data) {
    this.config = config;
    this.attachment_data = attachment_data;
    this.filesCounter = attachment_data ? attachment_data.length : 0;

    for (var i in attachment_data) {
        this.drawImageRow(attachment_data[i]);
    }
    this.initMultiUpload();
}

Multiupload.prototype.drawImageRow = function (row) {
    var config = this.config;

    var image = '<a href="{{img_url}}" data-toggle="lightbox">' +
        '<img src="{{img_thumb}}" width="{{width_img}}" height="{{height_img}}" alt="">' +
        '</a>';

    image = image
        .replace('{{img_url}}', row.img_url)
        .replace('{{img_thumb}}', row.img_thumb ? row.img_thumb : row.img_url)
        .replace('{{width_img}}', config.width_img)
        .replace('{{height_img}}', config.height_img);

    var template =
        '<div class="row muw-row" data-id="{{id}}">' +
            '<div class="col-3">' +
                '<div class="muw-img">{{image}}</div>' +
            '</div>' +
            '<div class="col-9">' +
                '<div class="row">' +
                    '<div class="col-7">' +
                        '<input name="title" title="Название" data-toggle="tooltip" placeholder="Название" class="form-control" type="text" value="{{input_title}}">' +
                    '</div>' +
                    '<div class="col-4">' +
                        '<input name="sort" title="Порядок" data-toggle="tooltip" placeholder="Порядок" class="form-control" type="number" value="{{input_sort}}">' +
                    '</div>' +
                    '<div class="col-1">' +
                        '<span class="fas fa-save muw-save" data-toggle="tooltip" title="Сохранить" data-position=\'{"my":"left+30 bottom-3","at":"left+3 bottom-3"}\'></span>' +
                    '</div>' +
                '</div>' +
                '<div class="row muw-description">' +
                    '<div class="col-11">' +
                        '<textarea name="description" title="Описание" data-toggle="tooltip" placeholder="Описание" class="form-control">' +
                            '{{input_description}}' +
                        '</textarea>' +
                    '</div>' +
                    '<div class="col-1">' +
                        '<span class="fas fa-trash muw-delete" data-toggle="tooltip" title="Удалить" data-position=\'{"my":"left+30 bottom-3","at":"left+10 bottom-3"}\'></span>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';
    template = template
        .replace('{{id}}', row.id)
        .replace('{{image}}', image)
        .replace('{{input_title}}', row.input_title ? row.input_title : '')
        .replace('{{input_description}}', row.input_description ? row.input_description : '')
        .replace('{{input_sort}}', row.input_sort ? row.input_sort : '');

    $(config.img_id).append($(template));

    this.initButtons();
}

Multiupload.prototype.initButtons = function () {
    var config = this.config,
        selfObject = this;

    $('.muw-delete').off('click').on('click', function () {
        var self = $(this);
        var id = $(self).parents('.muw-row').data('id');

        $.ajax({
            type: 'POST',
            url: config.delete_url,
            dataType: 'json',
            data: {id: id},
            success: function (data) {
                if (data.status) {
                    $(self).parents('.muw-row').hide(400, function () {
                        $(this).remove();
                    });
                    var value = $(config.id).val();
                    if (value) {
                        value = value.split(',');
                    } else {
                        value = [];
                    }
                    var result = [];
                    for (var i = 0; i < value.length; i++) {
                        if (value[i] != id) {
                            result.push(value[i]);
                        }
                    }
                    $(config.id).val(result.join(','));
                    selfObject.filesCounter -= 1;
                }
            },
            error: function () {
                alertToastsDanger(lajax.t('Ошибка'));
            }
        })
    });
    $('.muw-save').off('click').on('click', function () {
        var self = $(this);
        var id = $(self).parents('.muw-row').data('id');

        var data = {id: id};
        $(self).parents('.muw-row').find('input,textarea').each(function () {
            var key = $(this).attr('name'),
                value = $(this).val();
            data[key] = value;
        });

        $.ajax({
            type: 'POST',
            url: config.update_url,
            dataType: 'json',
            data: data,
            success: function (data) {
                if (data.status) {
                    alertToastsSuccessDelay(lajax.t('Сохранено'));
                }
            },
            error: function () {
                alertToastsDanger(lajax.t('Ошибка'));
            }
        })
    });

    resetTooltip();
}

Multiupload.prototype.initMultiUpload = function () {
    var config = this.config,
        selfObject = this;

    $(config.file_id).fileupload({
        url: config.url_upload,
        dataType: 'json',
        formData: config.formData,
        maxNumberOfFiles: 1,
        autoUpload: true,
        limitConcurrentUploads: 3,

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

        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 90, 10);
            $(config.progress_bar_id + ' .progress-bar').css('width', progress + '%');
        },

        send: function (e, data) {
            $(config.progress_bar_id + ' .progress-bar').css('width', '0%');
        },

        done: function (e, data) {
            if (data.result.error) {
                alert(data.result.error);
                return;
            }

            var img = data.result.files;
            var input = $(config.id);
            var img_ids = input.val().split(',');
            if (img_ids[0] == '') {
                img_ids.splice(0, 1);
            }
            img_ids.push(JSON.stringify(img.id));
            input.val(img_ids.join(',')).trigger('change');

            selfObject.drawImageRow({
                id: img.id,
                img_url: img.url,
                img_thumb: img.thumbnailUrl
            });
            $(config.progress_bar_id + ' .progress-bar').css('width', '100%');
            setTimeout(function() {
                $(config.progress_bar_id + ' .progress-bar').css('width', '0');
            }, 5000)
        },

        fail: function (e, data) {
            if (data.textStatus != 'canceled') {
                alert('Что то не так...');
                console.log(data.jqXHR.responseText);
            }
        },

        beforeSend: function (event, data) {
            if (selfObject.filesCounter >= config.maxFiles) {
                alert('Достигнут лимит загружаемых файлов для заданого объекта');
                return false;
            }

            selfObject.filesCounter += 1;
        }
    })
        .prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
}