<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kinnect2 Shop: Order Status</title>
</head>
<body style="background:#ccc;">
<div style=" width:580px; margin: 0 auto; overflow:hidden; margin-top: 20px;">

    <div style="background:#505050; overflow:hidden; padding:20px 10px; border-radius:8px 8px 0px 0px; font-family: Arial, Gotham, 'Helvetica Neue', Helvetica, sans-serif; ">
        <div style="float:left;">
            <a href="https://www.kinnect2.com/" target="_blank">
                <img src="https://www.kinnect2.com/local/public/assets/images/kinnect2-logo.png" alt="logo" width="140" height="30">
            </a>
        </div>
        <div style="float:right; color:#ffffff; font-size:16px; font-weight:bold; padding-top:8px;">Invoice</div>
    </div>

    <div style="overflow:hidden;padding:20px 10px; border-radius: 0px 0px 8px 8px; background:#ffffff; font-family: Arial, Gotham, 'Helvetica Neue', Helvetica, sans-serif; font-size:14px;">
        <div style="float:left; overflow:hidden;">
            <div style="font-weight:bold; float:left; padding-right:5px; color:#505050;">Date:</div>
            <div style="float:left; color:#737373;"><?php echo $data['orderCreated_at']['date']; ?></div>
        </div>
        <div style="float:right; overflow:hidden;">
            <div style="overflow:hidden;">
                @if(isset($data['isSeller']))
                    @if($data['isSeller'] == 0)
                        <?php
                        $subjectHeading = "Seller";
                        $productOwner = getUserDetail($data['orderSellerId']);
                        $profileLink  = '<a target="_blank" href="'.profileAddress( $productOwner ).'">'.ucfirst($productOwner->displayname).'</a>';
                        ?>
                    @endif

                    @if($data['isSeller'] == 1)
                        <?php
                            $subjectHeading = "Buyer";
                            $productBuyer = getUserDetail($data['orderBuyerId']);
                            $profileLink  = '<a target="_blank" href="'.profileAddress( $productBuyer ).'">'.ucfirst($productBuyer->displayname).'</a>';
                        ?>
                    @endif
                    @else
                        <?php $subjectHeading = "Seller"; ?>
                        <?php                  $productOwner = getUserDetail($data['orderSellerId']);
                        $profileLink  = '<a target="_blank" href="'.profileAddress( $productOwner ).'">'.ucfirst($productOwner->displayname).'</a>';
                        ?>
                @endif

                <div style="font-weight:bold; float:left; padding-right:5px; color:#505050;">{{$subjectHeading}}:</div>
                <div style="float:left; color:#737373;">
                    {!! $profileLink !!}
                </div>
            </div>
            <div style="overflow:hidden; padding-top:10px;">
                <div style="font-weight:bold; float:left; padding-right:5px; color:#505050;">Order No:</div>
                <div style="float:left; color:#737373;">{{$data['orderOrderNumber']}}</div>
            </div>
        </div>
        <div style="line-height:0; clear:both; margin:0; padding:0;"></div>
        <div style="overflow:hidden;">
            <div style="font-weight:bold; padding-bottom:10px; color:#505050;">Shipping Information:</div>
            <div style="color:#737373; padding-bottom:5px;">{{$data['billingAddress']->first_name." ".$data['billingAddress']->last_name}}</div>
            <div style="color:#737373; padding-bottom:5px;">{{$data['billingAddress']->st_address_1}},</div>
            <div style="color:#737373; padding-bottom:5px;">{{$data['billingAddress']->st_address_2}}</div>
        </div>

        <div style="overflow:hidden; padding:10px; background:#f5f5f5; color:#909090; border-radius:8px; margin-top:30px;">
            <div style="float:left; padding:10px; width: 200px;">Product Details</div>
            <div style="float:left; padding:10px; width: 100px;">Price Per Unit</div>
            <div style="float:left; padding:10px; width: 90px;">Quantity</div>
            <div style="float:left; padding:10px; width: 70px;">Total</div>
        </div>
        @foreach($data['orderProductsInfo'] as $product)
        <?php $discountValue = ($product['productPrice'] * $product['productDiscount'])/100; ?>
        <div style="overflow:hidden; padding:10px; background:#f5f5f5; color:#505050; border-radius:8px; margin-top:10px;">
            <div style="float:left; padding:10px; width: 200px;">{{$product['productTitle']}}</div>
            <div style="float:left; padding:10px; width: 100px;">${{format_currency($product['productPrice'] - $discountValue)}}</div>
            <div style="float:left; padding:10px; width: 90px;">{{$product['productQuantity']}}</div>
            <div style="float:left; padding:10px; width: 70px;">${{format_currency(($product['productPrice'] * $product['productQuantity']) - ($discountValue * $product['productQuantity']))}}</div>
        </div>
        @endforeach
        <div style="float:right; padding-top:20px; text-align:right; padding-right:10px;">
            <div style="padding-bottom:5px; color:#757575;">Total: ${{format_currency($data['orderTotalPrice'] - $data['orderTotalShippingCost'])}}</div>
            <div style="padding-bottom:10px; color:#757575;">+ Shipping: ${{format_currency($data['totalShippingCost'])}}</div>
            <div style="color:#505050; font-weight:bold;">Grand Total: <span style="color:#1AC718;">${{format_currency($data['orderTotalPrice'])}}</span></div>
        </div>
        <div style="clear:both; height:0px; line-height:0;"></div>

    </div>

    <h1 style="font-size:18px; font-family: Arial, Gotham, 'Helvetica Neue', Helvetica, sans-serif; text-align:center;font-weight: normal; padding-top: 20px;">
        @if(isset($data['isSeller']))
            @if($data['isSeller'] == 0)
                Thank You For Your Purchase
            @endif
        @endif
    </h1>
</div>
</body>
</html>
