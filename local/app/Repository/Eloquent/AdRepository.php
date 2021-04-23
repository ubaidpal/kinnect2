<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/16/2015
 * Time: 6:27 PM
 */

namespace App\Repository\Eloquent;
use App;
use App\AdCampaign;
use App\AdStatistics;
use App\AdTargets;
use App\AdTargetsCountry;
use App\AdUserAd;
use App\AlbumPhoto;
use App\Classes\UrlFilter;
use App\Ad;

use App\Services\StorageManager;
use App\StorageFile;
use App\User;
use Auth;
use Carbon\Carbon;
use Config;
use DB;
use Intervention\Image\Facades\Image;
use Kinnect2;
use Omnipay\Omnipay;

class AdRepository extends Repository
{
    protected $ad;
    private $activity_type;

    protected $data;
    protected $user_id;
    protected $is_api;

    public function __construct(Ad $ad)
    {
        parent::__construct();
        $this->ad = $ad;
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

//        $this->activity_type = \Config::get('constants_activity.OBJECT_TYPES.EVENT.NAME');
    }

    public function getUserCampaignStatistics($request)
    {
        $userCampaignsIds = AdCampaign::where('owner_id', $this->user_id)->lists('id');

        $chunk  = $request->chunk;
        $period = $request->period;

        $year  = Carbon::now()->addYear(-1)->toDateTimeString();
        $month = Carbon::now()->addMonth(-1)->toDateTimeString();
        $week  = Carbon::now()->addWeek(-1)->toDateTimeString();

        $data['chunk'] = '';
        if($chunk == 'dd'){$chunkSql = "DAY";}
        if($chunk == 'ww'){$chunkSql = "WEEK";}
        if($chunk == 'MM'){$chunkSql = "MONTH";}
        if($chunk == 'y'){$chunkSql = "YEAR"; }

        $data['chunk'] = $chunkSql;

        $data['period'] = '';
        if($period == 'ww'){ $filter_type  = $week;}
        if($period == 'MM'){ $filter_type  = $month;}
        if($period == 'y'){ $filter_type   = $year;}

        $data['period'] = $period;

        $type   = $request->type;
        $view   = '0';
        $click  = '0';
        $ctr    = '0';

        if($type == 'view'){$view = "1";}
        if($type == 'click'){$click = "1";}
        if($type == 'CTR'){$ctr = "1";}

        $labels_views        = '';
        $labels_clicks        = '';
        $values_views  = '';
        $values_clicks  = '';
        $values_ctr    = '';

        $data['views'] = '0';
        $data['clicks'] = '0';

        if($type == 'all' || $type == 'view')
        {
            $data['views'] = '1';

            //For Views only
            $statistics_view = AdStatistics::where('created_at', '>' , $filter_type)
                ->whereIn('ad_campaign_id', $userCampaignsIds)
                ->where('value_view', 1)
                ->groupBy('date')
                ->orderBy('date', 'DESC')
                ->get(array(
                    DB::raw($chunkSql.'(`created_at`) as date'),
                    DB::raw('COUNT(*) as "count", value_view')
                ));

            foreach($statistics_view as $statistic):

                $labels_views  .= $statistic->date.' ,';
                $values_views  .= $statistic->count.' ,';
            endforeach;

        }//End for views stats

        if($type == 'all' || $type == 'click')
        {
            $data['clicks'] = '1';
            //For Clicks only
            $statistics_view = AdStatistics::where('created_at', '>' , $filter_type)
                ->where('ad_campaign_id', $request->campaign_id)
                ->where('value_click', 1)
                ->groupBy('date')
                ->orderBy('date', 'DESC')
                ->get(array(
                    DB::raw($chunkSql.'(`created_at`) as date'),
                    DB::raw('COUNT(*) as "count", value_click')
                ));

            foreach($statistics_view as $statistic):
                $labels_clicks  .= $statistic->date.' ,';
                $values_clicks  .= $statistic->count.' ,';
            endforeach;
            //End for views stats
        }

        $data['labels_clicks'] = $labels_clicks;
        $data['labels_views']  = $labels_views;

        $data['values_clicks'] = $values_clicks;
        $data['values_views']  = $values_views;

        return $data;

//        dd($request->mode . ' > '.$request->type. " > ". $request->period." > ". $request->chunk);
    }

