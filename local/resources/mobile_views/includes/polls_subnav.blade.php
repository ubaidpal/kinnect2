<div class="sub-nav-container">
    <a class="sub-nav-item" href="{{url('polls')}}">
        <div class="sub-nav-txt <?php echo \Request::route()->getName() == 'polls.index' ? 'active' : ''; ?>">All Polls</div>
    </a>

    <a class="sub-nav-item" href="{{url('polls/manage')}}">
        <div class="sub-nav-txt <?php echo \Request::route()->getPath() == 'polls/manage' ? 'active' : ''; ?>">My Polls</div>
    </a>
    <a class="sub-nav-item" href="{{url('recommended_polls')}}">
        <div class="sub-nav-txt <?php echo \Request::route()->getPath() == 'recommended_polls' ? 'active' : ''; ?>">Recommended</div>
    </a>

    <!--<div class="subnav-edit">
        <a class="btn-subnav-edit" href="javascript:void(0)"></a>

        <div class="subnav-edit-pp hide">
            <ul>
                <li><a class="subnav-pp-item" href="javascript:void(0)">Info</a></li>
                <li><a class="subnav-pp-item" href="javascript:void(0)">Videos</a></li>
                <li><a class="subnav-pp-item" href="javascript:void(0)">Albums</a></li>
                <li><a class="subnav-pp-item" href="javascript:void(0)">Edit Profile</a></li>
            </ul>
        </div>
    </div>-->
</div>
