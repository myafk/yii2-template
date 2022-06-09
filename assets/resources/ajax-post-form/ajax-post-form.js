$(function () {

    $('.ajax-post-form').on('beforeSubmit', function () {
        let form = $(this);

        if ($(form).find('.error-summary').length) {
            $(form).find('.error-summary').hide(300);
        }
        addFormButtonSpinner(form);

        $.ajax({
            url: $(form).attr('action'),
            method: 'POST',
            data: $(form).serialize(),
            dataType: 'JSON',
            success: function (data) {
                if (data.status) {
                    let message = data.message ? data.message : lajax.t('Успешно сохранено');
                    alertToastsSuccess(message);
                } else {
                    let message = data.error ? data.error : lajax.t('Ошибка сохранения');
                    alertToastsDanger(lajax.t(message));

                    if (data.errorSummary) {
                        formErrorSummary(form, data.errorSummary);
                    }
                }
                removeFormButtonSpinner(form);
            },
            error: function () {
                alertToastsDanger(lajax.t('Системная ошибка. Попробуйте снова или обратитесь к администратору.'));
                removeFormButtonSpinner(form);
            },
        });

        return false;
    });

});