    public function customStatisticsReport($request)
    {
        $chunkSql                = '';
        $statistics_view_header['summarize_by']     = $summarize_by     = $request->summarize_by;   // ads, campaigns
        $statistics_view_header['filter_by']        = $filter_by        = $request->filter;      // no-filter, campaign, ads
        $statistics_view_header['filter_by_time']   = $filter_by_time   = $request->filter_by_time; // daily, week

        $statistics_view_header['start_date']       = $start_date       = $request->start_date;     //last month

        $start_date = explode('-', $start_date);
        $start_date = Carbon::createFromDate($start_date[0], $start_date[1], $start_date[2]);

        $statistics_view_header['end_date'] = $end_date   = $request->end_date;       //now

        $end_date   = explode('-', $end_date);
        $end_date   = Carbon::createFromDate($end_date[0], $end_date[1], $end_date[2]);

        $report_destination_type = $request->report_destination_type; //html, xls

        if($filter_by_time == 'daily')  { $chunkSql = "DAY";}
        if($filter_by_time == 'monthly'){ $chunkSql = "MONTH";}

        $userCampaignsIds = false;
        $UserAdsIds       = false;

        $whereInField = 'user_ad_id';
        $filter_by    = null;

        if(isset($request->campaign_list))
        {
            $whereInField = 'ad_campaign_id';
            $filter_by = $request->campaign_list;
        }

        if(isset($request->ad_list))
        {
            $whereInField = 'user_ad_id';
            $filter_by = $request->ad_list;
        }

        if($filter_by == null AND $summarize_by == 'ads'){
            $filter_by = AdUserAd::where('user_id', $this->user_id)->lists('id');
        }

        if($filter_by == null AND $summarize_by == 'campaigns'){
            $filter_by = AdCampaign::where('owner_id', $this->user_id)->lists('id');
            $whereInField = 'ad_campaign_id';
        }

        if($summarize_by == 'ads'){
            $group_by = 'user_ad_id';
        }else{
            $group_by = 'ad_campaign_id';
        }
        $statistics_view = AdStatistics::whereBetween('created_at', array($start_date, $end_date))
            ->whereIn($whereInField, $filter_by)
            ->groupBy($group_by)
            ->orderBy('date', 'DESC')
            ->get(array(
                DB::raw($chunkSql.'(`created_at`) as date, created_at, ad_campaign_id as campaign_id, user_ad_id as ad_id'),
                DB::raw('COUNT(*) as "count", value_view', 'value_click')
        ));

        //For views
        $countTotalViews =  AdStatistics::whereBetween('created_at', array($start_date, $end_date))
            ->whereIn($whereInField, $filter_by)
            ->where('value_view', 1)
            ->count();

        //For clicks
        $countTotalClicks =  AdStatistics::whereBetween('created_at', array($start_date, $end_date))
            ->whereIn($whereInField, $filter_by)
            ->where('value_click', 1)
            ->count();

        return ($report_destination_type == 'html')?$this->reportToHtml($statistics_view, $countTotalClicks, $countTotalViews):$this->reportToXls($statistics_view, $statistics_view_header, $countTotalClicks, $countTotalViews);
    }

    public function reportToXls($statistics_views, $statistics_view_header, $countTotalClicks, $countTotalViews)
    {

        $data['stat_header']  = $statistics_view_header;
        $data['statistics_views']  = $statistics_views;
        $data['totalClicks'] = $countTotalClicks;
        $data['totalViews']  = $countTotalViews;

//        foreach($statistics_views as $statistic):
//            if($statistic->value_click == 1)
//            {
//                $data['totalClicks'] = $data['totalClicks'] + $statistic->count;
//            }
//
//            if($statistic->value_view == 1)
//            {
//                $data['totalViews'] = $data['totalViews'] + $statistic->count;
//            }
//        endforeach;// calculated views/clicks

        //excel sheet
        $excel = App::make('excel');
        $excel->create('File Name', function($excel) use($data){
            $excel->sheet('Ads Report', function($sheet) use($data){

                $sheet->setBorder('A1:G1', 'thin');

                $sheet->cells('A4:G4', function($cells){
                    $cells->setBackground('#ee4b08');
                    $cells->setFontColor('#FFFFFF');
                });

                $data['totalViews_ctr'] = ($data['totalViews'] == 0)? 1: $data['totalViews'];
                $data['totalClicks_ctr'] = ($data['totalClicks'] == 0)? 1: $data['totalClicks'];
                $ctr = round(($data['totalClicks_ctr'] / $data['totalViews_ctr']) * 100, 2);


                $excelData = [];
                array_push($excelData, array('Summarize By:', 'Time Summary', 'Duration', 'Total Views', 'Total Clicks', 'CTR(%)'));
                array_push($excelData, array($data['stat_header']['summarize_by'], $data['stat_header']['filter_by_time'], $data['stat_header']['start_date'].' To '.$data['stat_header']['end_date'], $data['totalViews'], $data['totalClicks'], $ctr.'(%)'));

                array_push($excelData, array('', '', '', '', '', ''));

                array_push($excelData, array('Date', 'Campaign Name', 'Countries Views', 'Views', 'Countries Clicks', 'Clicks', 'CTR(%)'));

                //Adding excel sheet data
                foreach($data['statistics_views'] as $statistic):
                    $currentCampaign = Kinnect2::getCampaign($statistic->campaign_id);

                    $currentCampaignStats = Kinnect2::getCampaignStatistics($statistic->created_at, $statistic->campaign_id);
                    $clickedCountries = array_count_values(explode(', ', $currentCampaignStats['view_country_list']));

                    if(count($clickedCountries) > 1)
                    {
                        foreach($clickedCountries as $key => $clickedCountry)
                        {
                            if($key > 0)
                            {
                                $country_views = Kinnect2::getCountryName($key).': '.$clickedCountry.' Views,';
                            }

                        }
                    }       else{
                        $country_views = 'N/A';
                    }

                    $currentCampaignStats = Kinnect2::getCampaignStatistics($statistic->created_at, $statistic->campaign_id);
                    $clickedCountries = array_count_values(explode(', ', $currentCampaignStats['click_country_list']));
                    if(count($clickedCountries) > 1)
                    {
                        foreach($clickedCountries as $key => $clickedCountry)
                        {
                            if($key > 0)
                            {
                                $country_clicks = Kinnect2::getCountryName($key).': '.$clickedCountry.'Clicks,';
                            }

                        }
                    }       else{
                        $country_clicks = 'N/A';
                    }

                    $currentCampaignStats['camp_total_views_ctr'] = ($currentCampaignStats['camp_total_views'] == 0)? 1: $currentCampaignStats['camp_total_views'];
                    $currentCampaignStats['camp_total_clicks_ctr'] = ($currentCampaignStats['camp_total_clicks'] == 0)? 1: $currentCampaignStats['camp_total_clicks'];
                    $ctr = round(($currentCampaignStats['camp_total_clicks_ctr'] / $currentCampaignStats['camp_total_views_ctr']) * 100, 2);

//                    array_push($data, array('Date', 'Campaign Name', 'Countries Views', 'Views', 'Countries Clicks', 'Clicks', 'CTR(%)'));

                    array_push($excelData, array($statistic->created_at, $currentCampaign->name, $country_views, $currentCampaignStats['camp_total_views'],$country_clicks , $currentCampaignStats['camp_total_clicks'], $ctr.'(%)'));
                endforeach;
                //End of Adding sheet data
                $sheet->fromArray($excelData, null, 'A1', false, false);
            });
        })->download('xls');

        return $data;
    }

