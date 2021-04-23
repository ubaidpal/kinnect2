<?php

namespace App\Http\Controllers\Admin;

use App\Conversation;
use App\Repository\Eloquent\Admin\ClaimRepository;
use App\Repository\Eloquent\MessageRepository;
use App\Traits\UploadAttachment;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use kinnect2Store\Store\Repository\admin\StoreAdminOrderRepository;
use kinnect2Store\Store\Repository\DisputeRepository;
use kinnect2Store\Store\Repository\StoreOrderRepository;
use kinnect2Store\Store\Repository\StoreRepository;
use kinnect2Store\Store\StoreClaim;
use kinnect2Store\Store\StoreDispute;
use kinnect2Store\Store\StoreOrder;
use kinnect2Store\Store\StoreOrderTransaction;

class ClaimController extends Controller
{
    use UploadAttachment;
    protected $data;
    protected $user;
    /**
     * @var ClaimRepository
     */
    private $claimRepository;

    /**
     * ClaimController constructor.
     *
     * @param ClaimRepository $claimRepository
     */
    public function __construct(ClaimRepository $claimRepository, Request $middleware) {

        $this->claimRepository = $claimRepository;
        @$this->data->title = "Claims";
        $this->data->admin_url = \Config::get('constants.ADMIN_URL_PREFIX');

        $this->user_id = \Auth::user()->id;
        $this->user    = \Auth::user();
        $this->is_api  = $middleware['middleware']['is_api'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $this->data->title      = 'Claims - Unassigned';
        $this->data->claims     = $this->claimRepository->unassignedClaims();
        $this->data->arbitrator = $this->claimRepository->arbitrators();
        $this->data->type       = 'unassigned';
        $data                   = (array)$this->data;

        return view('admin.claims.index', $data);
    }

    public function assigned() {
        $this->data->title      = 'Claims - Assigned';
        $this->data->claims     = $this->claimRepository->assignedClaims($this->user);
        $this->data->arbitrator = $this->claimRepository->arbitrators();
        $this->data->type       = 'assigned';
        $data                   = (array)$this->data;

        return view('admin.claims.index', $data);
    }

    public function get_resolved() {
        $this->data->title  = 'Claims - Resolved';
        $this->data->claims = $this->claimRepository->resolvedClaims();
        $this->data->type   = 'resolved';
        $data               = (array)$this->data;

        return view('admin.claims.index', $data);
    }

    public function claim_detail($id) {
        $disputeRepository = new DisputeRepository();
        $data              = $disputeRepository->get_dispute($id);

        $this->data->dispute       = $data['dispute'];
        $this->data->files         = $data['files'];
        $this->data->title         = 'Claims - Detail';
        $this->data->shipping_info = $data['shipping_info'];
        if(is_null($this->data->dispute->conv_id)) {
            $this->data->seller_id = StoreOrder::find($this->data->dispute->order_id)->seller_id;
        } else {
            $messageRepo          = new MessageRepository();
            $this->data->messages = $messageRepo->getConvAllMessages($this->data->dispute->conv_id, 'ASC');
        }

        if($data['dispute']->claim_request == 'full') {
            $this->data->order_transection = StoreOrderTransaction::where('order_id', $data['dispute']->order_id)->first();
        }
        $this->data->claim             = $this->claimRepository->get_claim($this->data->dispute->id, 'dispute');
        $this->data->arbitrator        = $this->claimRepository->arbitrators();
        $this->data->requestedProducts = $disputeRepository->getDisputedProductsDetail($this->data->dispute->id);
        //echo '<tt><pre>'; print_r($this->data->dispute); die;
        $data                          = (array)$this->data;

        return view('admin.claims.detail', $data);
    }

    public function assign(Request $request) {

        if($this->user->is('super.admin') || $this->user->is('dispute.manager')) {
            $claim_id   = $request->id;
            $arbitrator = $request->arbitrator;
            $this->claimRepository->assign_claim($claim_id, $arbitrator);

            return 1;
        } else {
            return 2;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function assign_arbitrator($id) {
        if($this->user->isArbitrator()) {
            $claim_id   = $id;
            $arbitrator = $this->user_id;
            $this->claimRepository->assign_claim($claim_id, $arbitrator);

            return redirect()->back();
        } else {
            return redirect()->back();
        }
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

        $data         = $request->except('middleware');
        $data['user'] = \Auth::user();

        return view('admin.claims.new-message', ['data' => $data, 'attachment' => $file['data']]);

    }

    public function resolved() {

        $claim = StoreClaim::find(\Input::get('claim_id'));
        if(\Gate::allows('resolve', $claim) || $this->user->is('super.admin')) {
            $this->claimRepository->resolved($claim);
        }

        return redirect()->back();
    }

    public function search() {
        $this->data->claims     = $this->claimRepository->searchClaims($this->user);
        $this->data->arbitrator = $this->claimRepository->arbitrators();
        $this->data->type       = \Input::get('type');
        $this->data->order_id   = trim(\Input::get('order_id'));
        $this->data->serach     = 'search';

        $data = (array)$this->data;

        return view('admin.claims.index', $data);
    }

    public function getOrderInvoice($id) {
        $data['order_id']    = $order_id = $id;
        $data['url_user_id'] = $store_owner = $this->user->username;

        $storeAdminOrderRepository = new StoreAdminOrderRepository();
        $storeRepository           = new StoreRepository();

        $data['order']          = $storeAdminOrderRepository->getOrderById($order_id);

        $data['orderCourier']   = $order = $storeAdminOrderRepository->getOrderCourierByOrderId($order_id);
        $data['orderAddresses'] = $order = $storeRepository->getOrderAddressesByOrderId($data['order']->delivery_address_id);
        $data['orderPayments']  = $order = $storeAdminOrderRepository->getOrderPaymentByOrderId($order_id);

        return view('Store::orders.orderInvoice', $data);
    }
}
