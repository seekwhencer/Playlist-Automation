$(document).ready(function() {

    Schedule.resizeTable();

    $('.button-add-schedule').on('click', function(e) {
        e.preventDefault();
        $('#modal-add-schedule').toggle();
    });

    Schedule.addBehavior();
    Schedule.addTableBehavior();

});

$(window).resize(function() {
    Schedule.resizeTable();
});

var Schedule = {

    resizeTable : function() {
        var height = $(window).innerHeight() - $('.page-header').outerHeight();
        $('#ScheduleTable').height(height);

        var colHeight = height - $('.schedule-header-row').eq(0).height();
        $('.schedule-day-col').height(colHeight);

        $('.schedule-item').each(function() {
            var p = parseInt($(this).attr('data-percent')) / 120;
            var ih = colHeight * p;
            $(this).css({
                'top' : ih + 'px'
            });
        });
    },

    addBehavior : function() {
        //
        $('input[name="scheduleForm[hour]"]').on('keyup', function() {
            $(this).val(parseInt($(this).val()));
            if ($(this).val() > 23)
                $(this).val('23');

            if ($(this).val() < 0 || $(this).val() == 'NaN')
                $(this).val('00');
        });

        //
        $('input[name="scheduleForm[minute]"]').on('keyup', function() {
            $(this).val(parseInt($(this).val()));
            if ($(this).val() > 59)
                $(this).val('59');

            if ($(this).val() < 0 || $(this).val() == 'NaN')
                $(this).val('00');
        });

        //
        $('#button-save-schedule').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                'method' : 'POST',
                'url' : HOME_URL + 'admin/schedule/save',
                'data' : $('form').serialize()
            }).done(function(e) {
                Schedule.View.flushForm();
                Schedule.getTable();
                $('#button-abort-schedule').trigger('click');
            });
        });

        //
        $('#button-abort-schedule').on('click', function(e) {
            e.preventDefault();
            $('#modal-add-schedule').toggle();
        });
    },
    
    addTableBehavior : function(){
        //
        $('.schedule-item a.schedule-title').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                'url' : HOME_URL + 'admin/schedule/edit'
            }).done(function(e) {

            });
        });

        $('.schedule-item a.schedule-delete').on('click', function(e) {
            e.preventDefault();

            var item = $(this).closest('.schedule-item');

            var deleteData = {
                'scheduleDelete' : {
                    's' : $(item).attr('data-show'),
                    'wd' : $(item).attr('data-weekday'),
                    'h' : $(item).attr('data-hour'),
                    'm' : $(item).attr('data-minute'),
                }
            };

            $.ajax({
                'method' : 'POST',
                'data' : deleteData,
                'url' : HOME_URL + 'admin/schedule/delete'
            }).done(function(e) {
                Schedule.getTable();
            });
        });
    },

    getTable : function() {
        $.ajax({
            'url' : HOME_URL + 'admin/schedule/gettable'
        }).done(function(e) {
            $('#ScheduleTable').html(e);
            Schedule.resizeTable();
            Schedule.addTableBehavior();
        });
    },

    /**
     *
     *
     */
    View : {
        flushForm : function() {
            $('input[name="scheduleForm[hour]"]').val('');
            $('input[name="scheduleForm[minute]"]').val('00');
            $('form select').val('');
            $('input[type="checkbox"]').each(function() {
                $(this).attr('checked', false);
            });
        },
    },

};
