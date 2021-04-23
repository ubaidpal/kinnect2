<style>

    #img
    {
        float: left;
        background-image: url("{!! asset('local/public/assets/images/del-btn.png') !!}");
        width: 14px;
        height: 15px;
    }

    #display-success
    {
        width: 95%;
        border: 1px solid #D8D8D8;
        padding: 15px;
        border-radius: 5px;
        font-size: 12px;
        /* text-transform: uppercase;*/
        background-color: rgb(236, 255, 216);
        color: green;
        text-align: center;
        margin-top: 30px;
    }

    #display-success img
    {
        position: relative;
        bottom: 8px;
    }
</style>
@if (Session::has('info'))
    <div id="display-success">
        <img id="img"  alt="Success" /> {{Session::get('info')}}
    </div>
@endif
<a href="{{url('store/my_orders/')}}">My Orders</a>
