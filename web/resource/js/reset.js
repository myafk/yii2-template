$(function () {

    $('[data-toggle="tooltip"]').each(function () {
        let options = {};
        if ($(this).data('position')) {
            try {
                options.position = $(this).data('position');
            } catch (e) {
                console.log('Tooltip position fail', $(this).data('position'), e);
            }
        }
        $(this).tooltip(options);
    });

});