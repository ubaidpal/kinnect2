<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : kinnect2
 * Product Name : PhpStorm
 * Date         : 04-Mar-2016 6:50 PM
 * File Name    : ClaimRepository.php
 */

namespace App\Repository\Eloquent\Admin;

use App\Events\SendEmail;
use App\Repository\Eloquent\MessageRepository;
use App\Repository\Eloquent\Repository;
use App\StoreClaimRequest;
use App\User;
use kinnect2Store\Store\Repository\admin\StoreAdminRepository;
use kinnect2Store\Store\Repository\DisputeRepository;
use kinnect2Store\Store\StoreClaim;
use kinnect2Store\Store\StoreDispute;
use kinnect2Store\Store\StoreOrder;
use kinnect2Store\Store\StoreOrderTransaction;

class ClaimRepository extends Repository
{
    protected $status;

    public function __construct() {
        parent::__construct();
        $this->status = \Config::get('admin_constants.CLAIM_STATUS');

    }

    public function unassignedClaims() {

        return StoreClaim::where('status', $this->getStatus('NOT_ASSIGNED'))->with('dispute.user')
                         ->with('dispute.order')->orderBy('updated_at', 'DESC')->paginate(50);
    }

    public function assignedClaims($user) {
        $query = StoreClaim::where('status', $this->getStatus('ASSIGNED'))->with('dispute.user')->with('dispute.order');

        if(!$user->is('super.admin') && !$user->is('dispute.manager')) {
            $query->where('arbitrator_id', $user->id);
        }

        return $query->orderBy('updated_at', 'DESC')->paginate(50);

    }

    public function arbitrators() {
        return \DB::table('role_user')->join('users', 'users.id', '=', 'role_user.user_id')
                  ->where('role_user.role_id', '3')->lists('users.displayname', 'users.id');
    }

    public function assign_claim($claim_id, $arbitrator) {

        $claim   = StoreClaim::find($claim_id);
        $conv_id = $this->getConvID($claim->owner_id);

        $messageRepo = new MessageRepository();

        if(!is_null($claim->arbitrator_id)) {
            $messageRepo->leave_group($conv_id, $claim->arbitrator_id);
        }
        $claim->arbitrator_id = $arbitrator;
        $claim->status        = $this->getStatus('ASSIGNED');
        $claim->save();

        if($conv_id) {
            $messageRepo->add_member_to_group([$arbitrator], $conv_id);
        }

    }

    /**
     * @return mixed
     */
    public function getStatus($status) {
        return $this->status[$status];
    }

    public function get_claim($id, $type) {
        return StoreClaim::where('owner_id', $id)->where('owner_type', $type)->first();
    }

    private function getConvID($owner_id) {
        $data = StoreDispute::where('id', $owner_id)->first();
        if(!is_null($data->conv_id)) {
            return $data->conv_id;
        } else {
            return FALSE;
        }
    }