    public function reportToHtml($statistics_views, $countTotalClicks, $countTotalViews)
    {

        $data['statistics_views']  = $statistics_views;

        $data['totalClicks'] = $countTotalClicks;
        $data['totalViews']  = $countTotalViews;

//        foreach($statistics_views as $statistic):
//            if($statistic->value_click == 1)
//            {
//                $data['totalClicks'] = $data['totalClicks'] + $statistic->count;
//            }
//
//            if($statistic->value_view == 1)
//            {
//                $data['totalViews'] = $data['totalViews'] + $statistic->count;
//            }
//        endforeach;// calculated views/clicks

        return $data;
    }

    public function getAdDefaultStatistics($ad)
    {
        $week  = Carbon::now()->addWeek(-1)->toDateTimeString();
        $chunkSql = "DAY";
        $data['chunk'] = $chunkSql;

        $filter_type  = $week;
        $data['period'] = 'ww';

        $type   = 'view';
        $view   = '0';
        $click  = '0';
        $ctr    = '0';

        if($type == 'view'){$view = "1";}
        if($type == 'click'){$click = "1";}
        if($type == 'CTR'){$ctr = "1";}

        $labels_views        = '';
        $labels_clicks        = '';
        $values_views  = '';
        $values_clicks  = '';
        $values_ctr    = '';

        $data['views'] = '0';
        $data['clicks'] = '0';

        if($type == 'all' || $type == 'view')
        {
            $data['views'] = '1';

            //For Views only
            $statistics_view = AdStatistics::where('created_at', '>' , $filter_type)
                ->where('ad_campaign_id', $ad->id)
                ->where('value_view', 1)
                ->groupBy('date')
                ->orderBy('date', 'DESC')
                ->get(array(
                    DB::raw($chunkSql.'(`created_at`) as date'),
                    DB::raw('COUNT(*) as "count", value_view')
                ));

            foreach($statistics_view as $statistic):

                $labels_views  .= $statistic->date.' ,';
                $values_views  .= $statistic->count.' ,';
            endforeach;

        }//End for views stats

        $data['labels_clicks'] = $labels_clicks;
        $data['labels_views']  = $labels_views;

        $data['values_clicks'] = $values_clicks;
        $data['values_views']  = $values_views;

        return $data;

//        dd($request->mode . ' > '.$request->type. " > ". $request->period." > ". $request->chunk);

    }

