<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 1/6/2016
 * Time: 9:31 PM
 */
namespace kinnect2Store\Store\Repository;

use DB;
use kinnect2Store\Store\StoreProductStat;
use LucaDegasperi\OAuth2Server\Authorizer;
use App\Facades\UrlFilter;
use App\StorageFile;
use App\AlbumPhoto;
use Carbon\Carbon;
use App\Album;
use App\User;
use Auth;


class StoreProductStatRepository
{
    protected $store;

    protected $data;
    protected $user_id;
    protected $is_api;

    /**
     *
     */
    public function __construct() {

        $this->is_api = UrlFilter::filter();
        if ($this->is_api) {
            $this->user_id = Authorizer::getResourceOwnerId();
            @$this->data->user = User::findOrNew($this->user_id);
        } else {
            if (Auth::check()) {
                @$this->data->user = Auth::user();
                $this->user_id = $this->data->user->id;
            }
        }

    }

    public function addProductStat($stat_type = '', $user_id = '', $user_type = '', $user_age = '', $user_gender = '', $user_region = '', $user_ip = '', $product_owner_id = '', $product_id = '') {

        $product_stat = StoreProductStat::create([
            'stat_type'        => $stat_type,
            'user_id'          => $user_id,
            'user_type'        => $user_type,
            'user_age'         => $user_age,
            'user_gender'      => $user_gender,
            'user_region'      => $user_region,
            'user_ip'          => $user_ip,
            'product_owner_id' => $product_owner_id,
            'product_id'       => $product_id,
        ]);

        return $product_stat->id;
    }

    public function getProductViewStatics($product_id, $product_owner_id) {
        //For views only
        if (\Input::has('start_date') && !empty(\Input::get('end_date'))) {
            $month = \Input::get('start_date');
           $les   = \Input::get('start_date');//Carbon::parse($month)->startOfMonth();
            $now   =\Input::get('end_date'). ' 23:59:59';//Carbon::parse($month)->endOfMonth();
        } else {
            $now = Carbon::now();
            $les = Carbon::now()->subDay(7);
        }


        $statistics_view = StoreProductStat::where('product_id', $product_id)
            ->where('product_owner_id', $product_owner_id)
            ->where('stat_type', "view")
            ->where('created_at', '>=', $les)
            ->where('created_at', '<=', $now)
            ->groupBy('date')
            ->orderBy('created_at', 'ASC')
            ->get(array(
                DB::raw('DATE (`created_at`) as date'),
                DB::raw('COUNT(*) as "count", created_at'),
            ));
        return $statistics_view;
    }

    public function getPageViewStatics($product_owner_id) {
        //For views only
        if (\Input::has('start_date') && !empty(\Input::get('end_date'))) {
            $month = \Input::get('start_date');
            $les   = \Input::get('start_date');//Carbon::parse($month)->startOfMonth();
            $now   =\Input::get('end_date'). ' 23:59:59';//Carbon::parse($month)->endOfMonth();
        } else {
            $now = Carbon::now();
            $les = Carbon::now()->subDay(7);
        }


        $statistics_view = StoreProductStat::where('product_owner_id', $product_owner_id)
            ->where('stat_type', "view")
            ->where('created_at', '>=', $les)
            ->where('created_at', '<=', $now)
            ->groupBy('date')
            ->orderBy('created_at', 'ASC')
            ->get(array(
                DB::raw('DATE (`created_at`) as date'),
                DB::raw('COUNT(*) as "count", created_at'),
            ));
        return $statistics_view;
    }

