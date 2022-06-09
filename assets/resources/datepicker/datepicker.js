$(function () {

    let format = "DD.MM.YYYY";
    let formatTime = "DD.MM.YYYY hh:mm";

    // SINGLE DATE

    $('.dp-lte-date, .dp-lte-datetime').on('apply.daterangepicker', function (ev, picker) {
        if (!picker.startDate._isValid) {
            $(this).val('').trigger('change');
            return;
        }
        $(this).val(picker.startDate.format(format)).trigger('change');
    });

    $('.dp-lte-date').daterangepicker({
        autoUpdateInput: false,
        singleDatePicker: true,
        locale: {
            format: format
        },
    });

    $('.dp-lte-datetime').daterangepicker({
        autoUpdateInput: false,
        singleDatePicker: true,
        timePicker: true,
        timePickerIncrement: 5,
        timePicker24Hour: true,
        locale: {
            format: formatTime,
        },
    });

    // RANGE DATE

    let ranges = {};
    ranges[lajax.t('Сегодня')] = [moment(), moment()];
    ranges[lajax.t('Вчера')] = [moment().subtract(1, 'days'), moment().subtract(1, 'days')];
    ranges[lajax.t('Последние 7 дней')] = [moment().subtract(6, 'days'), moment()];
    ranges[lajax.t('Последние 30 дней')] = [moment().subtract(29, 'days'), moment()];
    ranges[lajax.t('Этот месяц')] = [moment().startOf('month'), moment().endOf('month')];
    ranges[lajax.t('Прошлый месяц')] = [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')];
    ranges[lajax.t('Очистить')] = ['', ''];

    $('.dpr-lte-date, .dpr-lte-datetime').on('apply.daterangepicker', function (ev, picker) {
        if (!picker.startDate._isValid) {
            $(this).val('').trigger('change');
            return;
        }
        $(this).val(picker.startDate.format(format) + ' - ' + picker.endDate.format(format)).trigger('change');
    });

    $('.dpr-lte-date').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: format
        },
        ranges: ranges,
    });

    $('.dpr-lte-datetime').daterangepicker({
        autoUpdateInput: false,
        timePicker: true,
        timePickerIncrement: 5,
        timePicker24Hour: true,
        locale: {
            format: formatTime
        }
    });

});