<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kinnect2: Notification</title>
</head>

<body style="background:#ccc">
    <div style="font-family:arial;">
        <div style="background-color:#ffffff; border-radius:10px; padding:30px 50px; overflow:hidden;">
            <div style="margin-bottom:50px;">
                <img src="<?php echo $message->embed(url('local/public/images/logo-for-email.png')); ?>" width="60" height="58" alt="" />
            </div>

            <div style="overflow:hidden">
                <div style="float:left; margin-right:10px; width:50px; height:50px; overflow:hidden; border-radius:50%;">
                    <img src="<?php echo $message->embed($data['photo']); ?>" width="100%" height="auto" alt="" />
                </div>
                <div style="float:left; margin-top: 20px; font-size: 20px; color: #a09c9c;"><!--<a href="#" style="color:#ee4b08; text-decoration:none;">Martha Simpson</a>--> {!! $data['string']['string'] !!} <!--<a href="#" style="color:float: left; color: #ee4b08; text-decoration:none;">Link</a>--></div>
            </div>
            <div style="margin-top:50px;">
                <a href="{{url('goto/'.$data['string']['notification_id'].'?redirect-uri='.base64_encode($data['string']['url']))}}" style="padding: 20px; color: #fff; background-color: #ee4b08; border-radius: 10px; display: block; width: 140px; text-align: center; text-decoration: none;">View on Kinnect2</a>
            </div>
        </div>

        <!-- Address area -->
        <div style="margin:50px 0 0 50px;">
            &copy; {{date('Y')}} kinnect2 <!--- 572 7th street, San Francisco CA, 94103--> <br/><br/>
            <a href="http://blog.kinnect2.com/" style="color:#000;">Blog</a> | <a href="https://www.kinnect2.com/pages/help_center" style="color:#000;">Help center</a> | <a href="https://www.kinnect2.com/policy/condition" style="color:#000;">Privacy Policy</a> | <a href="https://www.kinnect2.com/policy/terms" style="color:#000;">Terms</a> <br/>
            <!--<div style="margin-top:40px;"><a href="#" style="color:#000;">Unsubscribe from this email</a></div>-->
        </div>
    </div>
</body>
</html>