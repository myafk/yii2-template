function initAvatar(config) {

    bind_on_change_input_file(config.attribute, {
        select_ratio_width: 400,
        select_ratio_height: 400,
        width: 100,
        height: 100,
    });

    $(config.modal).on('hide.bs.modal', function () {
        for (var i = 1; i <= 4; i++)
            $('.imgareaselect-border' + i).hide();
        $('.imgareaselect-outer').hide();
    });

    $(config.modal).on('shown.bs.modal', function () {
        for (var i = 1; i <= 4; i++)
            $('.imgareaselect-border' + i).show();
        $('.imgareaselect-outer').show();
    });


    $('#modal-button-send').on('click', function () {
        var modal_box = $(config.modal);

        var fd = new FormData();

        $(modal_box).find('input[type="hidden"]').each(function () {
            var name = $(this).attr('name');
            fd.append(name, $(this).val());
        });
        fd.append('files', $(config.attribute).prop('files')[0]);

        $.ajax({
            url: config.url,
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            type: 'POST',
            success: function (data) {
                var img = data['files'];
                if (img.error) {
                    alert(img.error);
                    return;
                }
                $(config.id).val(img.id);
                if (img.thumbUrl) {
                    $(config.img_id).attr('src', img.thumbUrl);
                } else {
                    $(config.img_id).attr('src', img.url);
                }

                $(config.img_id).show();
                $(config.modal).modal('hide');

                if (config.success_callback) {
                    config.success_callback(img);
                }
            },
            fail: function (e, data) {
                alert('Что то пошло не так...');
                console.log(data.jqXHR.responseText);
            }
        });
        return false;
    });

}