    public function getCampaignStatistics($request)
    {
        $chunk  = $request->chunk;
        $period = $request->period;

        $year  = Carbon::now()->addYear(-1)->toDateTimeString();
        $month = Carbon::now()->addMonth(-1)->toDateTimeString();
        $week  = Carbon::now()->addWeek(-1)->toDateTimeString();

        $data['chunk'] = '';
        if($chunk == 'dd'){$chunkSql = "DAY";}
        if($chunk == 'ww'){$chunkSql = "WEEK";}
        if($chunk == 'MM'){$chunkSql = "MONTH";}
        if($chunk == 'y'){$chunkSql = "YEAR"; }

        $data['chunk'] = $chunkSql;

        $data['period'] = '';
        if($period == 'ww'){ $filter_type  = $week;}
        if($period == 'MM'){ $filter_type  = $month;}
        if($period == 'y'){ $filter_type   = $year;}

        $data['period'] = $period;

        $type   = $request->type;
        $view   = '0';
        $click  = '0';
        $ctr    = '0';

        if($type == 'view'){$view = "1";}
        if($type == 'click'){$click = "1";}
        if($type == 'CTR'){$ctr = "1";}

        $labels_views        = '';
        $labels_clicks        = '';
        $values_views  = '';
        $values_clicks  = '';
        $values_ctr    = '';

        $data['views'] = '0';
        $data['clicks'] = '0';

        if($type == 'all' || $type == 'view')
        {
            $data['views'] = '1';

            //For Views only
            $statistics_view = AdStatistics::where('created_at', '>' , $filter_type)
                ->where('ad_campaign_id', $request->campaign_id)
                ->where('value_view', 1)
                ->groupBy('date')
                ->orderBy('date', 'DESC')
                ->get(array(
                    DB::raw($chunkSql.'(`created_at`) as date'),
                    DB::raw('COUNT(*) as "count", value_view')
                ));

            foreach($statistics_view as $statistic):

                $labels_views  .= $statistic->date.' ,';
                $values_views  .= $statistic->count.' ,';
            endforeach;

        }//End for views stats

        if($type == 'all' || $type == 'click')
        {
            $data['clicks'] = '1';
            //For Clicks only
            $statistics_view = AdStatistics::where('created_at', '>' , $filter_type)
                ->where('ad_campaign_id', $request->campaign_id)
                ->where('value_click', 1)
                ->groupBy('date')
                ->orderBy('date', 'DESC')
                ->get(array(
                    DB::raw($chunkSql.'(`created_at`) as date'),
                    DB::raw('COUNT(*) as "count", value_click')
                ));

            foreach($statistics_view as $statistic):
                $labels_clicks  .= $statistic->date.' ,';
                $values_clicks  .= $statistic->count.' ,';
            endforeach;
            //End for views stats
        }

        $data['labels_clicks'] = $labels_clicks;
        $data['labels_views']  = $labels_views;

        $data['values_clicks'] = $values_clicks;
        $data['values_views']  = $values_views;

        return $data;

//        dd($request->mode . ' > '.$request->type. " > ". $request->period." > ". $request->chunk);
    }

    public function getAdStatistics($request)
    {
        $chunk  = $request->chunk;
        $period = $request->period;

        $year  = Carbon::now()->addYear(-1)->toDateTimeString();
        $month = Carbon::now()->addMonth(-1)->toDateTimeString();
        $week  = Carbon::now()->addWeek(-1)->toDateTimeString();

        $data['chunk'] = '';
        if($chunk == 'dd'){$chunkSql = "DAY";}
        if($chunk == 'ww'){$chunkSql = "WEEK";}
        if($chunk == 'MM'){$chunkSql = "MONTH";}
        if($chunk == 'y'){$chunkSql = "YEAR"; }

        $data['chunk'] = $chunkSql;

        $data['period'] = '';
        if($period == 'ww'){ $filter_type  = $week;}
        if($period == 'MM'){ $filter_type  = $month;}
        if($period == 'y'){ $filter_type   = $year;}

        $data['period'] = $period;

        $type   = $request->type;
        $view   = '0';
        $click  = '0';
        $ctr    = '0';

        if($type == 'view'){$view = "1";}
        if($type == 'click'){$click = "1";}
        if($type == 'CTR'){$ctr = "1";}

        $labels_views        = '';
        $labels_clicks        = '';
        $values_views  = '';
        $values_clicks  = '';
        $values_ctr    = '';

        $data['views'] = '0';
        $data['clicks'] = '0';

        if($type == 'all' || $type == 'view')
        {
            $data['views'] = '1';

            //For Views only
            $statistics_view = AdStatistics::where('created_at', '>' , $filter_type)
                ->where('user_ad_id', $request->ad_id)
                ->where('value_view', 1)
                ->groupBy('date')
                ->orderBy('date', 'DESC')
                ->get(array(
                    DB::raw($chunkSql.'(`created_at`) as date'),
                    DB::raw('COUNT(*) as "count", value_view')
                ));

            foreach($statistics_view as $statistic):

                $labels_views  .= $statistic->date.' ,';
                $values_views  .= $statistic->count.' ,';
            endforeach;

        }//End for views stats

        if($type == 'all' || $type == 'click')
        {
            $data['clicks'] = '1';
            //For Clicks only
            $statistics_view = AdStatistics::where('created_at', '>' , $filter_type)
                ->where('user_ad_id', $request->ad_id)
                ->where('value_click', 1)
                ->groupBy('date')
                ->orderBy('date', 'DESC')
                ->get(array(
                    DB::raw($chunkSql.'(`created_at`) as date'),
                    DB::raw('COUNT(*) as "count", value_click')
                ));

            foreach($statistics_view as $statistic):
                $labels_clicks  .= $statistic->date.' ,';
                $values_clicks  .= $statistic->count.' ,';
            endforeach;
            //End for views stats
        }

        $data['labels_clicks'] = $labels_clicks;
        $data['labels_views']  = $labels_views;

        $data['values_clicks'] = $values_clicks;
        $data['values_views']  = $values_views;

        return $data;

//        dd($request->mode . ' > '.$request->type. " > ". $request->period." > ". $request->chunk);
    }

