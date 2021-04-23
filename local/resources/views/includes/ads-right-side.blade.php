<!-- Right Ad Panel -->
<div class="adPanel">
    <div class="bandsNadds">
        @if(Request::is('profile/*') or Request::is('brand/*') || Request::is('/') || Request::is('home'))
        <div class="homesorting" id="post_filters">

        </div>
        @endif
        <div class="head">
            <a href="{{url('/ads/create/package')}}" title="Create an Ad">Create an Ad</a>

            <div class="fltR"><a title="More Ads" href="{{url('ads/ad-board')}}">More Ads</a></div>
        </div>
    </div>
    <style>
        .all_other_ads {
            position: fixed;
            top: 100px;
        }
    </style>
    @if(Request::is('profile/*')|| Request::is('brand/*'))
        <?php $class = 'profile_ads';?>
        @elseif(Request::is('home')|| Request::is('/'))
        <?php $class = 'dashboard_ads';?>
        @else
        <?php $class = 'all_other_ads';?>
        @endif
    <div class="adsDiv {{$class}}" id="stickAdd" @if(Request::is('profile/*') or Request::is('brand/*') or Request::is('store/*')) data-page="profile" @endif>
        <?php $ads = Kinnect2::getAdsWidget(); ?>
        @foreach($ads as $ad)
            <div class="ads_box {{$ad->id}} ads_box_{{$ad->id}}">
                <div id="ad_display_{{$ad->id}}" class="ad_display">
                    <a data-adid="{{$ad->id}}" href="{{url('/ads/incrementAdClick/'.$ad->id)}}"
                       id="{{$ad->campaign_id}}" class="ad-clicked" target="_blank">{{$ad->cads_title}}<br/><span
                                id="ads_url">{{$ad->cads_url}}</span></a>

                    <a data-adid="{{$ad->id}}" href="{{url('/ads/incrementAdClick/'.$ad->id)}}"
                       id="{{$ad->campaign_id}}" class="ad-clicked" target="_blank">
                        <img style="height: 170px;"
                             onclick="incrementAdViewClick('<?php echo $ad->id ?>', '<?php echo $ad->cads_url?>' );"
                             src="{{Kinnect2::getPhotoUrl($ad->photo_id, $ad->id, 'ads', 'ad_profile')}}"
                             id="{{$ad->campaign_id}}" width="170" height="170" alt="{{$ad->cads_title}}"
                             title="{{$ad->cads_title}}"/>
                    </a>

                    <p>{{$ad->cads_body}}</p>
                    <span id="ad_url_{{$ad->id}}" style="display: none;">{{$ad->cads_url}}</span>
                    <a class="report_ad" href="#" onclick="adCancel('<?php echo $ad->id; ?>')" style="display:none;">x</a>
                </div>

                <div id="cmad_ad_cancel_{{$ad->id}}" style="display: none; width: 171px;" class="cmadrem">
                    <div class="cmadrem_rl">
                        <a class="" title="Cancel reporting this ad" href="javascript:void(0);"
                           onclick="adUndo('<?php echo $ad->id; ?>');">Undo</a></div>
                    <div class="cmadrem_con">
                        Do you want to report this? Why didn't you like it?
                        <form>
                            <div>
                                <input id="report_msld_{{$ad->id}}" type="radio" name="adAction" value="0" onclick="adSave('1', '<?php echo $ad->id; ?>')">
                                <label onclick="adSave('1', '<?php echo $ad->id; ?>')" for="report_msld_{{$ad->id}}">Misleading</label>
                            </div>
                            <div>
                                <input id="report_ofnsve_{{$ad->id}}" type="radio" name="adAction" value="1" onclick="adSave('2', '<?php echo $ad->id; ?>')">
                                <label onclick="adSave('2', '<?php echo $ad->id; ?>')" for="report_ofnsve_{{$ad->id}}">Offensive</label>
                            </div>
                            <div>
                                <input id="report_inap_{{$ad->id}}" type="radio" name="adAction" value="2" onclick="adSave('3', '<?php echo $ad->id; ?>')">
                                <label onclick="adSave('3', '<?php echo $ad->id; ?>')" for="report_inap_{{$ad->id}}">Inappropriate</label>
                            </div>
                            <div>
                                <input id="report_licnd_{{$ad->id}}" type="radio" name="adAction" value="3" onclick="adSave('4', '<?php echo $ad->id; ?>')">
                                <label onclick="adSave('4', '<?php echo $ad->id; ?>')" for="report_licnd_{{$ad->id}}">Licensed Material</label>
                            </div>
                            <div>
                                <input type="radio" name="adAction" value="4" onclick="otherAdCannel('<?php echo $ad->id; ?>')" id="cmad_other_{{$ad->id}}">
                                <label onclick="otherAdCannel('<?php echo $ad->id; ?>')" for="cmad_other_{{$ad->id}}">Other</label>
                            </div>

                            <div>
            <textarea name="cmad_other_text_{{$ad->id}}" onkeyup="showReportButton('<?php echo $ad->id; ?>')" onclick="this.value = ''" onblur="if (this.value == '')
                this.value = 'Specify your reason here..';" id="cmad_other_text_{{$ad->id}}" style="display:none;">Specify your reason here..</textarea>
                            </div>

                            <div>
                                <a href="javascript:void(0);" onclick="adReportSave('<?php echo $ad->id; ?>')" id="cmad_report_button_<?php echo $ad->id; ?>" style="display:none" class="orngBtn">Report</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<script>
    var report_value = '';

    function adUndo(ad_id)
    {
        $("#cmad_ad_cancel_"+ad_id).css('display', 'none');
        $("#ad_display_"+ad_id).css('display', 'block');
    }

    function adSave(report_type, ad_id)
    {
        report_value = report_type;
        $("#cmad_other_text_"+ad_id).css('display', 'none');
        $("#cmad_report_button_"+ad_id).css('display', 'block');

    }

    function adReportSave(ad_id)
    {
        var description = '';

        if(report_value == 5){
            description = $("#cmad_other_text_"+ad_id).val();
        }

        var dataString = "report_value=" + report_value + "&adId=" + ad_id + "&description=" + description;

        $.ajax({
            type: 'POST',
            url: '{{url('/ads/report/ajax')}}',
            data: dataString,
            success: function (response) {
                if(response == 1){
                    $(".ads_box_"+ad_id).html('<h3>Ad is reported successfully.</h3>');
                }else{
                    alert("Not reported due to some reason, please try again.");
                }
            }
        });
    }

    function otherAdCannel(ad_id)
    {
        report_value = 5;

        $("#cmad_other_text_"+ad_id).css('display', 'block');
        $("#cmad_report_button_"+ad_id).css('display', 'none');

    }

    function adCancel(ad_id)
    {
        $("#cmad_ad_cancel_"+ad_id).css('display', 'block');
        $("#ad_display_"+ad_id).css('display', 'none');
    }

    function showReportButton(ad_id)
    {
        if($("#cmad_other_text_"+ad_id).val().length > 5)
        {
            $("#cmad_report_button_"+ad_id).css('display', 'block');
        }else{
            $("#cmad_report_button_"+ad_id).css('display', 'none');
        }

    }

    $(document).ready(function () {
        var myAds = [];
        $(".ad_display > a").each(function () {

            if (myAds.indexOf($(this).data("adid")) < 0) {
                myAds.push($(this).data("adid"));
            }

        });

        var dataString = "adsIds=" + myAds;
        $.ajax({
            type: 'POST',
            url: '{{url('/ads/incrementAdView')}}',
            data: dataString,
            success: function (response) {
//                alert(response);
            }
        });

    });
</script>
