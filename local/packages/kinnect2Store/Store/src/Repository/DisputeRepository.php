<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : kinnect2
 * Product Name : PhpStorm
 * Date         : 24-Feb-2016 3:43 PM
 * File Name    : DisputeRepository.php
 */

namespace kinnect2Store\Store\Repository;

use App\Album;
use App\Conversation;
use App\Country;
use App\Repository\Eloquent\MessageRepository;
use App\Repository\Eloquent\Repository;
use kinnect2Store\Store\DeliveryCourier;
use kinnect2Store\Store\Repository\admin\StoreAdminRepository;
use kinnect2Store\Store\StoreAlbums;
use kinnect2Store\Store\StoreBankAccount;
use kinnect2Store\Store\StoreClaim;
use kinnect2Store\Store\StoreDispute;
use kinnect2Store\Store\StoreDisputeProduct;
use kinnect2Store\Store\StoreOrder;
use kinnect2Store\Store\StoreOrderItems;
use kinnect2Store\Store\StoreProduct;
use kinnect2Store\Store\traits\StoreFile;
use Symfony\Component\HttpKernel\HttpCache\Store;
use kinnect2Store\Store\StoreOrderTransaction;
use App\Classes\Worldpay;
use App\Classes\WorldpayException;

class DisputeRepository extends Repository
{

    use StoreFile;

