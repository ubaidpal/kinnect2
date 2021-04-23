<?php

namespace App\Http\Controllers\Admin;

use App\Repository\Eloquent\Admin\SuperAdminRepository;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use kinnect2Store\Store\StoreClaim;
use kinnect2Store\Store\StoreClaimFeeTransaction;
use kinnect2Store\Store\StoreDispute;
use kinnect2Store\Store\StoreOrder;
use kinnect2Store\Store\StoreProduct;
use kinnect2Store\Store\StoreReversal;
use kinnect2Store\Store\StoreTransaction;
use stdClass;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    protected $data;
    /**
     * @var SuperAdminRepository
     */
    private $superAdminRepository;

    /**
     * SuperAdminController constructor.
     */
    public function __construct(SuperAdminRepository $superAdminRepository) {
        $this->data                 = $data = new StdClass();
        $this->data->title          = "Dashboard - Super Admin";
        $this->superAdminRepository = $superAdminRepository;
        if(isset(\Auth::user()->id)) {
            $this->user_id = \Auth::user()->id;
            $this->user    = \Auth::user();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $sales[] = \Config::get('constants_brandstore.STATEMENT_TYPES.SALE');
        $sales[] = \Config::get('constants_brandstore.STATEMENT_TYPES.ORDER_SHIPPING_FEE');
        $sales[] =\Config::get('constants_brandstore.STATEMENT_TYPES.DISPUTE_PARTIAL_TRANSFER');
        $data['totalSaleSum']     = StoreTransaction::whereIn('type',$sales)->sum( 'amount' );
        $data['totalWithdrawSum'] = StoreTransaction::where('type',\Config::get('constants_brandstore.STATEMENT_TYPES.WITHDRAW'))->sum( 'amount' );

        $data['totalDisputeFees'] =  StoreClaimFeeTransaction::sum('amount');
        $withdrawal_fee = \Config::get('constants_brandstore.STATEMENT_TYPES.WITHDRAW_FEE');
        $data['withdrawalFees'] = StoreTransaction::where('type',$withdrawal_fee)->sum('amount');

        $data['totalReversals'] = StoreReversal::sum('amount');

        $data['totalBrandsCount'] = User::where( 'user_type', '=', \Config::get('constants.BRAND_USER') )->count();
        $data['totalConsumersCount'] = User::where( 'user_type', '=', \Config::get('constants.REGULAR_USER'))->count();
        $data['totalProductsCount'] = StoreProduct::count();

        // <editor-fold desc="Claims">
        $data['openClaimsCount']  = StoreClaim::
        where('status', '=', \Config::get('admin_constants.CLAIM_STATUS.NOT_ASSIGNED'))
            ->orWhere('status', '=', \Config::get('admin_constants.CLAIM_STATUS.ASSIGNED'))
            ->count();

        $data['resolvedClaimsCount']  = StoreClaim::
        where('status', '=', \Config::get('admin_constants.CLAIM_STATUS.RESOLVED'))
            ->count();
        // </editor-fold>

        // <editor-fold desc="Disputes">
        $data['openDisputeCount']  = StoreDispute::
        where('status', '!=', \Config::get('constants_brandstore.DISPUTE_STATUS.RESOLVED'))
            ->where('status', '!=', \Config::get('constants_brandstore.DISPUTE_STATUS.DISPUTE_CANCELLED_BUYER'))
            ->orWhere('status' , NULL)
            ->count();

        $data['acceptedDisputeCount']  = StoreDispute::
        where('status', \Config::get('constants_brandstore.DISPUTE_STATUS.DISPUTE_ACCEPTED'))
            ->count();

        $data['rejectedDisputeCount']  = StoreDispute::
        where('status', \Config::get('constants_brandstore.DISPUTE_STATUS.DISPUTE_CANCELLED_SELLER'))
            ->count();


        // </editor-fold>

        // <editor-fold desc="Top Ten Brands">

        $topTenBrands = StoreOrder::select('seller_id')->get();

        $topTenBrandsInfoIds = [];
        foreach($topTenBrands as $topTenBrandsOrders){
            if(isset($topTenBrandsInfo[$topTenBrandsOrders->seller_id])){
                $topTenBrandsInfoIds[$topTenBrandsOrders->seller_id] = $topTenBrandsInfoIds[$topTenBrandsOrders->seller_id] + 1;
            }else{
                $topTenBrandsInfoIds[$topTenBrandsOrders->seller_id] = 1;
            }
        }

        $data['topTenBrandsInfo'] = [];
        $topTenBrandsCount =0;

        foreach($topTenBrandsInfoIds as $key => $orderCount){
            $topTenBrandsCount++;

            if($topTenBrandsCount > 10){break;}

            $userInfo = getUserEmailAndUsername($key);

            if(isset($userInfo->email) AND isset($userInfo->username)){
                array_push($data['topTenBrandsInfo'], ucfirst($userInfo->displayname)."+_+".$userInfo->email);
            }
        }
        $data['topTenBrands'] = $data['topTenBrandsInfo'];
        // </editor-fold>

        return view( "admin.dashboard", $data )->with( 'title', 'Kinnect2 Store: Home' );
        /*$this->data->members_count = $this->superAdminRepository->members_count();
        $this->data->brands_count  = $this->superAdminRepository->type_count(\Config::get('constants.BRAND_USER'));
        $this->data->users_count   = $this->superAdminRepository->type_count(\Config::get('constants.REGULAR_USER'));
        $this->data->login_count   = $this->superAdminRepository->all_login();
        $this->data->today_login   = $this->superAdminRepository->today_login();
        echo '<tt><pre>';
        print_r($this->data);
        die;*/
    }

    public function flaggedPosts() {
        if(!$this->user->is('super.admin')){
            return redirect()->back();
        }
        $data['posts'] = $this->superAdminRepository->getFlaggedPosts();

        return \View::make('admin.super-user.flagged-posts', $data);
    }


    public function dismissReport($id) {
        if(!$this->user->is('super.admin')){
            return redirect()->back();
        }
        $this->superAdminRepository->updateReportStatus($id);
        return redirect()->back();
    }


    public function blockPost($id) {
        if(!$this->user->is('super.admin')){
            return redirect()->back();
        }

        $action_id = $this->superAdminRepository->updateReportStatus($id);
        $this->superAdminRepository->updatePostStatus($action_id);
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
    public function storeTransactions(){
        $data = [];
        $from = \Request::get('from');
        $to = \Request::get('to');
        $data['transaction_type'] = $transaction_type = \Request::get('transaction_type');

        if ($to) {
            $to = Carbon::parse($to)->format('Y-m-d H:i:s');
        } else {
            $to = Carbon::now();
        }

        if ($from) {
            $from = Carbon::parse($from)->format('Y-m-d H:i:s');
        } else {
            $from = Carbon::now()->subDay(30);
        }

        $data['to'] = $to;
        $data['from'] = $from;

        $query = StoreTransaction::where('created_at', '>', $from)
                                ->where('created_at', '<', $to)
                                ->with('user')
                                ->orderBy('id','DESC');
        if(!empty($transaction_type)){
            $query->where('transaction_type','like',$transaction_type);
        }

        $transactions = $query->paginate(20);

        $data['transactions'] = $transactions;
        return view( 'admin.transactions.storeTransactions', $data );
    }
}