    public function getUserCampaignDefaultStatistics()
    {
        $userCampaignsIds = AdCampaign::where('owner_id', $this->user_id)->lists('id');

            $week  = Carbon::now()->addWeek(-1)->toDateTimeString();
            $chunkSql = "DAY";
            $data['chunk'] = $chunkSql;

            $filter_type  = $week;
            $data['period'] = 'ww';

            $type   = 'view';
            $view   = '0';
            $click  = '0';
            $ctr    = '0';

            if($type == 'view'){$view = "1";}
            if($type == 'click'){$click = "1";}
            if($type == 'CTR'){$ctr = "1";}

            $labels_views        = '';
            $labels_clicks        = '';
            $values_views  = '';
            $values_clicks  = '';
            $values_ctr    = '';

            $data['views'] = '0';
            $data['clicks'] = '0';

            if($type == 'all' || $type == 'view')
            {
                $data['views'] = '1';

                //For Views only
                $statistics_view = AdStatistics::where('created_at', '>' , $filter_type)
                    ->whereIn('ad_campaign_id', $userCampaignsIds)
                    ->where('value_view', 1)
                    ->groupBy('date')
                    ->orderBy('date', 'DESC')
                    ->get(array(
                        DB::raw($chunkSql.'(`created_at`) as date'),
                        DB::raw('COUNT(*) as "count", value_view')
                    ));

                foreach($statistics_view as $statistic):

                    $labels_views  .= $statistic->date.' ,';
                    $values_views  .= $statistic->count.' ,';
                endforeach;

            }//End for views stats

            $data['labels_clicks'] = $labels_clicks;
            $data['labels_views']  = $labels_views;

            $data['values_clicks'] = $values_clicks;
            $data['values_views']  = $values_views;

            return $data;

//        dd($request->mode . ' > '.$request->type. " > ". $request->period." > ". $request->chunk);

    }

    public function getCampaignDefaultStatistics($campaign_id)
    {
        $week  = Carbon::now()->addWeek(-1)->toDateTimeString();
        $chunkSql = "DAY";
        $data['chunk'] = $chunkSql;

        $filter_type  = $week;
        $data['period'] = 'ww';

        $type   = 'view';
        $view   = '0';
        $click  = '0';
        $ctr    = '0';

        if($type == 'view'){$view = "1";}
        if($type == 'click'){$click = "1";}
        if($type == 'CTR'){$ctr = "1";}

        $labels_views        = '';
        $labels_clicks        = '';
        $values_views  = '';
        $values_clicks  = '';
        $values_ctr    = '';

        $data['views'] = '0';
        $data['clicks'] = '0';

        if($type == 'all' || $type == 'view')
        {
            $data['views'] = '1';

            //For Views only
            $statistics_view = AdStatistics::where('created_at', '>' , $filter_type)
                ->where('ad_campaign_id', $campaign_id)
                ->where('value_view', 1)
                ->groupBy('date')
                ->orderBy('date', 'DESC')
                ->get(array(
                    DB::raw($chunkSql.'(`created_at`) as date'),
                    DB::raw('COUNT(*) as "count", value_view')
                ));

            foreach($statistics_view as $statistic):

                $labels_views  .= $statistic->date.' ,';
                $values_views  .= $statistic->count.' ,';
            endforeach;

        }//End for views stats

        $data['labels_clicks'] = $labels_clicks;
        $data['labels_views']  = $labels_views;

        $data['values_clicks'] = $values_clicks;
        $data['values_views']  = $values_views;

        return $data;

//        dd($request->mode . ' > '.$request->type. " > ". $request->period." > ". $request->chunk);
    }

    public function getCampaign($campaign_id)
    {
       return  AdCampaign::find($campaign_id);
    }

    public function getAd($ad_id)
    {
        return  AdUserAd::find($ad_id);
    }

    public function getUserAds()
    {
        return  AdUserAd::where(['user_id' => $this->user_id])->get();
    }

    public function getCampaignAds($campaign_id)
    {
        return  AdUserAd::where(['campaign_id' => $campaign_id])->where(['user_id' => $this->user_id])->orderBy('id', 'DESC')->get();
    }