    /**
     * DisputeRepository constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    public function addDisputeRecord($request, $user_id) {
        $order_dispute = $this->store_dispute($request, $user_id);
        if(isset($order_dispute['order_dispute']->id)) {
            if($request->has('input_image_id')) {
                $album_id = $order_dispute['album_id'];

                //return $fileIds = explode(",", $request->myImageIds);
                foreach ($request->input_image_id as $fileId) {
                    $options = [
                        'owner_id' => $order_dispute['order_dispute']->id,
                        'title'    => 'Order Dispute',
                        'user_id'  => $order_dispute['order_dispute']->owner_id,
                    ];
                    $this->store_file($album_id, $fileId, $user_id, $options);
                }
            }

            $this->update_order_status($request->order_id, \Config::get('constants_brandstore.ORDER_STATUS.ORDER_DISPUTED'));
            $this->addRequestedProducts($order_dispute['order_dispute']->id, $request->products);
            return $order_dispute['order_dispute']->reference_id;
        }

        return 0;
    }

    private function store_dispute($request, $user_id) {

        $dispute = $this->orderHasDispute($request->order_id);

        if(!$dispute) {
            $store       = StoreOrder::find($request->order_id);
            $seller_id   = $store->seller_id;
            $messageRepo = new MessageRepository();
            $conv        = $messageRepo->createConversation([$user_id, $seller_id], $user_id, 'group');

            $conv_id        = $conv['convId'];
            $conv           = Conversation::find($conv_id);
            $conv->conv_for = 'dispute';
            $conv->save();

            $order_dispute          = new StoreDispute();
            $order_dispute->conv_id = $conv_id;
        } else {
            $order_dispute = StoreDispute::find($dispute->reference_id);
        }

        $order_dispute->is_received    = $request->order_receive;
        $order_dispute->claim_request  = $request->refund;
        $order_dispute->claimed_amount = $request->claimed_amount != '' ? $request->claimed_amount : '';
        $order_dispute->reason         = $request->reason;
        $order_dispute->detail         = $request->detail;
        $order_dispute->order_id       = $request->order_id;//reference order id to be changed
        $order_dispute->owner_id       = $user_id;

        $order_dispute->save();
        $album_id = NULL;
        if($request->has('input_image_id')) {
            if(!$dispute) {
                $album = $this->get_album('order_dispute', $order_dispute->id);
                if(!$album) {
                    $album_id = $this->create_album($order_dispute->id);
                } else {
                    $album_id = $album->album_id;
                }

            } else {
                $album_id = $this->get_album('order_dispute', $order_dispute->id);
                if($album_id) {
                    $album_id = $album_id->album_id;
                } else {
                    $album_id = $this->create_album($order_dispute->id);
                }
            }
        }

        return ['order_dispute' => $order_dispute, 'album_id' => $album_id];
    }

    private function orderHasDispute($ordId) {
        $dispute = StoreDispute::where('order_id', $ordId)->first();
        if(!empty($dispute)) {
            return $dispute;
        } else {
            return FALSE;
        }
    }

    public function get_album($owner_type, $id) {
        return StoreAlbums::where('owner_type', $owner_type)->where('owner_id', $id)->first();
    }

    public function get_dispute($id) {
        $data['dispute'] = StoreDispute::where('reference_id', $id)->with('album.albumPhoto.storageFile')->first();

        if(isset($data['dispute']->album)) {
            $data['files'] = $data['dispute']->album->albumPhoto;
        } else {
            $data['files'] = '';
        }

        $data['shipping_info'] = $this->shipping_info($data['dispute']->order_id);
        $data['countries']     = Country::orderBy('name', 'ASC')->lists('name', 'id');

        return $data;

    }

    public function has_dispute($id) {
        $data = StoreDispute::where('order_id', $id)->first();

        if(!empty($data)) {
            return $data->reference_id;
        } else {
            return FALSE;
        }
    }

    public function accept_dispute($id, $status, $order_status = NULL, $conv_status = 0, $user_id = NULL) {
        $dispute = StoreDispute::find($id);

        $is_order_owner = $this->isSellerOrder($dispute->order_id, $user_id);

        if($is_order_owner) {
            $worldpay    = new Worldpay(\Config::get('constants_brandstore.WORLDPAY_SERVICE_KEY'));
            $transaction = StoreOrderTransaction::where('order_id', $dispute->order_id)
                                                ->select(['amount', 'total_amount', 'state', 'gateway_transaction_id'])
                                                ->first();
            $order_code  = $transaction->gateway_transaction_id;

            try {
                if($dispute->claim_request == 'full') {
                    $worldpay->refundOrder($order_code);
                    $amount = $transaction->amount;
                } else {
                    $refund_amount = $amount = $dispute->claimed_amount;
                    if($dispute->claimed_amount > $transaction->amount) {
                        return ['message_text' => 'Claimed amount is higher then the order amount', 'status' => 'error'];
                    }
                    $worldpay->refundOrder($order_code, $refund_amount * 100);

                    if($amount < $transaction->amount) {
                        $seller_amount = $transaction->amount - $amount;
                        $sarObj        = new StoreAdminRepository();
                        $type          = \Config::get('constants_brandstore.STATEMENT_TYPES.DISPUTE_PARTIAL_TRANSFER');
                        $sarObj->updateStatement($type, 'store_dispute', $dispute->id, 'credit', 'USD', $user_id, $seller_amount);
                    }
                }

                $this->updateProductQuantity($dispute->order_id,$dispute->id);

                $is_refunded = 1;
                if(!is_null($order_status)) {
                    $this->update_order_status($dispute->order_id, $order_status, $is_refunded, $amount);
                }
                if(!is_null($dispute->conv_id)) {
                    $messageRepo = new MessageRepository();
                    $messageRepo->change_status($dispute->conv_id, $conv_status);
                }

                $dispute->status = $status;
                $dispute->save();
                $message_text = '$' . $amount . ' has been refunded to buyer';
                if(!empty($seller_amount)) {
                    $message_text .= 'and $ ' . $seller_amount . ' has been added to your balance';
                }
                return ['message_text' => $message_text, 'status' => 'success'];
            } catch (WorldpayException $e) {
                return ['message_text' => $e->getMessage(), 'status' => 'error'];
            } catch (Exception $e) {
                return ['message_text' => $e->getMessage(), 'status' => 'error'];
            }
        } else {
            return ['message_text' => 'The order does not belongs to you.', 'status' => 'error'];
        }
    }
    protected function updateProductQuantity($order_id,$dispute_id){

        $product_ids = StoreDisputeProduct::where('dispute_id',$dispute_id)->lists('product_id','product_id');

        if(!$product_ids->isEmpty()) {
            $storeOrderItems = StoreOrderItems::whereIn('product_id', $product_ids)
                ->where('order_id', $order_id)
                ->get();

            foreach ($storeOrderItems as $item) {
                $product = StoreProduct::where('id', $item->product_id)->first();
                $product->quantity = $product->quantity + $item->quantity;
                $product->sold = $product->sold - $item->quantity;
                $product->save();
            }
        }
    }
    public function update_status($id, $status, $order_status = NULL, $conv_status = 0) {
        $dispute = StoreDispute::find($id);
        if(!is_null($order_status)) {
            $this->update_order_status($dispute->order_id, $order_status);
        }
        if(!is_null($dispute->conv_id)) {
            $messageRepo = new MessageRepository();
            $messageRepo->change_status($dispute->conv_id, $conv_status);
        }
        $dispute->status = $status;
        $dispute->save();
        return TRUE;
    }

    public function isSellerOrder($order_id, $user_id) {
        return StoreOrder::where('id', $order_id)->where('seller_id', $user_id)->count();
    }

    public function update_order_status($order_id, $status, $is_refunded = 0, $refunded_amount = 0) {
        $store         = StoreOrder::find($order_id);
        $store->status = $status;
        if($is_refunded > 0) {
            $store->is_refunded = $is_refunded;
        }
        if($refunded_amount > 0) {
            $store->refund_amount = $refunded_amount;
        }
        $store->save();

        return TRUE;
    }

    private function shipping_info($order_id) {
        return $shipping = DeliveryCourier::where('order_id', $order_id)->first();
    }

    public function store_claim($request) {
        $hasClaim = $this->hasClaim($request->owner_type, $request->dispute_id);

        if($hasClaim) {
            $claim = StoreClaim::find($hasClaim->uuid);
            //$bankAccount = StoreBankAccount::find($hasClaim->account_id);
        } else {
            $claim = new StoreClaim();
            //$bankAccount = new StoreBankAccount();
        }

        $claim->owner_type = $request->owner_type;
        $claim->owner_id   = $request->dispute_id;
        $claim->reason     = $request->reason;
        $claim->detail     = $request->detail;
        $claim->title      = $request->title;
        $claim->save();
        $dispute = StoreDispute::where('id', $request->dispute_id)->first();
        $this->update_status($dispute->reference_id, \Config::get('constants_brandstore.DISPUTE_STATUS.CLAIMED_BY_BUYER'), NULL, 1);

        $this->update_order_status($dispute->order_id, \Config::get('constants_brandstore.ORDER_STATUS.ORDER_DISPUTE_CLAIMED'));

        /*$bankAccount->user_id = $request->user_id;
        $bankAccount->account_title = $request->account_title;
        $bankAccount->account_number = $request->account_number;
        $bankAccount->iban_number = $request->iban_code;
        $bankAccount->swift_code = $request->swift_code;
        $bankAccount->country_code = $request->country;
        $bankAccount->bank_name = $request->bank_name;
        $bankAccount->save();*/
        //$claim->bank_account_id = $bankAccount->id;
        $claim->bank_account_id = 0;
        $claim->save();

