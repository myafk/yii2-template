(function () {
    function BirthdayPicker(element, options) {
        var base = this;

        this.element = $(element);
        this.options = $.extend({}, BirthdayPicker.Defaults, options);

        this.element.hide();

        this.wrap = $('<div />');
        this.wrap.insertAfter(this.element)
            .addClass('birthdaypicker');

        this.daySelect = $('<select />')
            .addClass(this.options.inputClass + ' birthdaypicker-select birthdaypicker-day')
            .attr('id', this.element.attr('id')+'-day');
        if (this.options.daySelectWidth) {
            this.daySelect.css('width', this.options.daySelectWidth);
        }

        this.element.parent().find('label').attr('for', this.daySelect.attr('id'));

        this.monthSelect = $('<select />')
            .addClass(this.options.inputClass + ' birthdaypicker-select birthdaypicker-month');
        if (this.options.monthSelectWidth) {
            this.monthSelect.css('width', this.options.monthSelectWidth);
        }

        this.yearSelect = $('<select />')
            .addClass(this.options.inputClass + ' birthdaypicker-select birthdaypicker-year')
            .css('width', 80);

        this.ageLabel = $('<button  />')
            .attr('type', 'button')
            .attr('class', 'btn btn-default')
            .attr('disabled', 'disabled')
            .addClass('birthdaypicker-age-label')
            .html('');

        this.ageInput = $('<input  />')
            .attr('type', 'number')
            .attr('class', 'birthdaypicker-select birthdaypicker-age')
            .attr('id', this.element.attr('id')+'-age');
        if (this.options.ageAttributeId) {
            this.ageInput = $('#' + this.options.ageAttributeId)
                .addClass('birthdaypicker-select')
                .attr('type', 'number')
        }

        this.clearLabel = $('<span  />')
            .attr('class', 'glyphicon glyphicon-remove clickable')
            .html('');

        var templateParts = this.options.template.split(',');

        var orderClass = {
            0: 'birthdaypicker-select-first',
            1: 'birthdaypicker-select-second',
            2: 'birthdaypicker-select-third'
        };

        for (i = 0; i < templateParts.length ; i++) {
            if (templateParts[i] == 'day') {
                this.wrap.append(this.daySelect);
                this.daySelect.addClass(orderClass[i]);
            } else if(templateParts[i] == 'month') {
                this.wrap.append(this.monthSelect);
                this.monthSelect.addClass(orderClass[i]);
            } else if(templateParts[i] == 'year') {
                this.wrap.append(this.yearSelect);
                this.yearSelect.addClass(orderClass[i]);
            } else if(templateParts[i] == 'label') {
                this.wrap.append(this.ageLabel);
            } else if(templateParts[i] == 'clear') {
                this.wrap.append(this.clearLabel);
            } else if(templateParts[i] == 'age') {
                this.wrap.append(this.ageInput);
            }
        }

        $('.birthdaypicker-select', this.wrap).bind('change.birthdaypicker', function() {
            if(options['updateAny'] || (base.daySelect.val() != '' && base.monthSelect.val() != '' && base.yearSelect.val() != '')) {
                base.element.val(base.yearSelect.val() + '-' + base.monthSelect.val() + '-' + base.daySelect.val())
                    .trigger('change');
            } else {
                base.element.val('');
            }
        });

        $('.birthdaypicker-select', this.wrap).bind('focusout.birthdaypicker', function() {
            setTimeout(function() {
                var focused = $(document.activeElement)[0];

                if(focused != base.daySelect[0] && focused != base.monthSelect[0] && focused != base.yearSelect[0]) {
                    base.element.trigger('blur');
                }
            }, 100);
        });

        var currentYear = new Date().getFullYear();

        this.daySelect.append($('<option />').val('').html('День'));
        for (i = 1; i <= 31 ; i++) {
            var dayVal = i > 9 ? i : '0'+i;
            this.daySelect.append($('<option />').val(dayVal).html(i));
        }

        this.monthSelect.append($('<option />').val('').html('Месяц'));
        for (i = 1; i < 13; i++) {
            var monthVal = i > 9 ? i : '0'+i;
            this.monthSelect.append($('<option />').val(monthVal).html(this.options.monthNames[i-1]));
        }

        this.yearSelect.append($('<option />').val('').html('Год'));
        for (i = currentYear; i >= currentYear - 100; i--) {
            this.yearSelect.append($('<option />').val(i).html(i));
        }

        var val = this.element.val();
        if(val != '') {
            var dateParts = val.split('-', 3);

            if (this.options.ageAttributeId) {
                this.yearSelect.val((new Date()).getFullYear() - (this.ageInput.val() || 0));
            } else {
                this.yearSelect.val(dateParts[0]);
            }
            this.monthSelect.val(dateParts[1]);
            this.daySelect.val(dateParts[2]);
        } else {
            if (this.options.ageAttributeId) {
                this.yearSelect.val((new Date()).getFullYear() - (this.ageInput.val() || 0) );
            }
        }

        this.updateNumberOfDays();
        this.updateAgeLabel();

        this.ageInput.change(function () {
            base.yearSelect.val((new Date()).getFullYear() - base.ageInput.val()).trigger('change');
        });

        this.yearSelect.change(function () {
            base.updateNumberOfDays();
        });

        this.monthSelect.change(function () {
            base.updateNumberOfDays();
        });

        this.daySelect.change(function () {
            base.updateAgeLabel();
        });

        this.clearLabel.click(function () {
            base.element.val('').trigger('change');
        });
    }

    BirthdayPicker.prototype.updateNumberOfDays = function() {
        var options = this.daySelect.find('option');

        options.prop( "disabled", true);

        month = this.monthSelect.val();
        year = this.yearSelect.val();
        days = this.daysInMonth(month, year);

        for (i = 0; i < days + 1 ; i++) {
            options.eq(i).prop("disabled", false);
        }

        if(this.daySelect.find(":selected").attr('disabled')) {
            this.daySelect.val('');
            this.daySelect.trigger('change');
        } else {
            this.daySelect.trigger('change');
        }
    };

    BirthdayPicker.prototype.updateAgeLabel = function() {
        var days = this.daySelect.val(),
            month = this.monthSelect.val(),
            year = this.yearSelect.val();
        if(!year || !month || !days) {
            this.ageLabel.html('');
            return;
        }
        var birthDay = new Date(year + '-' + month + '-' + days),
            today = new Date();

        var age = today.getFullYear() - birthDay.getFullYear();
        var m = today.getMonth() - birthDay.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDay.getDate())) {
            age--;
        }
        this.ageLabel.html(age);
    };

    BirthdayPicker.prototype.daysInMonth = function(month, year) {
        return new Date(year, month, 0).getDate();
    };

    BirthdayPicker.Defaults = {
        'inputClass': 'form-control',
        'template': 'month,day,year',
        'ageAttributeId': false,
        'monthNames': ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
    };

    $.fn.birthdayPicker = function (option) {
        return this.each(function () {
            var $this = $(this),
                data = $this.data('birthdayPicker');

            if (!data) {
                data = new BirthdayPicker(this, typeof option == 'object' && option);
                $this.data('birthdayPicker', data);
            } else if(option == 'refresh') {
                var dateParts = $this.val().split('-', 3);
                data.yearSelect.val(dateParts[0]);
                data.monthSelect.val(dateParts[1]);
                data.daySelect.val(dateParts[2]);
                data.yearSelect.trigger('change');
            }
        });
    };
})(window.jQuery);
