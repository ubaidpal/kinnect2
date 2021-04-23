<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : kinnect2
 * Product Name : PhpStorm
 * Date         : 24-Feb-2016 3:46 PM
 * File Name    : DisputeController.php
 */

namespace kinnect2Store\Store\Http;

use App\Conversation;
use App\Http\Controllers\Controller;
use App\Repository\Eloquent\MessageRepository;
use App\Services\StorageManager;
use App\Traits\UploadAttachment;
use App\User;
use Illuminate\Http\Request;
use kinnect2Store\Store\Repository\admin\StoreAdminRepository;
use kinnect2Store\Store\Repository\DisputeRepository;
use kinnect2Store\Store\Repository\StoreOrderRepository;
use kinnect2Store\Store\StoreAlbumPhotos;
use kinnect2Store\Store\StoreClaim;
use kinnect2Store\Store\StoreDispute;
use kinnect2Store\Store\StoreOrder;
use kinnect2Store\Store\StoreOrderTransaction;
use kinnect2Store\Store\StoreStorageFiles;
use App\Classes\Worldpay;
use App\Classes\WorldpayException;
use kinnect2Store\Store\StoreClaimFeeTransaction;

class DisputeController extends Controller
{
    use UploadAttachment;
    protected $user_id;
    protected $user;
    protected $is_api;
    protected $data;
    /**
     * @var DisputeRepository
     */
    private $disputeRepository;
    /**
     * @var StoreAdminRepository
     */
    private $storeAdminRepository;

    /**
     * DisputeController constructor.
     *
     * @param Request $middleware
     * @param DisputeRepository $disputeRepository
     * @param StoreAdminRepository $storeAdminRepository
     */
    public function __construct(Request $middleware, DisputeRepository $disputeRepository, StoreAdminRepository $storeAdminRepository) {
        $this->user_id = $middleware['middleware']['user_id'];
        $this->user    = $middleware['middleware']['user'];
        $this->is_api  = $middleware['middleware']['is_api'];

        $this->disputeRepository = $disputeRepository;
        @$this->data->title = 'Dispute Detail';
        $this->data->url_user_id    = $this->user_id;
        $this->storeAdminRepository = $storeAdminRepository;
    }

    public function orderDispute(Request $request) {

        $data['url_user_id'] = $this->user_id;
        //$data['allOrders']   = $this->disputeRepository->getAllOrdersCurrentUser();
        $order_id = $this->disputeRepository->addDisputeRecord($request, $this->user_id);
        if($order_id > 0) {

            return $order_id;
        }

        return 0;
    }

    public function get_dispute($id) {

        $data = $this->disputeRepository->get_dispute($id);

        $this->data->dispute   = $data['dispute'];
        $this->data->files     = $data['files'];
        $this->data->countries = $data['countries'];

        if($this->data->dispute->status == \Config::get('constants_brandstore.DISPUTE_STATUS.CLAIMED_BY_BUYER') || $this->data->dispute->status == \Config::get('constants_brandstore.DISPUTE_STATUS.RESOLVED')) {
            $this->data->claim_detail = $this->disputeRepository->get_claim($this->data->dispute->id, 'dispute');
        }

        if($data['dispute']->claim_request == 'full') {
            $this->data->order_transection = StoreOrderTransaction::where('order_id', $data['dispute']->order_id)->first();
        }
        $this->data->shipping_info = $data['shipping_info'];
        $this->data->reasons       = \DB::table('claim_reasons')->orderBy('reason', 'ASC')->lists('reason', 'id');
        $this->data->order         = StoreOrder::find($this->data->dispute->order_id);

        if(is_null($this->data->dispute->conv_id)) {
            if($this->user->user_type == \Config::get('constants.REGULAR_USER')) {
                $this->data->seller_id = $this->data->order->seller_id;
            } else {
                $this->data->seller_id = $this->data->order->customer_id;
            }
        } else {
            $messageRepo          = new MessageRepository();
            $this->data->messages = $messageRepo->getConvAllMessages($this->data->dispute->conv_id, 'ASC');
        }
        $this->data->requestedProducts = $this->disputeRepository->getDisputedProductsDetail($this->data->dispute->id);

        $this->data = (array)$this->data;

        return view('Store::dispute.dispute-detail', $this->data);
    }

