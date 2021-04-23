<?php
/**
 * Created by   :  Zahid Khurshid
 * Project Name : Kinnect2
 * Product Name : Store
 * Date         : 08-12-15 1:38 PM
 * File Name    : constants_brandstore.php
 */

return [
		'WORLDPAY_CLIENT_KEY' => 'T_C_538a50bd-60ef-4ae1-b6be-810a5193fab5',
		'WORLDPAY_SERVICE_KEY' => 'T_S_e1f83b95-f873-45e4-8185-5ea35bd5cde5',
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
				'ORDER_DISPUTE_RESOLVED'  => 10,//Only seller

		],
		'ORDER_STATUS_MESSAGE'  => [
				0 => 'ORDER CANCELED',//both (Buyer + Seller)
				1 => 'ORDER AWAITING PAYMENT',//only buyer

				2 => 'PAYMENT BEING VERIFIED',//not buyer nor seller, only kinnect2 admin
				3 => 'PAYMENT VERIFIED',//not buyer nor seller, only kinnect2 admin

				4 => 'ORDER AWAITING SHIPMENT',//Only seller
				5 => 'ORDER DISPATCHED',//Only seller
				6 => 'ORDER DELIVERED',//Only buyer
				7 => 'ORDER DISPUTED',//both (Buyer + Seller)
				8 => 'ORDER DISPUTE CANCELLED',//Only buyer
				9 => 'ORDER DISPUTE REJECTED',//Only seller
				10 => 'ORDER DISPUTE RESOLVED',//Only seller

		],
		'DISPUTE_STATUS'        => [
				'DISPUTE_CANCELLED_SELLER' => 1,// Cancelled by seller
				'DISPUTE_CANCELLED_BUYER'  => 2,// Cancelled by buyer
				'DISPUTE_ACCEPTED'         => 3,// For seller
				'CLAIMED_BY_SELLER'        => 4,// For seller
				'RESOLVED'                 => 5,// For seller
		],
		'DISPUTE_STATUS_STRING' => [
				1 => 'Dispute has been rejected by Seller',
				2 => 'Dispute has been cancelled by buyer',
				3 => 'Dispute has been accepted by seller',
				4 => 'Dispute has been claimed by buyer',
				5 => 'Dispute is resolved by Arbitrator',
		],

		'STATEMENT_TYPES' => [
				'SALE'         => 1,
				'WITHDRAW'     => 2,
				'WITHDRAW_FEE' => 3,
				'REVERSAL'     => 4,
				'ORDER_SHIPPING_FEE' => 5,
				'REVERSAL_FEE' => 6,
		],

		'STATEMENT_TYPES_STRING' => [
				1 => 'Sale',
				2 => 'Withdrawal',
				3 => 'Withdrawal Fee',
				4 => 'Sale Reversal',
				5 => 'Order Shipping Fee',
				6 => 'Reversal Fee'
		],
];
