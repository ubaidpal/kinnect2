@extends('layouts.masterDynamic')
@section('header-styles')
        <!--added for ads module-->
<link rel="stylesheet" href="{!! asset('local/public/assets/css/jquery-ui.min.css') !!}">
<script src="{!! asset('local/public/assets/js/jquery-ui.min.js') !!}"></script>

<link href="{{ asset('/local/public/css/jquery.multiselect.css') }}" rel="stylesheet">
<script src="{{ asset('/local/public/js/jquery.multiselect.js') }}"></script>
<!--end of added for ads module-->
@endsection
@section('content')

    <style>
        .adsError {
            color: red;
            font-size: 14px;
            font-weight: bold;
        }

        .cropit-image-preview{
            border-radius: 0 ;
            margin-left: 5px;
        }

        .cropit-image-background-container {
            height: 165px !important;
        }
    </style>
    @include('includes.ads-left-nav')
            <!--Create Album-->
    <div class="ad_main_wrapper">
        <div class="add_breadcrumb">
            <div class="create_p visited">Create Package</div>
            <div class="design_ad visited">Design Your Ad</div>
            <div class="target_sch active">Targeting and Scheduling</div>
        </div>
        {!! Form::open(["id" => "ad-detail-form", "enctype"=>"multipart/form-data"]) !!}
        <div id="targetDivWrapper">
            <div id="targetdiv" class="slide cmad_ad_steps">
                <div class="global_form">
                    <div>
                        <div>
                            <div class="form-elements">
                                <div class="main_heading">
                                    <h1>Targeting and Scheduling</h1>
                                    <span>Step 3/3</span>
                                </div>
                                <div class="form-wrapper">
                                    <div class="form-label"><label>Age</label></div>
                                    <p>Select the minimum and maximum age of the people who will find your ad relevant.</p>
                                    <div class="form-element form-element-age">
                                        {!!  Form::select('min_age',
                                                $hundreds, (isset($adTargets->age_min))?$adTargets->age_min:'', ['id' => 'min_age'])!!}
                                        @if($errors->first('min_age'))
                                            <span>{{ $errors->first('min_age') }}</span>
                                        @endif
                                    </div>
                                    <div class="seprate"> -- </div>
                                    <div class="form-element form-element-age">
                                        {!!  Form::select('max_age',
                                                $hundreds, (isset($adTargets->age_min))?$adTargets->age_max:'', ['id' => 'max_age'])!!}
                                        @if($errors->first('max_age'))
                                            <span>{{ $errors->first('max_age') }}</span>
                                        @endif
                                    </div>
                                    <div class="clrfix"></div>
                                </div>
                                <div id="gender-wrapper" class="form-wrapper">
                                    <div id="gender-label" class="form-label"><label for="gender"
                                                                                     class="optional">Gender</label>
                                    </div>
                                    <div id="gender-element" class="form-element">
                                        <p>Choose "Both" unless you only want your ads to be shown to either men or women.</p>
                                        <select name="gender" id="gender" alias="gender" publish="0" show="1" style=""
                                                data-field-id="5">
                                            <option value="" <?php if(isset($adTargets->gender)){if($adTargets->gender == 0) echo 'selected="selected"'; }?>>Both
                                                (Male and Female)
                                            </option>
                                            <option value="2" <?php if(isset($adTargets->gender)){if($adTargets->gender == 2) echo 'selected="selected"';} ?>>
                                                Male
                                            </option>
                                            <option value="3" <?php if(isset($adTargets->gender)){if($adTargets->gender == 3) echo 'selected="selected"';} ?>>
                                                Female
                                            </option>
                                        </select></div>
                                </div>
                                <div id="country-wrapper">
                                    <div id="country-label" class="form-label"><label for="country" class="optional">Select
                                            Country</label></div>
                                    <div id="country-element" class="form-element">
                                        <p>Designated countries to show or exclude your ad to people in those locations.</p>
                                        {!! Form::select('country[]', $countries, $selectedTargetedCountriesIds, array(
                                                                                    'multiple'=>'multiple',
                                                                                    'name'=>'country[]'))  !!}

                                        @if($errors->first('country'))
                                            <span>{{ $errors->first('country') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div id="toValues-wrapper" class="form-wrapper">
                                    <div id="toValues-label" class="form-label">&nbsp;</div>
                                    <div id="toValues-element" class="form-element">
                                        <input type="hidden" name="toValues" value="" id="toValues"></div>
                                </div>
                                <div class="main_heading">
                                    <h1>Advanced Targeting Options</h1>
                                </div>
                                <div id="profile-wrapper" class="form-wrapper mb10">
                                    <div id="profile-label" class="form-label"><label for="profile" class="optional">Select
                                            Profile Type</label></div>
                                    <div id="profile-element" class="form-element"><p class="description">Profile types
                                            based advanced targeting enables you to target your ad to users of a
                                            specific profile type. Select the profile type that you want to target to,
                                            or choose "All" to reach all profile types.</p>

                                        <ul class="form-options-wrapper">
                                            <li>
                                                <input type="radio" name="profile" id="profile-0" value="0"
                                                       <?php if(isset($adTargets->profile)){if($adTargets->profile == 0) echo 'checked="checked"';}else{echo 'checked="checked"';} ?> />
                                                <label for="profile-0">All</label>
                                            </li>
                                            <li>
                                                <input type="radio" name="profile" id="profile-1" value="1"
                                                <?php if(isset($adTargets->profile)){if($adTargets->profile == 1) echo 'checked="checked"';} ?> />
                                                <label for="profile-1">Regular Member</label>
                                            </li>
                                            <li>
                                                <input type="radio" name="profile" id="profile-2" value="2"
                                                <?php if(isset($adTargets->profile)){if($adTargets->profile == 2) echo 'checked="checked"';} ?> />
                                                <label for="profile-2">Brand Manager</label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-label mb10"><label><b>Ad Scheduling</b></label></div>
                                <div class="field-item calendar">
                                    <h3 id="date_errors" style="display: none;">End date must be greater than Start Date, and
                                        Start Date must be greater from '{{$now_date}}'</h3>
                                    @if($errors->isDateValid->any())
                                        <?php echo $errors->isDateValid->first( 'isDateValid' ) ?>
                                    @endif
                                    <label for="">Start Time</label>

                                    <div class="select-date">
                                        <?php $datetime = explode( ' ', $ad->cads_start_date ) ?>
                                        <input type="text" name="start_date" value="<?php if($datetime[0] == "0000-00-00"){ echo $now_date; }else{echo $datetime[0];} ?>"
                                               title="Select date to start." id="start_date">
                                        <a class="btn-calendar" id="start_date" _icon href="javascript:void(0);"></a>
                                    </div>
                                    <?php
                                    $time = explode( ':', $datetime[1] );
                                    $pmSelected = false;
                                    if ( $time[0] > 12 ) {
                                        $time[0]    = $time[0] - 12;
                                        $pmSelected = true;
                                    }
                                    ?>
                                    <div class="select-time">
                                        {!!  Form::select('start_time_hour',
                                             $hours, $time[0], ['id' => 'start_time_hour'])!!}
                                        @if($errors->first('start_time_hour'))
                                            <span>{{ $errors->first('start_time_hour') }}</span>
                                        @endif

                                        {!!  Form::select('start_time_minutes',
                                             $minutes, $time[1], ['id' => 'start_time_minutes'])!!}
                                        @if($errors->first('start_time_minutes'))
                                            <span>{{ $errors->first('start_time_minutes') }}</span>
                                        @endif

                                        <select name="start_time_am_pm" id="start_time_am_pm">
                                            <option value="am">AM</option>
                                            <option value="pm" @if($pmSelected)selected='selected'@endif>PM</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="field-item calendar">
                                    <label for="">End Time</label>

                                    <div class="select-date">
                                        <?php $datetime = explode( ' ', $ad->cads_end_date ) ?>
                                        <input type="text" name="end_date" value="{{$datetime[0]}}" title="Select date to end."
                                               id="end_date">
                                        <a class="btn-calendar" id="end_date_icon" href="javascript:void(0);"></a>
                                    </div>
                                    <?php
                                    $time = explode( ':', $datetime[1] );
                                    $pmSelected = false;
                                    if ( $time[0] > 12 ) {
                                        $time[0]    = $time[0] - 12;
                                        $pmSelected = true;
                                    }
                                    ?>
                                    <div class="select-time">
                                        {!!  Form::select('end_time_hour',
                                             $hours, $time[0], ['id' => 'end_time_hour'])!!}
                                        @if($errors->first('end_time_hour'))
                                            <span>{{ $errors->first('end_time_hour') }}</span>
                                        @endif

                                        {!!  Form::select('end_time_minutes',
                                             $minutes, $time[1], ['id' => 'end_time_minutes'])!!}
                                        @if($errors->first('end_time_minutes'))
                                            <span>{{ $errors->first('end_time_minutes') }}</span>
                                        @endif
                                        <input type="hidden" value="-1" name="saved_ad_image_file_id"
                                               id="saved_ad_image_file_id">

                                        <select name="end_time_am_pm" id="end_time_am_pm">
                                            <option value="am">AM</option>
                                            <option value="pm" @if($pmSelected)selected='selected'@endif>PM</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="save_area">
                                    <div id="continue_review-wrapper" class="form-wrapper fltR">
                                        <div id="continue_review-element">
                                            <button name="continue_review" id="continue_review" onclick="check_dates(this);"
                                                    type="button" class="orngBtn">Next
                                            </button>
                                        </div>
                                    </div>
                                    <button class="light_grey_btn" type="button" onclick="location.href = '{{url('ads/edit/ad/'.$ad->id)}}';">Back</button>
                                </div>
                            </div>





                        </div>
                    </div>
                </div>
            </div>
        </div>

        </form>

    </div>

    </div>
@endsection

@section('footer-scripts')
    <script type="text/javascript">

        function check_dates(e) {
            var now_date = '<?php echo $now_date; ?>';
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            if (end_date < start_date || start_date < now_date) {
                $('#date_errors').show();
                $('#date_errors').css('color', 'red');
                $('#date_errors').css('margin', '0 0 17px 0px');

                $('#start_date').css('border', '1px solid red');
                $('#end_date').css('border', '1px solid red');

                return false;
            }

            $("#ad-detail-form").submit();
        }

        $('select[multiple]').multiselect({
            columns: 1,
            search: true,
            selectedList : 1,
            placeholder: 'Select Country'
        });


    </script>
@endsection
