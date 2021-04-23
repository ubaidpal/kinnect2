<?php
    /**
     * Created by   :  Zahid Khurshid
     * Project Name : Kinnect2
     * Product Name : Store
     * Date         : 08-12-15 1:38 PM
     * File Name    : constants_brandstore.php
     */

    return [
        'WORLDPAY_CLIENT_KEY'   => env('WORLDPAY_CLIENT_KEY','T_C_538a50bd-60ef-4ae1-b6be-810a5193fab5'),
        'WORLDPAY_SERVICE_KEY'  => env('WORLDPAY_SERVICE_KEY','T_S_e1f83b95-f873-45e4-8185-5ea35bd5cde5'),
        'CLAIM_FILE_FEE' => 50,
        'WITHDRAWAL_FEE_PERCENTAGE' => 6,
        'PAYMENT_GATEWAY' => [
            'PAYPAL' => 1,
            'WORLD_PAY' => 2
        ],
        'PAYMENT_GATEWAY_STATUS' => [
            1 => 'PAYPAL',
            2 => 'WORLD_PAY'
        ],
        'ORDER_STATUS'          => [
            'ORDER_CANCELED'         => 0,//both (Buyer + Seller)
            'ORDER_AWAITING_PAYMENT' => 1,//only buyer

            'ORDER_PAYMENT_BEING_VERIFIED' => 2,//not buyer nor seller, only kinnect2 admin
            'ORDER_PAYMENT_VERIFIED'       => 3,//not buyer nor seller, only kinnect2 admin

            'ORDER_AWAITING_SHIPMENT'  => 4,//Only seller
            'ORDER_DISPATCHED'         => 5,//Only seller
            'ORDER_DELIVERED'          => 6,//Only buyer
            'ORDER_DISPUTED'           => 7,//both (Buyer + Seller)
            'ORDER_DISPUTED_CANCELLED' => 8,//Only buyer
            'ORDER_DISPUTED_REJECTED'  => 9,//Only seller
            'ORDER_DISPUTE_ACCEPTED'   => 10,//Only seller
            'ORDER_DISPUTE_CLAIMED'   => 11,//Only seller
            'ORDER_DISPUTE_RESOLVED'   => 12,//Only seller

        ],
        'ORDER_STATUS_MESSAGE'  => [
            0 => 'ORDER CANCELED',//both (Buyer + Seller)
            1 => 'ORDER AWAITING PAYMENT',//only buyer

            2 => 'PAYMENT BEING VERIFIED',//not buyer nor seller, only kinnect2 admin
            3 => 'PAYMENT VERIFIED',//not buyer nor seller, only kinnect2 admin

            4  => 'ORDER AWAITING SHIPMENT',//Only seller
            5  => 'ORDER DISPATCHED',//Only seller
            6  => 'ORDER DELIVERED',//Only buyer
            7  => 'ORDER DISPUTED',//both (Buyer + Seller)
            8  => 'ORDER Refund CANCELLED',//Only buyer
            9  => 'ORDER Refund REJECTED',//Only seller
            10 => 'Order Refund is accepted',//Only seller

            11 => 'Refund request has been disputed by buyer',//Only seller
            12 => 'Refund request has been resolved by arbitrator',//Only seller

        ],
        'DISPUTE_STATUS'        => [
            'DISPUTE_CANCELLED_SELLER' => 1,// Cancelled by seller
            'DISPUTE_CANCELLED_BUYER'  => 2,// Cancelled by buyer
            'DISPUTE_ACCEPTED'         => 3,// For seller
            'CLAIMED_BY_BUYER'         => 4,// For seller
            'RESOLVED'                 => 5,// For seller
        ],
        'DISPUTE_STATUS_STRING' => [
            1 => 'Refund Request has been rejected by Seller',
            2 => 'Refund Request has been cancelled by buyer',
            3 => 'Refund Request has been accepted by seller',
            4 => 'Buyer opened a dispute',
            5 => 'Dispute is resolved by Arbitrator',
        ],

        'STATEMENT_TYPES' => [
            'SALE'               => 1,
            'WITHDRAW'           => 2,
            'WITHDRAW_FEE'       => 3,
            'REVERSAL'           => 4,
            'ORDER_SHIPPING_FEE' => 5,
            'REVERSAL_FEE'       => 6,
            'DISPUTE_PARTIAL_TRANSFER'  => 7
        ],

        'STATEMENT_TYPES_STRING' => [
            1 => 'Sale',
            2 => 'Withdrawal',
            3 => 'Withdrawal Fee',
            4 => 'Sale Reversal',
            5 => 'Order Shipping Fee',
            6 => 'Reversal Fee',
            7 => 'Dispute Partial Payment'
        ],

        'DISPUTE_NOTE' => [
            'BUYER' => 'Please wait for the supplier to respond to your refund request. You can modify the details of your refund request or cancel your refund request by clicking the button below. If you cannot reach an agreement with the seller, you can file a dispute for the order.',
            'BRAND' => 'If you cannot respond to this refund request then buyer can open a dispute. When dispute is open the KINNECT2 Arbitrator can solve the dispute.',

            'DISPUTE_ACCEPTED_SELLER' => 'The refund request has been accpeted and the amount has been transfered.',
            'DISPUTE_ACCEPTED_BUYER' => 'The refund request has been accpeted and the amount has been transfered.',
        ],
        'ORDER_CANCEL_REASONS' => [
            1 => 'Reason 1',
            2 => 'Reason 2',
        ],
        'ORDER_DISPUTE_REASONS' => [
            1 => 'Reason 1',
            2 => 'Reason 2'
        ]
    ];
