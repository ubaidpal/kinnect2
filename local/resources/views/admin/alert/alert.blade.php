@if (Session::has('info'))
    <div id="display-success">
        <img id="img"  alt="Success" /> {{Session::get('info')}}
    </div>
@endif
@if (Session::has('error'))
    <div id="display-error">
        <strong>Oh snap! </strong> {{Session::get('error')}}
    </div>
@endif
<style>

    #img
    {
        float: left;
        background-image: url("{!! asset('local/public/assets/images/success.png') !!}");
        width: 14px;
        height: 15px;
		text-indent:-9999px;
		overflow:hidden;
		margin-right:10px;    }

    #display-success
    {
        border: 1px solid #D8D8D8;
        padding: 10px;
        border-radius: 5px;
        font-size: 12px;
       /* text-transform: uppercase;*/
        background-color: rgb(236, 255, 216);
        color: green;
        text-align: left;
        margin-top: 30px;
		overflow:hidden;
		line-height:normal;
    }
    #display-error
    {
        padding: 10px;
        border-radius: 5px;
        font-size: 12px;
        /* text-transform: uppercase;*/
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebcccc;
        text-align: left;
        margin-top: 30px;
        overflow:hidden;
        line-height:normal;
    }
</style>
<script type="text/javascript">
    $(function() {

        setTimeout(function() {
           $('#display-success').fadeOut('slow');
        }, 3000);
        setTimeout(function() {
            $('#display-error').fadeOut('slow');
        }, 3000);
    });
</script>
