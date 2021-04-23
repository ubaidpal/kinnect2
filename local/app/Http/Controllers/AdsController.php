<?php

namespace App\Http\Controllers;

use App\AdCancels;
use App\AdTargets;
use App\AdUserAd;
use App\Repository\Eloquent\AdRepository;
use App\Services\StorageManager;
use App\StorageFile;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Kinnect2;

class AdsController extends Controller
{
    protected $adRepository;

    public function __construct(AdRepository $adRepository, Request $middleware)
    {
        $this->adRepository = $adRepository;
        $this->user_id = $middleware['middleware']['user_id'];
        @$this->data->user = $middleware['middleware']['user'];
        $this->is_api = $middleware['middleware']['is_api'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['userAds'] = $this->adRepository->getUserAds();

        return view('ads.ad-board', $data)->with('title' , 'Ads Board');
    }

    public function getReportGenerator()
    {
        $data['page_title'] = 'Ads: Reports Generator';
        $data['campaigns'] = $this->adRepository->campaignsListForReports();
        $data['ads'] = $this->adRepository->adsListForReports();

        $data['start_date'] = Carbon::now()->addMonth(-1)->toDateString();
        $data['end_date']   = Carbon::now()->toDateString();

        return view('ads.reportsGenerator', $data)->with('title' , 'Reports Generator');
    }

    public function reportGenerator(Request $request)
    {
        $data['page_title'] = 'Ads: Generated Report';
        $data['statistics'] = $this->adRepository->customStatisticsReport($request);
        $data['summarize_by']            = $request->summarize_by; // ads, campaigns
        $data['filter_by']               = $request->filter_by;    // no-filter, campaign id
        $data['filter_by_time']          = $request->filter_by_time; // daily, monthly, yearly

        if($data['filter_by_time'] == 'yearly'){
            $data['start_date'] = Carbon::now()->addYear(-1)->toDateString();
            $data['end_date']   = Carbon::now()->toDateString();
        }else{
            $data['start_date']              = $request->start_date;     //last month
            $data['end_date']                = $request->end_date;       //now
        }


        $report_destination_type = $request->report_destination_type; //html, xls

        return view('ads.generatedReport', $data);
    }

    public function ajaxReportAd(Request $request) {

        $adCancel = new AdCancels();

        $adCancel->user_id              = $this->user_id;
        $adCancel->report_type          = $request->report_value;
        $adCancel->report_description   = $request->description;
        $adCancel->ad_id                = $request->adId;
        $adCancel->is_cancel            = 0;

        if($adCancel->save()){
            return 1;
        }else{
            return 0;
        }
   }
    public function ajaxImageUpload(Request $request, Kinnect2 $kinnect2)
    {
        if($_FILES['image_file']['tmp_name'] !='' AND $this->user_id > 0)
        {
            $tmp_file_path = $_FILES['image_file']['tmp_name'];
            $userAdSavedPhoto_id = $this->adRepository->tmpUploadingPhotos($tmp_file_path, $this->user_id);
            return $kinnect2::profilePhoto($userAdSavedPhoto_id, $this->user_id, $type = 'ads');
        }
        return 0;
    }

    public function createAd(Request $request)
    {
        $data['page_title'] = 'Ads: Create Ad';

        $data['hours'] = $this->hours();
        $data['minutes'] = $this->minutes();
        $data['hundreds'] = $this->hundreds();
        $data['now_date'] = Carbon::now()->toDateString();

        $data['countries'] = ['0' => ''];
        $data['countries'] = [];
        $data['countries'] = array_merge($data['countries'], $this->adRepository->allCountries());

        $data['package']   = $this->adRepository->packageInfo($request->package_id);
        $data['campaigns'] = $this->adRepository->userCampaignsList($this->user_id);

        return view('ads.createAd', $data)->with('title' , 'Design your ad');
    }

    public function incrementAdClick(Kinnect2 $k2, $ad_id)
    {
        $ad = AdUserAd::find($ad_id);
        if($ad->enable) {
            $k2::incrementAdClick( $ad );
        }
        return redirect($ad->cads_url);
    }


    public function updateAdPaymentStatus(Request $request)
    {
        echo '<tt><pre>'; print_r($request->all()); die;
    }
    public function payPal()
    {
        return $this->adRepository->payPal();
    }

    public function incrementAdView(Kinnect2 $k2, Request $request)
    {
        $adsIds = explode(",", $request->adsIds);
        foreach($adsIds as $adsId)
        {
            $ad = AdUserAd::find($adsId);
            if(isset($ad->enable)){
                if($ad->enable){
                    $k2::incrementAdView($ad, 1);
                }

            }

        }
        return 1;
    }

    public function myCampaigns()
    {
        $data['page_title'] = 'Ads: My Campaigns';

        $data['campaignStatistics'] = $this->adRepository->getUserCampaignDefaultStatistics();
        $data['campaigns']         = $this->adRepository->userAllCampaigns($this->user_id);

        return view('ads.myCampaigns', $data)->with('title' , 'My Campaigns');
    }

    public function myCampaignsStatistics(Request $request)
    {
        $data['page_title'] = 'Ads: My Campaigns';

        $data['campaignStatistics'] = $this->adRepository->getUserCampaignStatistics($request);

        $data['campaigns']          = $this->adRepository->userAllCampaigns($this->user_id);

        return view('ads.myCampaigns', $data);
    }

    public function manageCampaign($campaign_id)
    {
        $data['page_title'] = 'Ads: Manage Campaigns';

        $data['campaignStatistics'] = $this->adRepository->getCampaignDefaultStatistics($campaign_id);

        $data['campaign']    = $this->adRepository->getCampaign($campaign_id);
        $data['campaignAds'] = $this->adRepository->getCampaignAds($campaign_id);

        return view('ads.manageCampaign', $data);
    }

    public function manageCampaignStatistics(Request $request)
    {
        $data['page_title'] = 'Ads: Manage Campaigns';

        $data['campaignStatistics'] = $this->adRepository->getCampaignStatistics($request);

        $data['campaign']    = $this->adRepository->getCampaign($request->campaign_id);
        $data['campaignAds'] = $this->adRepository->getCampaignAds($request->campaign_id);

        return view('ads.manageCampaign', $data);
    }

    public function editAd($ad_id)
    {
        $data['page_title'] = 'Ads: Edit Ad';

        $data['hours'] = $this->hours();
        $data['minutes'] = $this->minutes();
        $data['hundreds'] = $this->hundreds();
        $data['now_date'] = Carbon::now()->toDateString();

        $data['adTargets']  = AdTargets::where('user_ad_id', $ad_id)->first();
        $data['ad']       = $this->adRepository->getAd($ad_id);

        $data['countries'] = ['0' => 'Select Country'];
        $data['countries'] = array_merge($data['countries'], $this->adRepository->allCountries());

        $data['package']   = $this->adRepository->packageInfo($data['ad']->package_id);
        $data['campaigns'] = $this->adRepository->userCampaignsList($this->user_id);


        return view('ads.editAd', $data);
    }

    public function updateAd(Request $request)
    {
        $saved_ad_image_file_id = -1;
        if($request->saved_ad_image_file_id > 1 AND $this->user_id > 0)
        {
            $saved_ad_image_file_id = $request->saved_ad_image_file_id;
        }

        $isSaved = $data['ad'] = $this->adRepository->updateAd($request, $saved_ad_image_file_id);
        if($isSaved > 0)
        {
            $message = 'Record updated.';
        }
        else
        {
            $message = 'Record not updated try again.';
        }
        return redirect('/ads/targetting/'.$request->ad_id);
//        return redirect('/ads/edit/ad/'.$request->ad_id.'/'.$message);
    }

    public function manageAd($ad_id)
    {
        $data['ad'] = AdUserAd::find($ad_id);

        if($data['ad']->id < 1) return redirect('ads/my-campaigns/no_ad_found');

        $data['adStatistics'] = $this->adRepository->getAdDefaultStatistics($data['ad']);
        $data['campaign'] = $this->adRepository->getCampaign($data['ad']->campaign_id);

        return view('ads.manageAd', $data);
    }

    public function manageAdStatistics(Request $request)
    {
        $data['ad'] = AdUserAd::find($request->ad_id);

        if($data['ad']->id < 1) return redirect('ads/my-campaigns/no_ad_found');

        $data['page_title'] = 'Ads: Manage Campaigns';

        $data['adStatistics'] = $this->adRepository->getAdStatistics($request);
        $data['campaign'] = $this->adRepository->getCampaign($data['ad']->campaign_id);

        return view('ads.manageAd', $data);
    }

    public function editCampaign($campaign_id)
    {
        $data['page_title'] = 'Ads: Edit Campaign';

        $data['campaign'] = $this->adRepository->getCampaign($campaign_id);
        return view('ads.editCampaign', $data);
    }

    public function updateCampaign(Request $request)
    {
        $isSaved = $data['campaign'] = $this->adRepository->updateCampaign($request);
        if($isSaved > 0)
        {
            $message = 'Record-updated.';
        }
        else
        {
            $message = 'Record-not-updated-try-again.';
        }

        return redirect('ads/my-campaigns/'.$message);
    }

    public function deleteCampaign($campaign_id)
    {
        $isDeleted = $this->adRepository->deleteCampaign($campaign_id);
        if($isDeleted > 0)
        {
            $message = 'Record deleted.';
        }
        else
        {
            $message = 'Record not deleted.';
        }

        return redirect('/ads/my-campaigns/'.$message);

    }

    public function deleteAd($ad_id)
    {
        $campaign_id = $this->adRepository->deleteAd($ad_id);
        if($campaign_id > 0)
        {
            $message = 'Record deleted.';
        }
        else
        {
            $message = 'Record not deleted.';
        }

        return redirect('/ads/manage/campaign/'.$campaign_id.'/'.$message);
    }

    public function deleteCampaignsAjax(Request $request)
    {
        $campaignsIds = explode(",", $request->adsIds);
        foreach($campaignsIds as $campaignsId)
        {
            $campaign_id = $this->adRepository->deleteCampaign($campaignsId);
        }
        dd(1);
    }

    public function deleteAdsAjax(Request $request)
    {
        $adsIds = explode(",", $request->adsIds);
        foreach($adsIds as $adsId)
        {
            $campaign_id = $this->adRepository->deleteAd($adsId);
        }
        return 'deleted';
    }

    public function createAdStore(Request $request)
    {

        if($request->saved_ad_image_file_id !='' AND $this->user_id > 0)
        {
            $saved_ad_image_file_id = $request->saved_ad_image_file_id;
        }

        $ad = $this->adRepository->createAd($request, $saved_ad_image_file_id);
        if( isset($ad['error']) ){
            return redirect('/ads/create-ad/'.$ad['package_id'].'/invalid_date');
        }else{
            return redirect('/ads/targetting/'.$ad->id);
        }

    }

    public function createAdTargetting(Request $request) {

        $data['ad_id'] = $ad_id =$request->ad_id;

        $data['ad'] = $ad = AdUserAd::find($ad_id);

        if($this->adRepository->isAdOwner($ad) == 0){
            return redirect('ads/my-campa-gns/not authorized');
        }

        $data['adTargets']  = AdTargets::where('user_ad_id', $ad_id)->first();

        $data['page_title'] = 'Ad: Targetting Ad ';

        $data['hours'] = $this->hours();
        $data['minutes'] = $this->minutes();
        $data['hundreds'] = $this->hundreds();
        $data['now_date'] = Carbon::now()->toDateString();
        $data['selectedTargetedCountriesIds'] = $this->adRepository->selectedTargetedCountriesIds($ad_id);
        $data['countries'] = $this->adRepository->allCountries();

        return view('ads.createAdTargetting', $data)->with('title' , 'Targeting and Scheduling');
    }

    public function createAdTargettingPost(Request $request) {
        $userAd = $this->adRepository->adTargetting($request);

        if(isset($userAd->id)){
            if($userAd->payment_status > 0){
                return redirect('ads/manage/ad/'.$userAd->id.'/ad updated');
            }else{
                return redirect('paypal/ad?ad_id='.$userAd->id.'&pkg_id='.$userAd->package_id.'');
            }
        }

        return redirect('ads/my-campaigns/ad-not-updated');

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAdPackage()
    {
        $data['page_title'] = 'Ads: Create Package';

        return view('ads.createPackage', $data)->with('title' , 'Create Package');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $package_id = $this->adRepository->createPackage($request);
        if($package_id > 0)
        {
            return redirect('/ads/create-ad/'.$package_id);
        }
        return redirect('/ads/create/package');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function pauseAd($ad_id)
    {
        $ad = AdUserAd::find($ad_id);

        $ad->status = 0;

        $ad->save();

        $message = 'status-changed.';
        return redirect('/ads/manage/campaign/'.$ad->campaign_id.'/'.$message);
    }

    public function activateAd($ad_id)
    {
        $ad = AdUserAd::find($ad_id);

        $ad->status = 1;

        $ad->save();

        $message = 'status-changed.';

        return redirect('/ads/manage/campaign/'.$ad->campaign_id.'/'.$message);
    }
    public function hours()
    {
        return $hours = [''=>'Hours', '01'=>'01', '02'=>'02', '03'=>'03', '04'=>'04', '05'=>'05'
            , '06'=>'06', '07'=>'07', '08'=>'08', '09'=>'09', '10'=>'10', '11'=>'11', '12'=>'12'];
    }
    public function minutes()
    {
        $minutes = [];

        for($i=0; $i <= 59; $i++){
            if($i < 10){
                $minutes[$i] = '0'.$i;
            }else{
                $minutes[$i] = $i;
            }
        }
        return $minutes;
    }

    public function hundreds()
    {
        $hundreds = [];

        for($i=0; $i <= 100; $i++){
            $hundreds[$i] = $i;
        }
        return $hundreds;
    }

    public function helpOverview()
    {
        $data['page_title'] = "Help: Overview page";

        return view('ads.helpOverview', $data)->with('title' , 'Overview');
    }

    public function helpGetStarted()
    {
        $data['page_title'] = "Help: Get Started page";

        return view('ads.helpGetStarted', $data)->with('title' , 'Get Started');
    }
    public function helpImproveYourAds()
    {
        $data['page_title'] = "Help: Improve Your Ads page";

        return view('ads.helpImproveYourAds', $data)->with('title' , 'Improve Your Ads');
    }
    public function helpContactSales()
    {
        $data['page_title'] = "Help: Contact Sales page";

        return view('ads.helpContactSales', $data)->with('title' , 'Contact Sales Team');
    }
    public function helpGeneralFaq()
    {
        $data['page_title'] = "Help: General Faq page";

        return view('ads.helpGeneralFaq', $data)->with('title' , 'General FAQ');
    }
    public function helpTargetingFaq()
    {
        $data['page_title'] = "Help: Targeting Faq page";

        return view('ads.helpTargetingFaq', $data)->with('title' , 'Targeting FAQ');
    }
    public function helpAdDesignFaq()
    {
        $data['page_title'] = "Help: Ad Design Faq page";

        return view('ads.helpAdDesignFaq', $data)->with('title' , 'Design Your Ad FAQ');
    }

    public function ad_profile_temp_image(Request $request) {

        $ad_image_file = $request->file('ad_image');

        $sm = new StorageManager();

        $data = $sm->storeFile(-1, $ad_image_file, 'album_photo');

        $sfObj = new StorageFile();

        $sfObj->parent_file_id = !empty($data['parent_file_id']) ? $data['parent_file_id'] : NULL;
        $sfObj->type = !empty($data['type']) ? $data['type'] : NULL;
        $sfObj->parent_id = isset($data['parent_id']) ? $data['parent_id'] : NULL;
        $sfObj->parent_type = $data['parent_type'];
        $sfObj->user_id = $data['user_id'];
        $sfObj->storage_path = $data['storage_path'];
        $sfObj->extension = $data['extension'];
        $sfObj->name = $data['name'];
        $sfObj->mime_type = $data['mime_type'];
        $sfObj->size = $data['size'];
        $sfObj->hash = $data['hash'];

        if(!$sfObj->save())
        {
            return FALSE;
        }else{
            return $sfObj->file_id."+_+".$sfObj->storage_path;
        }
    }

}