    public function edit_dispute($id) {
        $orderRepo = new StoreOrderRepository();
        $data      = $this->disputeRepository->get_dispute($id);

        $this->data->dispute           = $data['dispute'];
        $this->data->payment_received  = $orderRepo->paymentReceivedInfoSingle($this->data->dispute->order_id);
        $this->data->files             = $data['files'];
        $this->data->order             = $orderRepo->getOrder($this->data->dispute->order_id);
        $this->data->deliveryInfo      = $orderRepo->getOrderDeliveryInfo($this->data->dispute->order_id);
        $this->data->title             = "Modify Dispute";
        $storeRepo                     = new StoreOrderRepository();
        $this->data->products          = $storeRepo->getOrderAllProductsByKey($this->data->dispute->order_id);
        $this->data->requestedProducts = $this->disputeRepository->getDisputedProducts($this->data->dispute->id);

        $this->data = (array)$this->data;

        return view('Store::dispute.edit', $this->data);

    }

    public function cancel_dispute($id) {
        if($this->user->user_type == \Config::get('constants.REGULAR_USER')) {
            $status       = \Config::get('constants_brandstore.DISPUTE_STATUS.DISPUTE_CANCELLED_BUYER');
            $order_status = \Config::get('constants_brandstore.ORDER_STATUS.ORDER_DISPUTED_CANCELLED');
        } else {
            $status       = \Config::get('constants_brandstore.DISPUTE_STATUS.DISPUTE_CANCELLED_SELLER');
            $order_status = \Config::get('constants_brandstore.ORDER_STATUS.ORDER_DISPUTED_REJECTED');
        };

        $this->disputeRepository->update_status($id, $status, $order_status);

        return redirect()->back();
    }

    public function accept_dispute($id) {

        $status       = \Config::get('constants_brandstore.DISPUTE_STATUS.DISPUTE_ACCEPTED');
        $order_status = \Config::get('constants_brandstore.ORDER_STATUS.ORDER_DISPUTE_ACCEPTED');

        $message = $this->disputeRepository->accept_dispute($id, $status, $order_status, 0, $this->user_id);

        return redirect()->back()->with($message['status'], $message['message_text']);
    }

    public function message(Request $request) {
        $messageRepo = new MessageRepository();
        if(!$request->has('conv_id')) {
            $conv = $messageRepo->createConversation([$this->user_id, $request->receiver_id], $this->user_id, 'group');

            $request['conv_id'] = $conv['convId'];
            $conv_id            = $conv['convId'];
            $conv               = Conversation::find($conv_id);
            $conv->conv_for     = 'dispute';
            $conv->save();

            $dispute          = StoreDispute::find($request->dispute_id);
            $dispute->conv_id = $request['conv_id'];
            $dispute->save();
        }
        $value        = $request->file('attachment');
        $file['data'] = [];
        if(!empty($value)) {
            $file               = $this->upload_attachment($value, $this->user_id);
            $request['file_id'] = $file['file_id'];
        }

        $messageRepo->save_message($request, $this->user_id);

        $data = $request->except('middleware');

        return view('Store::dispute.new-message', ['data' => $data, 'attachment' => $file['data']]);

    }

    public function claim_store(Request $request) {
        $claim_id = $this->disputeRepository->store_claim($request);

        return redirect('/claimFee/' . $claim_id);
    }

    public function claimFee($claim_id) {
        $data['claim_id']  = $claim_id;
        $data['claim_fee'] = \Config::get('constants_brandstore.CLAIM_FILE_FEE');

        return view('Store::dispute.claimFee', $data);
    }

    public function payClaimFee($claim_id) {
        $data['claim_id']  = $claim_id;
        $claim_fee         = \Config::get('constants_brandstore.CLAIM_FILE_FEE');
        $data['claim_fee'] = $claim_fee;
        $claim             = StoreClaim::where('id', $claim_id)->select(['fee_paid', 'fee_amount', 'owner_id'])->first();
        if($claim->fee_paid) {
            $dispute = StoreDispute::where('id', $claim->owner_id)->select(['order_id'])->first();
            return redirect('store/order/dispute/' . $dispute->order_id)->with('error', 'The fee has been paid for this claim');
        }
        $service_key = \Config::get('constants_brandstore.WORLDPAY_SERVICE_KEY');

        $world_pay = new Worldpay($service_key);

        $inputTokenWorldPay = \Input::get('token');

        $billing_address = array(
            "address1"   => '',
            "address2"   => '',
            "postalCode" => '',
            "city"       => '',
            "state"      => '',
        );
        try {
            $response = $world_pay->createOrder(array(
                'token'             => $inputTokenWorldPay,
                'amount'            => round($claim_fee, 2) * 100,
                'currencyCode'      => 'USD',
                'name'              => 'Kinnect2',
                'billingAddress'    => $billing_address,
                'orderDescription'  => 'Claim File Fee : ClaimID: : ' . $claim_id,
                'customerOrderCode' => $claim_id
            ));
            if($response['paymentStatus'] === 'SUCCESS') {
                $this->saveClaimFeeTransaction($response, $claim_id);
                $dispute = StoreDispute::where('id', $claim->owner_id)->select(['order_id'])->first();
                return redirect('store/order/dispute/' . $dispute->order_id)->with('success', 'The fee amount ' . format_currency($claim_fee) . ' has been paid for this claim');
            }
        } catch (WorldpayException $e) {
            $data['e'] = $e;
            return view('Store::dispute.claimFee', $data);
        } catch (Exception $e) {
            echo 'Error message: ' . $e->getMessage();
        }

    }