    public function updateAd($requestFormData, $saved_ad_image_file_id)
    {
        if($requestFormData->campaign_id > 0)
        {
            $campaign = AdCampaign::find($requestFormData->campaign_id);
        }
        else
        {
            $campaign = new AdCampaign;

            $campaign->name     = $requestFormData->campaign_name;
            $campaign->status   = 0;
            $campaign->owner_id = $this->user_id;
            $campaign->save();
        }

        $package = Ad::find($requestFormData->package_id);

        $userAd   = AdUserAd::find($requestFormData->ad_id);

        $userAd->user_id            = $this->user_id;
        $userAd->ad_type            = '';
        $userAd->package_id         = $package->id;
        $userAd->campaign_id        = $campaign->id;
        $userAd->cads_url           = $requestFormData->cads_url;
        $userAd->cads_title         = $requestFormData->name;
        $userAd->cads_body          = $requestFormData->cads_body;
        $userAd->owner_id           = $this->user_id;
        $userAd->gateway_order_id   = '';
//        $userAd->cads_start_date    = $starttime;
//        $userAd->cads_end_date      = $endtime ;
        $userAd->sponsored          = '';
        $userAd->featured           = '0';
        $userAd->like               = '0';
        $userAd->resourece_type     = 'user';
        $userAd->resourece_id       = $this->user_id;
        /* $userAd->public             = '1';
         $userAd->approved           = '0';
         $userAd->enable             = '0';
         $userAd->status             = '0';
         $userAd->payment_status     = '0';
         $userAd->declined           = '0';
         $userAd->approve_date       = '0000-00-00 00:00:00';
         $userAd->price_model        = $package->price_model;
         $userAd->limit_click        = $package->model_detail;
         $userAd->limit_view         = $package->model_detail;
         $userAd->limit_like         = $package->model_detail;
         $userAd->count_view         = '0';
         $userAd->count_like         = '0';
         $userAd->expiry_date        = '0000-00-00 00:00:00';
         $userAd->weight             = '0';
         $userAd->min_ctr            = '0';
         $userAd->gateway_id         = '';
         $userAd->gateway_profile_id = '';
         $userAd->renew_by_admin_date= '0000-00-00 00:00:00';
         $userAd->profile            = '';
         $userAd->story_type         = '';*/

        $userAd->update();

        if($saved_ad_image_file_id > 0)
        {
            $file = StorageFile::where('file_id', $saved_ad_image_file_id)->first();
            if(isset($file->file_id) ) {
                if ( file_exists( "local/storage/app/photos/" . $file->storage_path ) == true ) {
                    $file_name     = time() . rand( 111111111, 9999999999 );
                    $folder_path   = "local/storage/app/photos/" . $userAd->owner_id;
                    $file_name_new = $userAd->owner_id . "_" . $file_name . "." . $file->extension;
                    if ( ! file_exists( $folder_path ) ) {
                        if ( ! mkdir( $folder_path, 0777, true ) ) {
                            $folder_path = '';
                        }
                    }
                    rename( "local/storage/app/photos/" . $file->storage_path, $folder_path . "/" . $file_name_new );
                }

                $file->parent_id = $saved_ad_image_file_id;//photo_id
                $file->user_id = $userAd->owner_id;
                $file->storage_path = $userAd->owner_id."/".$file_name_new;
                $file->name = $file_name;
//                $file->mime_major = 'image';
                $file->save();

                $userAd->photo_id = $saved_ad_image_file_id;
                $userAd->save();
            }
        }

        return $userAd->id;
    }

    public function updateCampaign($request)
    {
        $campaign = AdCampaign::find($request->campaign_id);

        if ($campaign)
        {
            $campaign->name = $request->name;
            $campaign->save();
            return $campaign->id;
        }
        return 0;
    }

    public function deleteCampaign($campaign_id)
    {
        $campaign = AdCampaign::find($campaign_id);

        if($campaign->owner_id == $this->user_id)
        {
            AdStatistics::where('ad_campaign_id', $campaign_id)->delete();
            AdUserAd::where('campaign_id', $campaign_id)->delete();

            return $campaign->delete();
        }
        return 0;
    }

    public function deleteAd($ad_id)
    {
        $ad = AdUserAd::find($ad_id);
        $campaign_id = $ad->campaign_id;

        if($ad->user_id == $this->user_id)
        {
            AdStatistics::where('ad_campaign_id', $ad->campaign_id)->delete();
            $ad->delete();
            return $campaign_id;
        }
        return 0;
    }

    public function userCampaignsList($owner_id)
    {
        $campaigns     = ['0'=>'Create new campaign'];
        $userCampaigns = DB::table('ad_campaigns')->where(['owner_id' => $owner_id])->lists('name', 'id');
        $userCampaigns = $campaigns + $userCampaigns;
        return $userCampaigns;
    }

    public function campaignsListForReports()
    {
        $userCampaigns = DB::table('ad_campaigns')->where(['owner_id' => $this->user_id])->select('name', 'id')->get();
        return $userCampaigns;
    }

    public function adsListForReports()
    {
        $userAds = DB::table('ad_user_ads')->where(['user_id' => $this->user_id])->select('cads_title', 'id')->get();
        return $userAds;
    }

    public function userAllCampaigns($owner_id)
    {
        return $userCampaigns = DB::table('ad_campaigns')->where(['owner_id' => $owner_id])->orderBy('id', 'DESC')->get();
    }

    public function packageInfo($package_id)
    {
        return Ad::find($package_id);
    }

