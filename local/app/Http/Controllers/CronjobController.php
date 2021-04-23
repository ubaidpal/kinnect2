<?php

namespace App\Http\Controllers;

use App\Classes\Kinnect2;
use App\StorageFile;
use App\Services\StorageManager;
use DB;
use kinnect2Store\Store\StoreOrder;


class CronjobController extends Controller
{

    public function __construct() {

    }
    public function deleteStorageFiles(){
        $date = date('Y-m-d',strtotime('-7 days'));
        $smObj = new StorageManager();
        StorageFile::where('is_temp',1)
                    ->whereRaw("DATE(created_at) < '$date'")
                    ->chunk(100,function($files) use (&$smObj){
                        foreach ($files as $file){
                            if($smObj->pathExists('photos/'.$file->storage_path)){
                                $smObj->deletFile('photos/'.$file->storage_path);
                            }
                            $file->delete();
                        }
                    });
    }
    public function transferSellerAmount(){
        $status = \Config::get('constants_brandstore.ORDER_STATUS.ORDER_DISPATCHED');
        $date = date('Y-m-d',strtotime('-14 days'));

        $saObj = new \kinnect2Store\Store\Repository\admin\StoreAdminRepository();
        $sadminObj = new \kinnect2Store\Store\Repository\admin\StoreAdminOrderRepository();
        StoreOrder::where('status',$status)
                    ->whereRaw("DATE(shiping_date) < '$date'")
                    ->whereNull('received_date')
                    ->where('is_deleted',0)
                    ->where('is_refunded',0)
                    ->chunk(100,function($orders) use (&$saObj,&$sadminObj){
                        foreach ($orders as $order){
                            $sale = \Config::get('constants_brandstore.STATEMENT_TYPES.SALE');
                            $saObj->updateStatement($sale,'store_order',$order->id,'credit','USD');
                            $fee = \Config::get('constants_brandstore.STATEMENT_TYPES.ORDER_SHIPPING_FEE');
                            $saObj->updateStatement($fee,'store_order',$order->id,'credit','USD');
                            $status = \Config::get('constants_brandstore.ORDER_STATUS.ORDER_DELIVERED');
                            $sadminObj->updateOrderStatus($order->id,$status,'system');
                        }
                    });
    }
}
