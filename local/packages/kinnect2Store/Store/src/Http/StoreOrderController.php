<?php

namespace kinnect2Store\Store\Http;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use app\Http\Requests;
use App\StorageFile;
use App\User;
use Illuminate\Support\Facades\Auth;
use kinnect2Store\Store\Repository\DisputeRepository;
use kinnect2Store\Store\Repository\StoreOrderRepository;
use kinnect2Store\Store\StoreProduct;
use Session;

class StoreOrderController extends Controller {
	protected $storeRepository;
	protected $storeOrderRepository;
	protected  $user_id = null;
	/**
	 * @var DisputeRepository
	 */
	private $disputeRepository;

	/**
	 * @param \kinnect2Store\Store\Repository\StoreRepository      $storeRepository
	 * @param Request                                              $middleware
	 */
	public function __construct(
		\kinnect2Store\Store\Repository\StoreOrderRepository $storeOrderRepository,
		\kinnect2Store\Store\Repository\StoreRepository $storeRepository, Request $middleware,
	DisputeRepository $disputeRepository
	) {
		$this->storeRepository      = $storeRepository;
		$this->storeOrderRepository      = $storeOrderRepository;
		/* $this->user_id = $middleware['middleware']['user_id'];
		 @$this->data->user = $middleware['middleware']['user'];
		 $this->is_api = $middleware['middleware']['is_api'];*/
		$this->disputeRepository = $disputeRepository;
		$this->user_id = Auth::user()->id;
	}

	public function getMyOrders(  ) {
		$data['url_user_id']       = Auth::user()->id;
		$status = \Request::get('status');
		$data['status'] = $status;
		$order_status = null;
		if(!empty($status) && $status != 'All') {
			if($status == 'ORDER_DISPUTED'){
				$order_status[] = \Config::get('constants_brandstore.ORDER_STATUS.ORDER_DISPUTED');
				$order_status[] = \Config::get('constants_brandstore.ORDER_STATUS.ORDER_DISPUTED_CANCELLED');
				$order_status[] = \Config::get('constants_brandstore.ORDER_STATUS.ORDER_DISPUTED_REJECTED');
				$order_status[] = \Config::get('constants_brandstore.ORDER_STATUS.ORDER_DISPUTE_ACCEPTED');
				$order_status[] = \Config::get('constants_brandstore.ORDER_STATUS.ORDER_DELIVERED');
			}else {
				$order_status[] = \Config::get('constants_brandstore.ORDER_STATUS.' . $status);
			}
		}
		$data['allOrders'] = $this->storeOrderRepository->paginateUserOrders($this->user_id,$order_status);
		
		$data['countOrdersStatusWise'] = $this->storeOrderRepository->countOrdersStatusWise($this->user_id);
		//$data['countRequestToRevise'] = $this->storeOrderRepository->countRequestToReviseCurrentUser();

		return view('Store::orders.myOrders', $data);
	}

	public function getOrderDispute($id) {
		 $hasDispute = $this->disputeRepository->has_dispute($id);
		if($hasDispute){
			return redirect('store/dispute/detail/'.$hasDispute);
		}
		$data['url_user_id']       = Auth::user()->id;
		$data['order']           = $this->storeOrderRepository->getOrder($id);
		$data['deliveryInfo'] = $this->storeOrderRepository->getOrderDeliveryInfo($id);

		$data['countRequestToRevise'] = $this->storeOrderRepository->countRequestToReviseCurrentUser();
		$data['payment_received'] = $this->storeOrderRepository->paymentReceivedInfo();
		$storeRepo = new StoreOrderRepository();
		$data['products'] = $storeRepo->getOrderAllProducts($id);

		$data['order_id'] = $id;
		return view('Store::orders.dispute', $data);

	}


	public function getOrderMangerPanel(  ) {
		$data['url_user_id']       = Auth::user()->id;
		$data['allOrders'] = $this->storeOrderRepository->getAllOrdersCurrentUser();
		$data['countRequestToRevise'] = $this->storeOrderRepository->countRequestToReviseCurrentUser();
		return view('Store::orders.storeManagerPanelOrders', $data);
	}
}
