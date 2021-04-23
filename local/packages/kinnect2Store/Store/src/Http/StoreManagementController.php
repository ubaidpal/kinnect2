<?php

namespace kinnect2Store\Store\Http;

use App\Http\Controllers\Controller;
use App\Country;
use Auth;
use Illuminate\Http\Request;
use kinnect2Store\Store\StoreBankAccount;
use kinnect2Store\Store\StoreTransaction;
use kinnect2Store\Store\StoreWithdrawalMethod;
use kinnect2Store\Store\StoreWithdrawal;
use kinnect2Store\Store\Repository\StoreRepository;

class StoreManagementController extends Controller {

    protected $user_id = null;
    protected $StoreRepository = null;
    public function __construct()
    {
        $this->user_id = Auth::user()->id;
        $this->StoreRepository = new StoreRepository();

        if(Auth::user()->user_type != \Config::get('constants.BRAND_USER')){
            abort(404);
        }
    }

    public function getBankAccount()
	{
		$data = [];
        $data['url_user_id']  = $this->user_id;

        $method = StoreWithdrawalMethod::where(['seller_id' => $this->user_id,'method' => 'bank'])->first();

        $bank = StoreBankAccount::firstOrNew(['store_withdrawal_method_id' => @$method->id]);
        $data['bank'] = $bank;

		$countries = Country::lists('name','iso3');
		$data['countries'] = $countries;
		return view('Store::addBankAccount',$data);
	}
    public function addBankAccount(Request $request){
        $this->validate($request, [
            'account_title'       => 'required',
            'account_number'      =>  'required'
        ]);


        $swmObj = new StoreWithdrawalMethod();

        $sbObj = new StoreBankAccount();

        $method = $swmObj->firstOrNew(['seller_id' => $this->user_id,'method' => 'bank']);

        if(empty($method->id)){
            $method->seller_id = $this->user_id;
            $method->method = 'bank';
            $method->save();
        }

        $bank = $sbObj->firstOrNew(['store_withdrawal_method_id' => @$method->id]);

        $bank->store_withdrawal_method_id = $method->id;
        $bank->user_id = $this->user_id;
        $bank->account_title = $request->get('account_title');
        $bank->permanent_billing_address = $request->get('permanent_billing_address');
        $bank->temp_billing_address = $request->get('temp_billing_address');
        $bank->city = $request->get('city');
        $bank->state = $request->get('state');
        $bank->post_code = $request->get('post_code');
        $bank->country_code = $request->get('country_code');
        $bank->account_number = $request->get('account_number');
        $bank->iban_number	 = $request->get('iban_number');
        $bank->swift_code = $request->get('swift_code');
        $bank->bank_name = $request->get('bank_name');
        $bank->bank_branch_country_code = $request->get('bank_branch_country_code');
        $bank->bank_branch_city = $request->get('bank_branch_city');

        $bank->save();

        return redirect('store/withdrawals');
    }
    public function requestWithdrawal(){
        $data = [];
        $method = StoreWithdrawalMethod::where(['seller_id' => $this->user_id,'method' => 'bank'])
                                    ->select(['id'])
                                    ->first();

        $bank = StoreBankAccount::where(['store_withdrawal_method_id' => @$method->id])
                                ->select(['id'])
                                ->first();
        $data['bank'] = $bank;

        $data['available_balance'] = $this->StoreRepository->getAvailableBalance($this->user_id);
        $data['pending_amount'] = $this->StoreRepository->getPendingAmount($this->user_id);
        $data['fee_percentage'] = $this->StoreRepository->getKinnect2Fee();

        $pendings = StoreWithdrawal::where('seller_id',$this->user_id)
                                        ->where(function($q){
                                            $q->where('status','pending');
                                            $q->orwhere('status','processing');
                                        })
                                        ->get();

        if(!$pendings->isEmpty()) {
            foreach ($pendings as $item) {
                $method = StoreWithdrawalMethod::where('id', $item->withdrawal_method_id)
                                                        ->select(['method'])
                                                        ->first();
                $item->method = @$method->method;
            }
        }

        $data['pending_withdrawals'] = $pendings;
        $data['url_user_id'] = $this->user_id;
        return view('Store::store_management.requestWithdrawls',$data);
    }
    public function sendWithdrawalRequest(Request $request){

        $payment_type = \Input::get('payment_type');
        $pending_amount = $this->StoreRepository->getPendingAmount($this->user_id);
        if($payment_type == 'partial'){
            $amount = \Input::get('amount');
            $this->validate($request,[
                'amount' => 'required|max:'.($amount - $pending_amount)
            ]);
        }else{
            $amount = $this->StoreRepository->getAvailableBalance($this->user_id);
            $amount = $amount - $pending_amount;
        }
        $store_withdrawal_mehtod_id = $this->StoreRepository->getDefaultWithdrawalMethod($this->user_id);
        if(empty($store_withdrawal_mehtod_id)){
            redirect('store/withdrawals')->with('message', 'Please add withdrawal method first');
        }

        $sqrObj = new StoreWithdrawal();
        $sqrObj->amount = $amount;
        $sqrObj->seller_id = $this->user_id;
        $sqrObj->fee_percentage = $this->StoreRepository->getKinnect2Fee();
        $sqrObj->withdrawal_method_id = $store_withdrawal_mehtod_id;
        $sqrObj->type = $payment_type;
        $sqrObj->status = 'pending';
        $sqrObj->save();

        return redirect('store/withdrawals');

    }
    public function cancelWithdrawalRequest($withdrawal_id){

       $withdrawal = StoreWithdrawal::where('id',$withdrawal_id)->where('seller_id',$this->user_id)->first();
        if($withdrawal->status != 'pending' ){
            return \Redirect::back()->with('message','You can not cancel request in '.$withdrawal->status.' state');
        }
        if(!empty($withdrawal->id)){
            $withdrawal->status = 'canceled';
            $withdrawal->save();
        }
        return \Redirect::back();
    }
}