    public function payPal()
    {
        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername('kinnect2_api1.gmail.com');
        $gateway->setPassword('YKGTXZWGPWNDNZ8Q');
        $gateway->setSignature('AVXro2zM5KcJ1qpllp8Cg.7a9YXAAj1XnNHGpOHb7hv5VO2VMHjOyICO');
        $gateway->setTestMode(true);

        $formData = ['number' => '4032033480835573', 'expiryMonth' => '10', 'expiryYear' => '2020', 'cvv' => '123'];

        $data['customParameter'] = true;

        $response = $gateway->purchase(['amount' => '10.00',
            'currency' => 'USD',
            'ad_id' => '208',
            'cancelUrl' => url('paypal/ad/208'),
            'returnUrl' => url('paypal/update/ad/status/208'),
            'card' => $formData])->send();

        if ($response->isSuccessful()) {
            // payment was successful: update database
            print_r($response);
            dd('in success');
        } elseif ($response->isRedirect()) {
            echo '<pre>';
            echo 'Here';
            print_r($response);
            exit;
            // redirect to offsite payment gateway
            $response->redirect();
            dd('in offsite');
        } else {
            // payment failed: display message to customer
            echo $response->getMessage();
            dd('in failed');
        }

    }

    public function adTargetting($requestFormData) {

        $userAd = AdUserAd::find($requestFormData->ad_id);

        //2015-11-18 16:19:57
        $hour = (isset($requestFormData->start_time_hour) ? $requestFormData->start_time_hour: '00');
        if($requestFormData->start_time_am_pm == 'pm')
        {
            $hour = $hour + 12;
        }
        $minutes = (isset($requestFormData->start_time_minutes) ? $requestFormData->start_time_minutes : '00');
        $starttime = $requestFormData->start_date.' '. $hour . ':'. $minutes.':00';

        $hour = (isset($requestFormData->end_time_hour) ? $requestFormData->end_time_hour: '00');
        if($requestFormData->end_time_am_pm == 'pm')
        {
            $hour = $hour + 12;
        }
        $minutes = (isset($requestFormData->end_time_minutes) ? $requestFormData->end_time_minutes : '00');
        $endtime = $requestFormData->end_date.' '. $hour . ':'. $minutes.':00';

        if($endtime < $starttime){
            return ['error'=>'date_error', 'package_id' => $requestFormData->package_id];
        }

        $userAd->cads_start_date = $starttime;
        $userAd->cads_end_date   = $endtime;

        $userAd->save();

        $isExist = AdTargets::where('user_ad_id', $userAd->id)->first();
        if(isset($isExist->user_ad_id)){
            $targets  = $isExist;
        }else{
            $targets  = new AdTargets;
        }

        $targets->user_ad_id        = $userAd->id;
        $targets->birthday_enable   = '1';
        $targets->age_min           = $requestFormData->min_age;

        $requestFormData->max_age   = ($requestFormData->max_age == 0)?100 : $requestFormData->max_age;
        $targets->age_max           = $requestFormData->max_age;

        $targets->gender            = $requestFormData->gender;
        $targets->profile           = $requestFormData->profile;
        $targets->country           = '';

        $targets->save();

        AdTargetsCountry::where('user_ad_id', $userAd->id)->delete();

        if($requestFormData->country != 0)
        {
            $countries = $requestFormData->country;
            foreach($countries as $country)
            {
                $AdTargetCountry = new AdTargetsCountry();

                $AdTargetCountry->user_ad_id = $userAd->id;
                $AdTargetCountry->country_id = $country;

                $AdTargetCountry->save();
            }
        }else{
            $AdTargetCountry = new AdTargetsCountry();

            $AdTargetCountry->user_ad_id = $userAd->id;
            $AdTargetCountry->country_id = 0;

            $AdTargetCountry->save();
        }

        return $userAd;
    }

