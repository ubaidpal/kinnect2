<script src="{{ asset('/local/public/js/mvc/mrb/public/js/libs/socket-io.min.js') }}" ></script>
<script src="{{ asset('/local/public/js/mvc/mrb/public/js/libs/moment.min.js') }}" ></script>
<script src="{{ asset('/local/public/js/mvc/mrb/public/js/libs/plugins/chat-plugin.js') }}" ></script>
<script type="text/javascript">
    util = {
         getFormattedTime:function(timeStr){
            var timezoneHoursDifference = -(new Date().getTimezoneOffset()/60);
            if(moment().diff(moment(timeStr), "hours") > 23){
                return moment.utc(timeStr).add(timezoneHoursDifference, "hours").format("LLL");
            }else{
                return moment(timeStr).add(timezoneHoursDifference, "hours").fromNow()
            }
        }

    };
    $.fn.initiChat(io, util);
</script>