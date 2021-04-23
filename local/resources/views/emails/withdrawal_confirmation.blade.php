<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kinnect2: Withdrawal Request Status</title>
</head>
<body style="background:#ccc;">
<div style=" width:580px; margin: 0 auto; overflow:hidden; padding-top: 20px;">

    <div style="background:#505050; overflow:hidden; padding:20px 10px; border-radius:8px 8px 0px 0px; font-family: Arial, Gotham, 'Helvetica Neue', Helvetica, sans-serif; ">
        <div style="float:left;">
            <a href="<?php echo url('/') ?>" target="_blank">
                <img src="<?php echo $message->embed(url('/local/public/assets/images/kinnect2-logo.png')); ?>" alt="logo" width="140" height="30">
            </a>
        </div>
        <div style="float:right; color:#ffffff; font-size:16px; font-weight:bold; padding-top:8px;">Withdrawal Request Approved</div>
    </div>

    <div style="overflow:hidden;padding:20px 10px; border-radius: 0px 0px 8px 8px; background:#ffffff; font-family: Arial, Gotham, 'Helvetica Neue', Helvetica, sans-serif; font-size:14px;">
        <div style="float: left;">
            <b>Dear {{$data['seller']}},</b>
        </div>
        <div style="float: right;">
            <div style="font-weight:bold; float: left; padding-right:5px; color:#505050;">Date:</div>
            <div style="float:left; color:#737373;">{{date('m/d/Y')}}</div>
            <div style="clear: both;"></div>
        </div>
        <div style="clear: both;"></div>
        <div>
            <p>Your requested to withdraw <b>${{format_currency($data['amount'] + $data['fee_amount'])}}</b> on <b>{{date('m/d/Y',strtotime($data['created_at']))}}</b>. We are pleased to inform you that requested transaction has been approved and we have transferred your requested amount via requested method.</p>
            <p>Please find the details of your withdrawal below.</p>
        </div>
        <div style="overflow:hidden; padding:10px; background:#f5f5f5; color:#909090; border-radius:8px; margin-top:30px;">
            <div style="float:left; padding:10px; width: 170px;">Requested Amount:</div>
            <div style="float:left; padding:10px; width: 160px;">Fee Amount:</div>
            <div style="float:left; padding:10px; width: 150px;">Final Amount:</div>

        </div>
        <div style="overflow:hidden; padding:10px; background:#f5f5f5; color:#909090; border-radius:8px; margin-top:5px;">
            <div style="float:left; padding:10px; width: 170px;">${{format_currency($data['amount'] + $data['fee_amount'])}}</div>
            <div style="float:left; padding:10px; width: 160px;">${{format_currency($data['fee_amount'])}}</div>
            <div style="float:left; padding:10px; width: 150px;">${{format_currency($data['amount'])}}</div>
        </div>
        <div style="clear:both; height:0px; line-height:0;"></div>
    </div>

    <h1 style="font-size:18px; font-family: Arial, Gotham, 'Helvetica Neue', Helvetica, sans-serif; text-align:center;font-weight: normal; padding-top: 20px;">
        <?php echo date('Y') ?> &copy; <a href="{{url('/')}}">kinnect2.com</a>
    </h1>
</div>
</body>
</html>