    public function createAd($requestFormData, $saved_ad_image_file_id)
    {
        if($requestFormData->campaign_id > 0)
        {
            $campaign = AdCampaign::find($requestFormData->campaign_id);
        }
        else
        {
            $campaign = new AdCampaign;

            $campaign->name     = $requestFormData->campaign_name;
            $campaign->status   = 0;
            $campaign->owner_id = $this->user_id;
            $campaign->save();
        }

        $package = Ad::find($requestFormData->package_id);

        $userAd   = new AdUserAd;

        $userAd->user_id            = $this->user_id;
        $userAd->ad_type            = '';
        $userAd->package_id         = $package->id;
        $userAd->campaign_id        = $campaign->id;
        $userAd->cads_url           = $requestFormData->cads_url;
        $userAd->cads_title         = $requestFormData->name;
        $userAd->cads_body          = $requestFormData->cads_body;
        $userAd->owner_id           = $this->user_id;
        $userAd->photo_id           = '';
        $userAd->gateway_order_id   = '';
        $userAd->cads_start_date    = '';
        $userAd->cads_end_date      = '' ;
        $userAd->sponsored          = '';
        $userAd->featured           = '0';
        $userAd->like               = '0';
        $userAd->resourece_type     = 'user';
        $userAd->resourece_id       = $this->user_id;
        $userAd->public             = '1';
        $userAd->approved           = '0';
        $userAd->enable             = '0';
        $userAd->status             = '0';
        $userAd->payment_status     = '0';
        $userAd->declined           = '0';
        $userAd->approve_date       = '0000-00-00 00:00:00';
        $userAd->price_model        = $package->price_model;
        $userAd->limit_click        = $package->model_detail;
        $userAd->limit_view         = $package->model_detail;
        $userAd->limit_like         = $package->model_detail;
        $userAd->count_view         = '0';
        $userAd->count_like         = '0';
        $userAd->expiry_date        = '0000-00-00 00:00:00';
        $userAd->weight             = '0';
        $userAd->min_ctr            = '0';
        $userAd->gateway_id         = '';
        $userAd->gateway_profile_id = '';
        $userAd->renew_by_admin_date= '0000-00-00 00:00:00';
        $userAd->profile            = '';
        $userAd->story_type         = '';

        $userAd->save();

        if($userAd->photo_id > 0)
        {
            $albumPhoto = $this->getDefaultAlbum($userAd->photo_id);
            $album_id   = $albumPhoto->album_id;
        }
        else
        {
            $album_id = $this->insertDefaultAlbum($userAd->id);
        }

        $userAd->photo_id = $saved_ad_image_file_id;

        $userAd->save();


        $file = StorageFile::where('file_id', $saved_ad_image_file_id)->first();
        if(isset($file->file_id) ) {
            if ( file_exists( "local/storage/app/photos/" . $file->storage_path ) == true ) {
                $file_name     = time() . rand( 111111111, 9999999999 );
                $folder_path   = "local/storage/app/photos/" . $userAd->owner_id;
                $file_name_new = $userAd->owner_id . "_" . $file_name . "." . $file->extension;
                if ( ! file_exists( $folder_path ) ) {
                    if ( ! mkdir( $folder_path, 0777, true ) ) {
                        $folder_path = '';
                    }
                }
                rename( "local/storage/app/photos/" . $file->storage_path, $folder_path . "/" . $file_name_new );
            }

            $file->parent_id = $saved_ad_image_file_id;//photo_id
            $file->user_id = $userAd->owner_id;
            $file->storage_path = $userAd->owner_id."/".$file_name_new;
            $file->name = $file_name;
//            $file->mime_major = 'image';
            $file->save();

        }
        return $userAd;
    }

    public function selectedTargetedCountriesIds($ad_id) {
        return DB::table('ad_targets_countries')->where('user_ad_id', $ad_id)->lists('country_id');
    }
    public function allCountries()
    {
        return DB::table('countries')->lists('name');
    }

    public function createPackage($request)
    {
        $ad['title']              = "Ads per ". $request->price_model;
        $ad['description']        = "this is default description.";
        $ad['level_id']           = "0";
        $ad['price']              = $request->price;
        $ad['sponsored']          = "0";
        $ad['featured']           = "0";
        $ad['url_option']         = "website,album,blog,classified,event,forum,group,music,poll,video";
        $ad['enable']             = "1";
        $ad['network']            = "1";
        $ad['public']             = "1";
        $ad['price_model']        = $request->price_model;
        $ad['model_detail']       = $request->model_period;
        $ad['renew']              = "0";
        $ad['renew_before']       = "0";
        $ad['auto_approve']       = "1";
        $ad['order']              = "0";
        $ad['type']               = "default";
        $ad['created_at']          = Carbon::now();
        $ad['updated_at']          = Carbon::now();

        $adId = Ad::insertGetId($ad);
        if($adId > 0)
        {
            return $adId;
        }
        else
        {
            return 0;
        }
    }

    public function getDefaultAlbum($photo_id)
    {
        // save image record to db, if it is saved well enough.
        return AlbumPhoto::where('photo_id', $photo_id)->first(['album_id']);
    }

    public function insertDefaultAlbum($user_ad_id)
    {
        $userAdAlbum = \DB::table('albums')
            ->select('album_id')
            ->where('owner_id', $user_ad_id)
            ->where('owner_type', 'user_ads')
            ->where('type','ads-profile')
            ->first();

        // save image record to db, if it is saved well enough.
        if(!$userAdAlbum){
            return $album_id = DB::table('albums')->insertGetId(
                [
                    'title' => 'User Ads',
                    'description' => 'Ads default profile album',
                    'owner_type' => 'user_ads',
                    'owner_id' => $user_ad_id,
                    'category_id' => 0,
                    'type' => 'ads-profile',
                    'photo_id' => 0
                ]
            );
        }else{
            return $userAdAlbum->album_id;
        }
    }

    public function insertPhotoIntoAlbum($user_ad_id, $album_id, $path_photo)
    {
        return $photo_id = DB::table('album_photos')->insertGetId(
            ['album_id' => $album_id,
                'title' => 'Ads default profile photo',
                'description' => $path_photo,
                'owner_type' => 'user_ads',
                'owner_id' => $user_ad_id,
                //'file_id' => $file_id,
                'photo_id' => 0
            ]
        );
    }

    public function isAdOwner($ad) {
       if(isset($ad->owner_id)){
            if($ad->owner_id == $this->user_id){
                   return 1;
               }else{
                   return 0;
            }
       }else{
           return 0;
       }
    }
}
