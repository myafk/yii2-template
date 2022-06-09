var reloadButtons = function () {
    $('.ajax-grid-modal').off('click').on('click', function (e) {
        e.preventDefault();
        var self = $(this);
        var gridDiv = $(this).data('grid');
        $.ajax({
            url: $(self).attr('href'),
            method: 'GET',
            dataType: 'JSON',
            success: function (data) {
                if (data.status) {
                    $(gridDiv).after(data.html);
                    $('a.btn.ajax-grid-modal[data-grid="' + gridDiv + '"]').hide(100);
                } else {
                    let message = data.error ? data.error : lajax.t('Ошибка загрузки таблицы');
                    alertToastsDanger(message);
                }
            },
            error: function () {
                alertToastsDanger(lajax.t('Системная ошибка. Попробуйте снова или обратитесь к администратору.'));
            }
        });
        return false;
    });
};

var reloadsGrid = function (callback) {
    var $gridsDiv = $('.ajax-grid');
    callback = callback ? callback : function () {};

    if ($gridsDiv.length) {
        $gridsDiv.each(function () {
            $(this).on('reload-grid', function (event, callback) {
                var gridDiv = $(this);
                callback = callback ? callback : function () {};
                $.ajax({
                    url: $(gridDiv).data('url'),
                    method: 'GET',
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.status) {
                            $(gridDiv).html('').html(data.html);
                            reInitModal();
                            reloadButtons();
                            callback();
                        } else {
                            let message = data.error ? data.error : lajax.t('Ошибка загрузки таблицы');
                            alertToastsDanger(message);
                        }
                    },
                    error: function () {
                        alertToastsDanger(lajax.t('Системная ошибка. Попробуйте снова или обратитесь к администратору.'));
                    }
                });
            })
        });

        $gridsDiv.each(function () {
            $(this).trigger('reload-grid', [callback]);
        });
    }

};
reloadsGrid(reloadButtons);