        return $claim->id;

    }

    private function hasClaim($owner_type, $dispute_id) {
        $claim = StoreClaim::where('owner_type', $owner_type)->where('owner_id', $dispute_id)->first();
        if($claim) {
            return $claim;
        } else {
            return FALSE;
        }
    }

    public function get_claim($id, $type) {
        return StoreClaim::where('owner_type', $type)->where('owner_id', $id)->first();
    }

    private function addRequestedProducts($id, $products) {
        $this->deleteRequestedProducts($id);
        foreach ($products as $product) {
            $disputeProduct = new StoreDisputeProduct();

            $disputeProduct->dispute_id = $id;
            $disputeProduct->product_id = $product;
            $disputeProduct->save();
        }
        return TRUE;
    }

    private function deleteRequestedProducts($id) {
        StoreDisputeProduct::where('dispute_id', $id)->delete();
        return TRUE;
    }

    public function getDisputedProducts($id) {
        return StoreDisputeProduct::where('dispute_id', $id)->get()->keyBy('product_id');
    }

    public function getDisputedProductsDetail($id) {
        $orderAllProductsIds = StoreDisputeProduct::where('dispute_id', $id)->lists('product_id');

        return $orderAllProducts = StoreProduct::whereIn('id', $orderAllProductsIds)->get()->keyBy('id');
    }
}