    public function resolved($claim) {

        $amount_check = $this->check_amount(\Input::get('amount'), $claim->owner_type, $claim->owner_id);

        if(!$amount_check) {
            \Request::session()->flash('error', 'Amount must be less then claimed amount');
            return redirect()->back();
        }
        //Upload Attachment
        if(\Input::hasFile('attachment')) {
            $ext        = \Input::file('attachment')->getClientOriginalExtension();
            $rand       = random_id(5);
            $attachment = $rand . '.' . $ext;
            \Input::file('attachment')->move(\Config::get('constants_activity.ATTACHMENT_PATH'), $attachment);
            $claim->attachment = $attachment;
        };

        //Get Dispute
        $dispute  = StoreDispute::where('id', $claim->owner_id)->first();
        $order_id = $dispute->order_id;

        //change Dispute Status
        $disputeRepo = new DisputeRepository();
        $disputeRepo->update_status($dispute->reference_id, \Config::get('constants_brandstore.DISPUTE_STATUS.RESOLVED'), NULL, 0);

        $claim->status  = \Config::get('admin_constants.CLAIM_STATUS.RESOLVED');
        $claim->remarks = \Input::get('remarks');

        $sarObj = new StoreAdminRepository();

        if(\Input::has('seller') && empty(\Input::has('buyer'))) {
            $sale = \Config::get('constants_brandstore.STATEMENT_TYPES.SALE');
            $sarObj->updateStatement($sale, 'store_order', $order_id, 'credit', 'USD');
            $fee = \Config::get('constants_brandstore.STATEMENT_TYPES.ORDER_SHIPPING_FEE');
            $sarObj->updateStatement($fee, 'store_order', $order_id, 'credit', 'USD');
        } else {
            $buyer_amount       = \Input::get('amount');
            $order_transaction  = StoreOrderTransaction::where('order_id', $order_id)->first();
            $transaction_amount = $order_transaction->amount;
            if($buyer_amount > $transaction_amount) {
                \Request::session()->flash('error', 'Amount must be less then transaction amount');
                return redirect()->back();
            }
            $seller_amount = $transaction_amount - $buyer_amount;
            if($seller_amount > 0) {
                $type      = \Config::get('constants_brandstore.STATEMENT_TYPES.DISPUTE_PARTIAL_TRANSFER');
                $seller    = StoreOrder::where('id', $order_id)->select(['seller_id'])->first();
                $seller_id = $seller->seller_id;
                $sarObj->updateStatement($type, 'store_claim', $claim->id, 'credit', 'USD', $seller_id, $seller_amount);
            }
        }

        if(\Input::has('seller')) {
            $claim->favour_of_seller = 1;
        }
        if(\Input::has('buyer')) {
            $claim->favour_of_buyer = 1;
            $claim->amount          = \Input::get('amount');
        }
        $claim->save();

        //Send request to Account manager
        $claimRequest = ['owner_id' => $claim->id, 'owner_type' => 'claim', 'status' => 'pending', 'seller_id' => \Input::get('seller_id'), 'amount' => \Input::get('amount'),];

        //if claim is not on the favour of buyer then no claim request is created
        if(\Input::has('buyer') && \Input::get('amount') > 0) {
            $this->createClaimRequest($claimRequest);
        }
        $order = StoreOrder::find($order_id);
        if(!empty($order->id)) {
            $order->status = \Config::get('constants_brandstore.ORDER_STATUS.ORDER_DISPUTE_RESOLVED');
            $order->save();
        }
        $seller = User::find($order->seller_id)->email;
        $buyer  = User::find($order->customer_id)->email;

        $disputeRepo = new DisputeRepository();
        $disputeRepo->update_order_status($order->id, \Config::get('constants_brandstore.ORDER_STATUS.ORDER_DISPUTE_RESOLVED'));

        $data = array('message' => 'Claim Is resolved by Arbitrator', 'from' => \Config::get('admin_constants.FEEDBACK_EMAIL'), 'name' => 'Admin', 'template' => 'feedback', 'to' => $seller,);

        //return redirect()->back();

        \Event::fire(new SendEmail($data));
        $data = array('message' => 'Claim Is resolved by Arbitrator', 'from' => \Config::get('admin_constants.FEEDBACK_EMAIL'), 'name' => 'Admin', 'template' => 'claim-resolved', 'to' => $buyer,);

        //return redirect()->back();

        \Event::fire(new SendEmail($data));

        $accounts_manager = getenv('ACCOUNT_MANAGER_EMAIL');

        $data = array(
            'from'     => \Config::get('admin_constants.FEEDBACK_EMAIL'),
            'name'     => 'Admin',
            'message'  => \Input::get('remarks'),
            'template' => 'claim-resolved',
            'to'       => $accounts_manager,
        );

        //return redirect()->back();

        \Event::fire(new SendEmail($data));
        //echo '<tt><pre>'; print_r($order); die;

        //Send email to Accounts Manager
        $accounts_manager = getenv('ACCOUNT_MANAGER_EMAIL');

        $data = array(
            'from'     => \Config::get('admin_constants.FEEDBACK_EMAIL'),
            'name'     => 'Admin',
            'message'  => \Input::get('remarks'),
            'template' => 'claim-resolved',
            'to'       => $accounts_manager,
            'subject'  => 'Claim is resolved'
        );

        //return redirect()->back();

        \Event::fire(new SendEmail($data));

    }

    public function createClaimRequest($data) {
        $claimRequest = StoreClaimRequest::whereOwnerId($data['owner_id'])->whereOwnerType($data['owner_type'])
                                         ->first();
        if(!$claimRequest) {
            $claimRequest = new StoreClaimRequest();
        }

        $claimRequest->amount     = $data['amount'];
        $claimRequest->owner_type = $data['owner_type'];
        $claimRequest->owner_id   = $data['owner_id'];
        $claimRequest->seller_id  = $data['seller_id'];
        //$claimRequest->status   = \Config::get('admin_constants.CLAIM_REQUEST_STATUS.IN_PROCESS');
        $claimRequest->status = $data['status'];
        $claimRequest->save();
    }

    public function resolvedClaims() {
        return StoreClaim::where('status', $this->getStatus('RESOLVED'))->with('dispute.user')->with('dispute.order')
                         ->orderBy('updated_at', 'DESC')->paginate(50);
    }

