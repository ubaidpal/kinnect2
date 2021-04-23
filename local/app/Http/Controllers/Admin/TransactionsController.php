<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Events\SendEmail;
use kinnect2Store\Store\StoreBankAccount;
use kinnect2Store\Store\StoreClaim;
use kinnect2Store\Store\StoreDispute;
use kinnect2Store\Store\StoreOrderTransaction;
use kinnect2Store\Store\StoreOrder;
use kinnect2Store\Store\StoreWithdrawal;
use kinnect2Store\Store\StoreStorageFiles;
use App\User;
use DB;
use Auth;
use kinnect2Store\Store\StoreWithdrawalMethod;
use App\Services\StorageManager;
use kinnect2Store\Store\Repository\admin\StoreAdminRepository;
use kinnect2Store\Store\Repository\StoreRepository;
use kinnect2Store\Store\StoreTransaction;
use App\StoreClaimRequest;
use App\Classes\Worldpay;
use App\Classes\WorldpayException;

class TransactionsController extends Controller{

    protected $user_id = null;

    public function __construct()
    {
        $this->user_id = Auth::user()->id;
    }
    public function index(){

        $seller = \Input::get('seller');
        $key = \Input::get('key');
        $data['key'] = $key;
        $query = StoreTransaction::select(['user_id'])->groupBy('user_id')->orderBy('id','DESC');

        if(!empty($seller)){
            $query->where('user_id',$seller);
        }

        if(!empty($key)){
            $users = User::where('displayname','like',"%$key%")
                ->where('userable_type','App\Brand')
                ->lists('id','id')->toArray();
            if(!empty($users)){
                $query = $query->whereIn('user_id',$users);
            }
        }

        $transactions = $query->paginate(20);

        $data['seller'] = [];
        $strObj = new StoreRepository();
        foreach ($transactions as $item) {
            $last_month_sales = StoreTransaction::where('user_id',$item->user_id)
                                        ->select(DB::raw('SUM(amount) AS total_price'))
                                        ->where(DB::raw('DATE(created_at)'),'>',date('Y-m-d',strtotime('-30 days')))
                                        ->first();
            $item->total_orders = StoreTransaction::where('user_id',$item->user_id)
                                                    ->where('type',\Config::get('constants_brandstore.STATEMENT_TYPES.SALE'))
                                                    ->count();
            $item->balance = $strObj->getAvailableBalance($item->user_id);
            $item->user_id = User::find($item->user_id)->userable;
            $item->last_month_sales = $last_month_sales->total_price;
          
        }

        $data['transactions'] = $transactions;


        return view('admin.transactions.index',$data)->with('seller',$seller);
    }
    public function withddrawalRequests(){
        $data = [];
        $term = \Input::get('term');
        $status = \Input::get('status');
        $query = StoreWithdrawal::orderBy('id','DESC');
        if(!empty($term)){
            $users = User::where('displayname','like',"%$term%")
                            ->where('userable_type','App\Brand')
                            ->lists('id','id')->toArray();
            if(!empty($users)){
                $query = $query->whereIn('seller_id',$users);
            }else{
                $query = $query->where('seller_id','0');
            }
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $withdrawals = $query->with('seller')->paginate(20);
        $strObj = new StoreRepository();
        foreach($withdrawals as $val){
            $val->balance = $strObj->getAvailableBalance($val->seller_id);

        }
        $data['term'] = $term;
        $data['withdrawals'] = $withdrawals;
        $data['status'] = $status;

        return view('admin.transactions.storeWithdrawlRequests',$data);
    }
    public function viewPaymentMethodDetails($method_id){
        $data = [];
        $method = StoreWithdrawalMethod::where('id',$method_id)->first();

        if($method->method == 'bank'){
            $bank = StoreBankAccount::where('store_withdrawal_method_id',$method_id)->first();
            $data['bank'] = $bank;
        }
        $data['method'] = $method;
        return view('admin.transactions.storeBankDetailPopup',$data);
    }
    public function chagePaymentStatus($withdrawal_id){
        $data = ['withdrawal_id' => $withdrawal_id];
        $withdrawal = StoreWithdrawal::where('id',$withdrawal_id)->first();
        $data['withdrawal'] = $withdrawal;

        if(!empty($withdrawal->deposit_attachment_id)){
           $attachment = StoreStorageFiles::where('file_id',$withdrawal->deposit_attachment_id)
                                        ->select(['file_id','storage_path','mime_type'])
                                        ->first();

            if(!empty($attachment->file_id))
            {
                $withdrawal->attachment_path = url('admin/getAttachment/deposit_slip/'.$attachment->storage_path.'?type='.urlencode(base64_encode($attachment->mime_type)));
            }
        }
        return view('admin.transactions.storePaymentInfo',$data);
    }
    public function startPaymentProcess($withdrawal_id){
        $withdrawal = StoreWithdrawal::where('id',$withdrawal_id)->select(['id','status'])->first();
        if(!empty($withdrawal->id)){
            $withdrawal->status = 'processing';
            $withdrawal->save();
            \Request::session()->flash('messge', 'Process started successfully');
        }else{
            \Request::session()->flash('messge', 'Error Starting process');
        }
        return \Redirect::back();

    }
    public function savePaymentinfo($withdrawal_id){

        $withdrawal = StoreWithdrawal::where('id',$withdrawal_id)->first();
        $deposited_to = \Request::get('deposited_to');
        $deposit_date = \Request::get('deposit_date');
        $slip_number = \Request::get('deposit_slip_number');

        if (\Request::hasFile('attachment') && \Request::file('attachment')->isValid()) {
            $sm = new StorageManager();
            $file_data = $sm->storeFile($withdrawal->seller_id,\Request::file('attachment'),'deposit_slip');

            $ssfObj = new StoreStorageFiles();
            $ssfObj->storage_path = $file_data['storage_path'];
            $ssfObj->extension = $file_data['extension'];
            $ssfObj->name = $file_data['name'];
            $ssfObj->mime_type = $file_data['mime_type'];
            $ssfObj->size = $file_data['size'];
            $ssfObj->hash = $file_data['hash'];
            $ssfObj->user_id = $file_data['user_id'];
            $ssfObj->parent_type = 'store_withdrawal';
            $ssfObj->parent_id = $withdrawal->id;
            $ssfObj->type = 'deposit_slip';
            $ssfObj->save();

            $withdrawal->deposit_attachment_id = $ssfObj->file_id;
        }

        $withdrawal->deposited_to = $deposited_to;
        $withdrawal->deposit_date = date('Y-m-d',strtotime($deposit_date));
        $withdrawal->deposit_slip_number = $slip_number;
        $withdrawal->status = 'completed';

        if($withdrawal->save()){
            $sarObj = new StoreAdminRepository();
            $amount = str_replace(',','',$withdrawal->amount);
            $fee = round($amount * $withdrawal->fee_percentage/100,2);
            $amount = round($amount - $fee,2);
            $type = \Config::get('constants_brandstore.STATEMENT_TYPES.WITHDRAW');
            $sarObj->updateStatement($type,'store_withdrawal',$withdrawal_id,'debit','USD',$withdrawal->seller_id,$amount);
            $fee_type = \Config::get('constants_brandstore.STATEMENT_TYPES.WITHDRAW_FEE');
            $sarObj->updateStatement($fee_type,'store_withdrawal',$withdrawal_id,'debit','USD',$withdrawal->seller_id,$fee);

            $this->sendConfirmationEmail($withdrawal->seller_id,$amount,$fee,$withdrawal->created_at);

            return response()->json(['status' => 1]);
        }else{
            return response()->json(['status' => 0]);
        }
    }
    protected function sendConfirmationEmail($seller_id,$amount,$fee,$created_at){
        $seller = User::where('id',$seller_id)->select('id','first_name','last_name','email')->first();
        $emailData = array(
            'subject' => 'Requested withdrawal amount has been deposited.',
            'message' => 'Requested withdrawal amount has been deposited.',
            'from' => \Config::get('admin_constants.ORDER_STATUS_EMAIL'),
            'name' => 'Kinnect2 Admin',
            'template' => 'withdrawal_confirmation',
            'to' => $seller->email,
            'amount' => $amount,
            'seller' => $seller->first_name .' '.$seller->last_name,
            'fee_amount' => $fee,
            'created_at' => $created_at
        );
        \Event::fire(new SendEmail($emailData));
    }
    public function getAttachment($type,$id,$name){
        $mime = \Input::get('type');

        $sm = new StorageManager();
        $file = $sm->getFile($id,$name,$type);

        return response()->make($file)->header('Content-Type', base64_decode(urldecode($mime)));
    }
    public function claimRequests(){
        $data = [];
        $term = \Input::get('term');
        $status = \Input::get('status');
        $query = StoreClaimRequest::orderBy('id','DESC');
        if(!empty($term)){
            $users = User::where('displayname','like',"%$term%")
                ->where('userable_type','App\Brand')
                ->lists('id','id')->toArray();
            if(!empty($users)){
                $query->whereIn('seller_id',$users);
            }else{
                $query->where('seller_id','0');
            }
        }
        if(!empty($status)){
            $query->where('status',$status);
        }
        $withdrawals = $query->with('store_claim')->paginate(20);
        $strObj = new StoreRepository();
        foreach ($withdrawals as $withdrawal){
            $cliam = StoreClaim::where('id',$withdrawal->owner_id)->select(['owner_id'])->first();
            $dispute = StoreDispute::where('id',@$cliam->owner_id)->select(['owner_id'])->first();
            $withdrawal->user  = User::where('id',@$dispute->owner_id)->select(['first_name','last_name'])->first();
        }

        $data['withdrawals'] = $withdrawals;
        $data['status'] = $status;
        $data['term'] = $term;

        return view('admin.transactions.claimRequests',$data);
    }
    public function startClaimPaymentProcess($request_id){
        $claim_request = StoreClaimRequest::where('id',$request_id)->first();

        if(!empty($claim_request->id)){
            $claim_request->status = 'processing';
            $claim_request->save();
            \Request::session()->flash('messge', 'Process started successfully');
        }else{
            \Request::session()->flash('messge', 'Error Starting process');
        }
        return \Redirect::back();
    }
    public function viewBankDetails($bank_id){
        $bank = StoreBankAccount::where('id',$bank_id)->first();
        $data['bank'] = $bank;
        return view('admin.transactions.storeBankDetailPopup',$data);
    }

    public function chageClaimPaymentStatus($withdrawal_id){
        $data = ['withdrawal_id' => $withdrawal_id,'claim' => 1];
        $withdrawal = StoreClaimRequest::where('id',$withdrawal_id)->first();
        $data['withdrawal'] = $withdrawal;

        if(!empty($withdrawal->deposit_attachment_id)){
            $attachment = StoreStorageFiles::where('file_id',$withdrawal->deposit_attachment_id)
                ->select(['file_id','storage_path','mime_type'])
                ->first();

            if(!empty($attachment->file_id))
            {
                $withdrawal->attachment_path = url('admin/getAttachment/deposit_slip/'.$attachment->storage_path.'?type='.urlencode(base64_encode($attachment->mime_type)));
            }
        }
        return view('admin.transactions.storePaymentInfo',$data);
    }
    public function saveClaimPaymentinfo($withdrawal_id){

        $withdrawal = StoreClaimRequest::where('id',$withdrawal_id)->first();
        $deposited_to = \Request::get('deposited_to');
        $deposit_date = \Request::get('deposit_date');
        $slip_number = \Request::get('deposit_slip_number');

        if (\Request::hasFile('attachment') && \Request::file('attachment')->isValid()) {
            $sm = new StorageManager();
            $file_data = $sm->storeFile($withdrawal->seller_id,\Request::file('attachment'),'deposit_slip');

            $ssfObj = new StoreStorageFiles();
            $ssfObj->storage_path = $file_data['storage_path'];
            $ssfObj->extension = $file_data['extension'];
            $ssfObj->name = $file_data['name'];
            $ssfObj->mime_type = $file_data['mime_type'];
            $ssfObj->size = $file_data['size'];
            $ssfObj->hash = $file_data['hash'];
            $ssfObj->user_id = $file_data['user_id'];
            $ssfObj->parent_type = 'store_withdrawal';
            $ssfObj->parent_id = $withdrawal->id;
            $ssfObj->type = 'deposit_slip';
            $ssfObj->save();

            $withdrawal->deposit_attachment_id = $ssfObj->file_id;
        }

        $withdrawal->deposited_to = $deposited_to;
        $withdrawal->deposit_date = date('Y-m-d',strtotime($deposit_date));
        $withdrawal->deposit_slip_number = $slip_number;
        $withdrawal->status = 'completed';

        if($withdrawal->save()){
            return response()->json(['status' => 1]);
        }else{
            return response()->json(['status' => 0]);
        }
    }
    public function viewPaymentInfo($withdrawal_id){
        $from = \Input::get('from');
        if($from == 'withdrawal'){
            $withdrawal = StoreWithdrawal::where('id',$withdrawal_id)->first();
        }else{
            $withdrawal = StoreClaimRequest::where('id',$withdrawal_id)->first();
        }
        if(!empty($withdrawal->deposit_attachment_id)){
            $attachment = StoreStorageFiles::where('file_id',$withdrawal->deposit_attachment_id)
                ->select(['file_id','storage_path','mime_type'])
                ->first();

            if(!empty($attachment->file_id))
            {
                $withdrawal->attachment_path = url('admin/getAttachment/deposit_slip/'.$attachment->storage_path.'?type='.urlencode(base64_encode($attachment->mime_type)));
            }
        }
        $data['withdrawal'] = $withdrawal;

        return view('admin.transactions.viewPaymentInfo',$data);
    }
    public function makeClaimPayment($request_id){

        $withdrawal = StoreClaimRequest::where('id',$request_id)->first();
        if($withdrawal->status == 'completed'){
            return redirect()->back()->with('error','Amount has been already transferred');
        }
        $amount = $withdrawal->amount;
        $cliam = StoreClaim::where('id',$withdrawal->owner_id)->select(['owner_id'])->first();
        $dispute = StoreDispute::where('id',@$cliam->owner_id)->select(['id','owner_id','order_id'])->first();
        $order_id = @$dispute->order_id;
        if(empty($order_id)){
            return redirect()->back()->with('error','Order does not exists for this claim');
        }
        $transaction = StoreOrderTransaction::where('order_id',$order_id)->select(['id','amount','gateway_transaction_id','state'])->first();

        if(empty($transaction->id)){
            return redirect()->back()->with('error','There is no transaction found for the claimed amount.');
        }
        $order = StoreOrder::where('id',$order_id)->select(['id','customer_id','seller_id'])->first();
        if(empty($order->id)){
            return redirect()->back()->with('error','There is no order found for the claimed amount.');
        }
        $order_code = $transaction->gateway_transaction_id;
        $worldpay = new Worldpay(\Config::get('constants_brandstore.WORLDPAY_SERVICE_KEY'));
        try {
            $worldpay->refundOrder($order_code,$amount*100);
            $withdrawal->status = 'completed';
            $withdrawal->save();

            $stRepositoryObj = new StoreRepository();

            $reversal['parent_type'] = 'store_dispute';
            $reversal['parent_id'] = $dispute->id;
            $reversal['user_id'] = $order->customer_id;
            $reversal['seller_id'] = $order->seller_id;
            $reversal['amount'] = $amount;

            $stRepositoryObj->logStoreReversal($reversal);

            return redirect()->back()->with('success','Amount has been transferred successfully');

        } catch (WorldpayException $e) {
            return redirect()->back()->with('error',$e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
    public function getClaimInfo($claim_id){
        $withdrawal = StoreClaimRequest::where('id',$claim_id)->first();
        $cliam = StoreClaim::where('id',$withdrawal->owner_id)->select(['owner_id'])->first();
        $dispute = StoreDispute::where('id',@$cliam->owner_id)->select(['owner_id'])->first();
        $data['user']  = User::where('id',@$dispute->owner_id)->select(['first_name','last_name'])->first();
        $data['withdrawal'] = $withdrawal;

        return view('admin.transactions.claimConfirmation',$data);
    }
}