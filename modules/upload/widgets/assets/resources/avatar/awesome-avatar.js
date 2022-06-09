function bind_on_change_input_file(selector, config) {

    $(selector).change(function () {
        if ($(this).val() == '') {
            return;
        }

        // if selection, remove
        var $last_img = $(selector + '-select-area img');
        if ($last_img.length) {
            var img_obj = $last_img.imgAreaSelect({instance: true});
            img_obj.remove();
        }

        $(selector + '-select-area').empty();
        $(selector + '-preview img').hide();

        var p = new RegExp(/\.(jpg|jpeg|png|gif)$/);
        var filename = $(this).val().toLowerCase().replace(/^\s+|\s+$/g, '');
        if (!p.test(filename)) {
            alert('{% trans "Неверный формат. Выберите изображение" %}');
            return;
        }

        var file = this.files[0];
        if (!file.type.match(/image.*/)) {
            return;
        }
        var reader = new FileReader();
        reader.onload = bind_preview.bind(null, selector, config);
        reader.readAsDataURL(file);
    });
}

function bind_preview(selector, config, e) {
    var image_data = e.target.result;
    var image = new Image();
    image.src = image_data;

    $(selector + '-preview img').show();
    $(selector + '-preview img').css('max-width', 'none');
    $(selector + '-preview img').attr('src', image_data);

    $(selector + '-select-area').empty();
    $(selector + '-select-area').append('<img />');

    $(selector + '-select-area img').attr('src', image_data);
    $(selector + '-select-area img').on('load', function () {
        $(this).unbind('load');

        var img_width = image.width;
        var img_height = image.height;

        var ratio_x = img_width / config.select_ratio_width;
        var ratio_y = img_height / config.select_ratio_height;

        if (ratio_x > 1 || ratio_y > 1) {
            if (ratio_x > ratio_y) {
                $(this).css('width', config.select_ratio_width + 'px');
                $(selector + '-ratio').val(image.width / config.select_ratio_width);
            } else {
                $(this).css('height', config.select_ratio_height + 'px');
                $(selector + '-ratio').val(image.height / config.select_ratio_height);
            }
        } else {
            $(selector + '-ratio').val(1);
        }

        $(selector + '-width').val(ratio_x);
        $(selector + '-height').val(ratio_y);

        var sel = {};
        sel['x1'] = Math.round(img_width / 2 - 25 > 0 ? img_width / 2 - 25 : 0);
        sel['y1'] = Math.round(img_height / 2 - 25 > 0 ? img_height / 2 - 25 : 0);
        sel['x2'] = Math.round(img_width / 2 + 25 > img_width ? img_width : img_width / 2 + 25);
        sel['y2'] = Math.round(img_height / 2 + 25 > img_height ? img_height : img_height / 2 + 25);
        sel['width'] = 50;

        $(this).imgAreaSelect({
            aspectRatio: config.select_ratio_width + ":" + config.select_ratio_height,
            fadeSpeed: 100,
            x1: sel.x1,
            y1: sel.y1,
            x2: sel.x2,
            y2: sel.y2,
            onSelectChange: update_coors.bind(null, selector, config)
        });

        update_coors(selector, config, {'width': img_width}, sel);
    });
}

function update_coors(selector, config, img, selection) {
    $(selector + "-x1").val(selection.x1);
    $(selector + "-y1").val(selection.y1);
    $(selector + "-x2").val(selection.x2);
    $(selector + "-y2").val(selection.y2);

    if (parseInt(selection.width) > 0) {
        var ratiox = config.width / (selection.width || 1);
        var ratioy = config.height / (selection.height || 1);

        $(selector + '-preview img').css({
            width: Math.round(ratiox * img.width) + 'px',
            height: Math.round(ratioy * img.height) + 'px',
            marginLeft: '-' + Math.round(ratiox * selection.x1) + 'px',
            marginTop: '-' + Math.round(ratiox * selection.y1) + 'px'
        });
    }
}
