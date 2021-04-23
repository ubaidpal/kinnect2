/**
 * Created by   :  Muhammad Yasir
 * Project Name : kinnect2
 * Product Name : PhpStorm
 * Date         : 14-Mar-2016 5:01 PM
 * File Name    :
 */
/*$(function () {
    $(".month").datepicker({
        dateFormat: 'mm-yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,

        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('yy-mm', new Date(year, month, 1)));
        }
    });

    $(".month").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });

    $(".date").datepicker({
        dateFormat: 'yy-mm-dd',

    });
});*/
$(function () {
    $(".date").datepicker({
        dateFormat: 'yy-mm-dd',

    });
    $(".start_date").datepicker({
        dateFormat: "yy-mm-dd",
        showOn: 'both',

        //minDate: 0,
        onClose: function (selectedDate) {
            $(".end_date").datepicker("option", "minDate", selectedDate);
        }
    });


    $(".end_date").datepicker({
        dateFormat: "yy-mm-dd",
        showOn: 'both',
        //buttonImage: '{{asset('local/public/assets/images/img-Start-Time.png')}}',
        //minDate: 0,
        onClose: function (selectedDate) {
            $(".start_date").datepicker("option", "maxDate", selectedDate);
        }
    });

});