    private function check_amount($arbitrator_amount, $owner_type, $owner_id) {

        //if($owner_type == 'dispute'){
        $data = StoreDispute::where('id', $owner_id)->first();
        // }

        $amount = $data->claimed_amount;
        if($data->claim_request == 'full') {
            $amount = StoreOrderTransaction::where('order_id', $data->order_id)->first();
            $amount = $amount->amount;
        }

        if($arbitrator_amount <= $amount) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function searchClaims($user) {
        $type = \Input::get('type');
        switch ($type) {
            case 'resolved':
                $data = $this->searchClaimsByType($user, $this->getStatus('RESOLVED'));
                break;
            case 'assigned':
                $data = $this->searchClaimsByType($user, $this->getStatus('ASSIGNED'));
                break;
            case 'unassigned':
                $data = $this->searchClaimsByType($user, $this->getStatus('NOT_ASSIGNED'));
                break;
        }
        return $data;
    }

    public function searchClaimsByType($user, $status) {
        $order_id = trim(\Input::get('order_id'));
        $claims   = \DB::table('store_claims')
                       ->join('store_disputes', 'store_claims.owner_id', '=', 'store_disputes.id')
                       ->join('store_orders', 'store_disputes.order_id', '=', 'store_orders.id')
                       ->join('users', 'users.id', '=', 'store_disputes.owner_id')
                       ->where('store_claims.status', $status)
                       ->where('store_orders.order_number', 'like', "$order_id%")
                       ->select(['store_disputes.id as dispute_id', 'store_disputes.*', 'store_claims.id as claim_id', 'store_claims.*', 'store_orders.*', 'users.id as user_id', 'users.displayname']);

        if(!$user->is('super.admin') && !$user->is('dispute.manager')) {
            $claims->where('arbitrator_id', $user->id);
        };

        $claims = $claims->paginate(50);

        $data = [];
        $row  = new \stdClass();
        foreach ($claims as $claim) {
            $row->detail           = $claim->detail;
            $row->title            = $claim->title;
            $row->created_at       = $claim->created_at;
            $row->arbitrator_id    = $claim->arbitrator_id;
            $row->uuid             = $claim->uuid;
            $row->id               = $claim->claim_id;
            $row->owner_type       = $claim->owner_type;
            $row->arbitrator_id    = $claim->arbitrator_id;
            $row->favour_of_seller = $claim->favour_of_seller;
            $row->favour_of_buyer  = $claim->favour_of_buyer;
            $row->amount           = $claim->amount;
            $row->remarks          = $claim->remarks;
            $row->attachment       = $claim->attachment;

            @$row->dispute->reference_id = $claim->reference_id;
            $row->dispute->owner_id       = $claim->owner_id;
            $row->dispute->order_id       = $claim->order_id;
            $row->dispute->is_received    = $claim->is_received;
            $row->dispute->claim_request  = $claim->claim_request;
            $row->dispute->claimed_amount = $claim->claimed_amount;
            $row->dispute->reason         = $claim->reason;
            $row->dispute->detail         = $claim->detail;
            $row->dispute->status         = $claim->status;
            $row->dispute->conv_id        = $claim->conv_id;
            $row->dispute->created_at     = $claim->created_at;
            $row->dispute->updated_at     = $claim->updated_at;

            @$row->dispute->order->order_number = $claim->order_number;
            $row->dispute->order->id                  = $claim->id;
            $row->dispute->order->total_shiping_cost  = $claim->total_shiping_cost;
            $row->dispute->order->received_date       = $claim->received_date;
            $row->dispute->order->shiping_date        = $claim->shiping_date;
            $row->dispute->order->is_deleted          = $claim->is_deleted;
            $row->dispute->order->approved_date       = $claim->approved_date;
            $row->dispute->order->total_discount      = $claim->total_discount;
            $row->dispute->order->total_quantity      = $claim->total_quantity;
            $row->dispute->order->total_price         = $claim->total_price;
            $row->dispute->order->payment_type        = $claim->payment_type;
            $row->dispute->order->delivery_address_id = $claim->delivery_address_id;
            $row->dispute->order->seller_id           = $claim->seller_id;
            $row->dispute->order->customer_id         = $claim->customer_id;
            $row->dispute->order->fee_amount          = $claim->fee_amount;
            $row->dispute->order->fee_paid            = $claim->fee_paid;

            @$row->dispute->user->id = $claim->user_id;
            $row->dispute->user->displayname = $claim->displayname;

            $data[] = $row;
        }
        return $data;
    }
}
