var reInitModal = function () {
    $('[data-toggle="modal"], a[data-page]').off('click').on('click', function () {
        if ($(this).closest('.ajax-modal-asset-fix').length > 0) {
            return true;
        }
        var url = $(this).attr('href');
        if ($(this).data('add-param')) {
            url += '&active=' + $(this).data('add-param');
        }
        $.ajax({
            url: url,
            dataType: 'JSON',
            type: 'GET'
        }).done(function (result) {
            if (result.status) {
                if (result.html) {
                    $('.modal-ajax').html(result.html).modal('show');
                    $('.modal-ajax').find('.panel').length && $('.modal-ajax').find('.panel').css("visibility", "visible");
                } else if (result.notify) {
                    alertToastsSuccess(result.notify);
                } else {
                    alertToastsSuccess(lajax.t('Операция прошла успешно'));
                }
                reInitModal();
            } else if (result.error) {
                alertToastsDanger(lajax.t('Ошибка.') + ' ' + result['error']);
            } else {
                alertToastsDanger(lajax.t('Ошибка сервера'));
            }
        });

        return false;
    });
};
$(document).on('pjax:end', reInitModal);
reInitModal();