    public function product_image_ajax(Request $request) {

        $product_file = $request->file('product_image');
        $product_id   = $request->get('product_id');

        $sm   = new StorageManager();
        $data = $sm->storeFile($this->user_id, $product_file, 'album_photo');

        $sfObj                 = new StoreStorageFiles();
        $sfObj->parent_file_id = !empty($data['parent_file_id']) ? $data['parent_file_id'] : NULL;
        $sfObj->type           = !empty($data['type']) ? $data['type'] : NULL;
        $sfObj->parent_id      = isset($data['parent_id']) ? $data['parent_id'] : $product_id;
        $sfObj->parent_type    = $data['parent_type'];
        $sfObj->user_id        = $data['user_id'];
        $sfObj->storage_path   = $data['storage_path'];
        $sfObj->extension      = $data['extension'];
        $sfObj->name           = $data['name'];
        $sfObj->mime_type      = $data['mime_type'];
        $sfObj->size           = $data['size'];
        $sfObj->hash           = $data['hash'];

        if(!$sfObj->save()) {
            $message = ['status' => 0];
        } else {

            if(!empty($product_id)) {
                $this->storeAdminRepository->resizeProductImage($sfObj->storage_path, $sfObj->file_id, $sfObj->user_id, 'product', 'product_profile', '151', '210', $product_id);
                $this->storeAdminRepository->resizeProductImage($sfObj->storage_path, $sfObj->file_id, $sfObj->user_id, 'product', 'product_thumb', '170', '170', $product_id);
                $this->storeAdminRepository->resizeProductImage($sfObj->storage_path, $sfObj->file_id, $sfObj->user_id, 'product', 'product_icon', '54', '80', $product_id);
            }

            $message = ['id' => $sfObj->file_id, 'path' => \Config::get('constants_activity.PHOTO_URL') . $sfObj->storage_path . '?type=' . urlencode($sfObj->mime_type), 'status' => 1];
        }

        return response()->json($message);
    }

    public function delete_product_image(Request $request) {
        $file_id = $request->get('file_id');

        if($file_id > 0) {

            $file = StoreStorageFiles::where('file_id', $file_id)->where('user_id', $this->user_id)->select(['file_id', 'storage_path'])->first();

            $sm = new StorageManager();

            if(!empty($file->storage_path) && $sm->pathExists(('photos/' . $file->storage_path))) {
                $sm->deletFile(('photos/' . $file->storage_path));
                StoreStorageFiles::where('parent_file_id', $file_id)->delete();
                StoreAlbumPhotos::where('file_id', $file_id)->delete();
                $file->delete();
                $status = 1;
            } else {
                $status = 0;
            }
        } else {
            $status = 0;
        }

        return response()->json(['status' => $status]);
    }

    public function saveClaimFeeTransaction($response, $claim_id) {
        $transaction = new StoreClaimFeeTransaction();

        $transaction->claim_id         = $claim_id;
        $transaction->gateway_id       = 2;
        $transaction->type             = $response['paymentResponse']['type'];
        $transaction->state            = $response['paymentStatus'];
        $transaction->transaction_code = $response['orderCode'];
        $transaction->amount           = round($response['amount'] / 100, 2);
        $transaction->currency         = $response['currencyCode'];
        $transaction->response_object  = serialize($response);
        if($transaction->save()) {
            $claim             = StoreClaim::where('id', $claim_id)->select(['id', 'fee_paid', 'fee_amount'])->first();
            $claim->fee_paid   = 1;
            $claim->fee_amount = round($response['amount'] / 100, 2);
            $claim->save();
        }
    }
}