    public function getProductViewStaticsByRegion($product_id, $product_owner_id) {
        //For views only
        $statistics_views = StoreProductStat::where('product_id', $product_id)
            ->where('product_owner_id', $product_owner_id)
            ->where('user_region', "!=", 0)
            ->groupBy('user_region')
            ->orderBy('user_region', 'ASC');

        if (\Input::has('start_date') && !empty(\Input::get('end_date'))) {
            $month = \Input::get('start_date');
            $les   = \Input::get('start_date');//Carbon::parse($month)->startOfMonth();
            $now   =\Input::get('end_date'). ' 23:59:59';//Carbon::parse($month)->endOfMonth();
            $statistics_views = $statistics_views->where('created_at', '>=', $les)
                ->where('created_at', '<=', $now);
        }
        $statistics_views = $statistics_views->get(array(
            DB::raw('COUNT(*) as "count", created_at, user_region as region'),
        ));

        foreach ($statistics_views as $statistics_view) {
            $statistics_view->region = getRegionName($statistics_view->region);
        }

        return $statistics_views;
    }

    public function getPageViewStaticsByRegion($product_owner_id) {
        //For views only
        $statistics_views = StoreProductStat::where('product_owner_id', $product_owner_id)
            ->where('user_region', "!=", 0)
            ->groupBy('user_region')
            ->orderBy('user_region', 'ASC');

        if (\Input::has('start_date') && !empty(\Input::get('end_date'))) {
            $month = \Input::get('start_date');
            $les   = \Input::get('start_date');//Carbon::parse($month)->startOfMonth();
            $now   =\Input::get('end_date'). ' 23:59:59';//Carbon::parse($month)->endOfMonth();
            $statistics_views = $statistics_views->where('created_at', '>=', $les)
                ->where('created_at', '<=', $now);
        }
        $statistics_views = $statistics_views->get(array(
            DB::raw('COUNT(*) as "count", created_at, user_region as region'),
        ));

        foreach ($statistics_views as $statistics_view) {
            $statistics_view->region = getRegionName($statistics_view->region);
        }

        return $statistics_views;
    }

