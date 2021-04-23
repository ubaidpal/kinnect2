<!-- Right Ad Panel -->
<div class="create_ad_panel">
    <div class="menu-btn">
        <a title="Creat an Ad" href="{{url('ads/create/package')}}" <?php echo (strpos($_SERVER['REQUEST_URI'], 'ads/create-ad/') !== false OR strpos($_SERVER['REQUEST_URI'], 'ads/create/package') !== false) ? 'class="create_ad active"' : 'class="create_ad"'?>>Create an Ad</a>
        
        <a title="My Campaigns" href="{{url('ads/my-campaigns')}}" <?php echo (strpos($_SERVER['REQUEST_URI'], 'ads/my-campaigns') !== false OR strpos($_SERVER['REQUEST_URI'], 'ads/manage/') !== false) ? 'class="campaigns active"' : 'class="campaigns"'?>>My Campaigns</a>
        
        <a title="Ad Board" href="{{url('ads/ad-board')}}" <?php echo (strpos($_SERVER['REQUEST_URI'], 'ads/ad-board') !== false) ? 'class="ad_board active"' : 'class="ad_board"'?>>Ad Board</a>
        
        <a title="Reports" href="{{url('ads/reports/generator')}}" <?php echo (strpos($_SERVER['REQUEST_URI'], 'ads/reports/generator') !== false) ? 'class="reports active"' : 'class="reports"'?>>Reports</a>

        <div id="help-more">
            <ul>
                <li>
                    <a title="Help & Learn More" href="javascript:void(0);" <?php echo (strpos($_SERVER['REQUEST_URI'], 'ads/help/') !== false) ? 'class="help_more active"' : 'class="help_more"'?>>Help
                                                                                                                                                               &amp;
                                                                                                                                                               Lean
                                                                                                                                                               More</a>
                    <ul <?php echo (strpos($_SERVER['REQUEST_URI'], 'ads/help/') !== false) ? 'style="display:block"' : ''?>>

                        <li>
                            <a title="Overview" <?php echo (strpos($_SERVER['REQUEST_URI'], '/help/overview') !== false) ? 'style="color:#ee4b08;"' : ''?> href="{{url('/ads/help/overview')}}"><span>Overview</span></a>
                        </li>

                        <li>
                            <a title="Get Started" <?php echo (strpos($_SERVER['REQUEST_URI'], '/help/get-started') !== false) ? 'style="color:#ee4b08;"' : ''?> href="{{url('/ads/help/get-started')}}"><span>Get Started</span></a>
                        </li>

                        <li>
                            <a title="Improve your Ads" <?php echo (strpos($_SERVER['REQUEST_URI'], '/help/improve-your-ads') !== false) ? 'style="color:#ee4b08;"' : ''?> href="{{url('/ads/help/improve-your-ads')}}"><span>Improve your Ads</span></a>
                        </li>

                        <li>
                            <a title="Contact Sales Team" <?php echo (strpos($_SERVER['REQUEST_URI'], '/help/contact-sales') !== false) ? 'style="color:#ee4b08;"' : ''?> href="{{url('/ads/help/contact-sales')}}"><span>Contact Sales Team</span></a>
                        </li>

                        <li>
                            <a title="General FAQ" <?php echo (strpos($_SERVER['REQUEST_URI'], '/help/general-faq') !== false) ? 'style="color:#ee4b08;"' : ''?> href="{{url('/ads/help/general-faq')}}"><span>General FAQ</span></a>
                        </li>

                        <li>
                            <a title="Design Your Ad FAQ" <?php echo (strpos($_SERVER['REQUEST_URI'], '/help/ad-design-faq') !== false) ? 'style="color:#ee4b08;"' : ''?> href="{{url('/ads/help/ad-design-faq')}}"><span>Design Your Ad FAQ</span></a>
                        </li>

                        <li>
                            <a title="Targeting FAQ" <?php echo (strpos($_SERVER['REQUEST_URI'], '/help/targeting-faq') !== false) ? 'style="color:#ee4b08;"' : ''?> href="{{url('/ads/help/targeting-faq')}}"><span>Targeting FAQ</span></a>
                        </li>

                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
