var removeFormButtonSpinner = function (form) {
    $(form).find('button').each(function() {
        $(this).find('i.fas').remove();
        $(this).attr('disabled', false);
    });
};

var addFormButtonSpinner = function (form) {
    $(form).find('button').each(function() {
        let spinner = $('<i class="fas fa-spinner fa-pulse"></i>');
        $(this).append(spinner);
        $(this).attr('disabled', true);
    });
};

var formErrorSummary = function (form, errorSummary) {
    if ($(form).find('.error-summary').length === 0) {
        return;
    }
    $(form).find('.error-summary')
        .html('')
        .append($(errorSummary))
        .show(300);
};