    public function getProductViewStaticsByAge($product_id, $product_owner_id) {
        //For views only
        $statistics_views = StoreProductStat::where('product_id', $product_id)->orderBy('created_at', 'DESC');
        if (\Input::has('start_date') && !empty(\Input::get('end_date'))) {
            $month = \Input::get('start_date');
            $les   = \Input::get('start_date');//Carbon::parse($month)->startOfMonth();
            $now   =\Input::get('end_date') . ' 23:59:59';//Carbon::parse($month)->endOfMonth();
            $statistics_views = $statistics_views->where('created_at', '>=', $les)
                ->where('created_at', '<=', $now);
        }
        $statistics_views = $statistics_views->get();

        $data['firstCountView']  = 0;
        $data['secondCountView'] = 0;
        $data['thirdCountView']  = 0;
        $data['fourthCountView'] = 0;
        $data['fifthCountView']  = 0;

        $data['maleCountView']   = 0;
        $data['femaleCountView'] = 0;

        foreach ($statistics_views as $statistic) {

            if ($statistic->user_gender == 1 || $statistic->user_gender == '') {
                $data['maleCountView'] = $data['maleCountView'] + 1;
            }

            if ($statistic->user_gender == 2) {
                $data['femaleCountView'] = $data['femaleCountView'] + 1;
            }

            if ($statistic->user_age > 10 AND $statistic->user_age < 25) {
                $data['firstCountView'] = $data['firstCountView'] + 1;
            }

            if ($statistic->user_age > 25 AND $statistic->user_age < 35) {
                $data['secondCountView'] = $data['secondCountView'] + 1;
            }

            if ($statistic->user_age > 35 AND $statistic->user_age < 45) {
                $data['thirdCountView'] = $data['thirdCountView'] + 1;
            }

            if ($statistic->user_age > 45 AND $statistic->user_age < 55) {
                $data['fourthCountView'] = $data['fourthCountView'] + 1;
            }

            if ($statistic->user_age >= 55) {
                $data['fifthCountView'] = $data['fifthCountView'] + 1;
            }
        }
        if (count($statistics_views) == 0) {
            $data['maleCountViewPercent']   = 0;
            $data['femaleCountViewPercent'] = 0;

            return $data;
        }
        $data['maleCountViewPercent']   = $data['maleCountView'] / count($statistics_views) * 100;
        $data['femaleCountViewPercent'] = 100 - $data['maleCountViewPercent'];

        return $data;
    }
    public function getPageViewStaticsByAge($product_owner_id) {
        //For views only
        $statistics_views = StoreProductStat::where('product_owner_id', $product_owner_id)->orderBy('created_at', 'DESC');
        if (\Input::has('start_date') && !empty(\Input::get('end_date'))) {
            $month = \Input::get('start_date');
            $les   = \Input::get('start_date');//Carbon::parse($month)->startOfMonth();
            $now   =\Input::get('end_date') . ' 23:59:59';//Carbon::parse($month)->endOfMonth();
            $statistics_views = $statistics_views->where('created_at', '>=', $les)
                ->where('created_at', '<=', $now);
        }
        $statistics_views = $statistics_views->get();

        $data['firstCountView']  = 0;
        $data['secondCountView'] = 0;
        $data['thirdCountView']  = 0;
        $data['fourthCountView'] = 0;
        $data['fifthCountView']  = 0;

        $data['maleCountView']   = 0;
        $data['femaleCountView'] = 0;

        foreach ($statistics_views as $statistic) {

            if ($statistic->user_gender == 1 || $statistic->user_gender == '') {
                $data['maleCountView'] = $data['maleCountView'] + 1;
            }

            if ($statistic->user_gender == 2) {
                $data['femaleCountView'] = $data['femaleCountView'] + 1;
            }

            if ($statistic->user_age > 10 AND $statistic->user_age < 25) {
                $data['firstCountView'] = $data['firstCountView'] + 1;
            }

            if ($statistic->user_age > 25 AND $statistic->user_age < 35) {
                $data['secondCountView'] = $data['secondCountView'] + 1;
            }

            if ($statistic->user_age > 35 AND $statistic->user_age < 45) {
                $data['thirdCountView'] = $data['thirdCountView'] + 1;
            }

            if ($statistic->user_age > 45 AND $statistic->user_age < 55) {
                $data['fourthCountView'] = $data['fourthCountView'] + 1;
            }

            if ($statistic->user_age >= 55) {
                $data['fifthCountView'] = $data['fifthCountView'] + 1;
            }
        }
        if (count($statistics_views) == 0) {
            $data['maleCountViewPercent']   = 0;
            $data['femaleCountViewPercent'] = 0;

            return $data;
        }
        $data['maleCountViewPercent']   = $data['maleCountView'] / count($statistics_views) * 100;
        $data['femaleCountViewPercent'] = 100 - $data['maleCountViewPercent'];

        return $data;
    }

    public function getProductViewStaticsByHour($product_id, $product_owner_id) {
        if (\Input::has('date') && !empty(\Input::get('date'))) {
            $date = \Input::get('date');

        }else{
            $date =  Carbon::today()->toDateString();
        }

        //For views only
        return $statistics_view = StoreProductStat::where('product_id', $product_id)
            ->where('product_owner_id', $product_owner_id)
            ->where('stat_type', "view")
            ->whereDate('created_at', '=',$date)
            ->groupBy('hour')
            ->orderBy('hour', 'ASC')
            ->get(array(
                DB::raw('HOUR (`created_at`) as hour'),
                DB::raw('COUNT(*) as "count", created_at'),
            ));
    }

    public function getPageViewStaticsByHour($product_owner_id) {
        if (\Input::has('date') && !empty(\Input::get('date'))) {
            $date = \Input::get('date');

        }else{
            $date =  Carbon::today()->toDateString();
        }

        //For views only
        return $statistics_view = StoreProductStat::where('product_owner_id', $product_owner_id)
            ->where('stat_type', "view")
            ->whereDate('created_at', '=',$date)
            ->groupBy('hour')
            ->orderBy('hour', 'ASC')
            ->get(array(
                DB::raw('HOUR (`created_at`) as hour'),
                DB::raw('COUNT(*) as "count", created_at'),
            ));
    }

}
