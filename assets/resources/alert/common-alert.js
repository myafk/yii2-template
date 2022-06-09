var alertToasts = function (type, body, params) {
    params = params ? params : {};
    params['class'] = type;
    params.body = body;
    params.title = params.title ? params.title : lajax.t('Оповещение');

    $(document).Toasts('create', params);
};

var alertToastsSuccess = function (body, params) {
    alertToasts('bg-success', body, params)
};

var alertToastsSuccessDelay = function (body, params) {
    params = params ? params : {};
    params.delay = 4000;
    params.autohide = true;
    alertToastsSuccess(body, params)
};

var alertToastsDanger = function (body, params) {
    alertToasts('bg-danger', body, params)
};

var alertToastsDangerDelay = function (body, params) {
    params = params ? params : {};
    params.delay = 4000;
    params.autohide = true;
    alertToastsDanger(body, params)
};