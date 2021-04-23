<!-- Right Ad Panel -->
<div class="ads-panel">
    <div class="menu-btn">
        <a href="{{url('settings/general')}}" <?php echo (strpos($_SERVER['REQUEST_URI'], 'general') !== false) ? 'class="btn active"' : 'class="btn"'?> title="General" >General</a>
        <a href="{{url('settings/privacy')}}" <?php echo (strpos($_SERVER['REQUEST_URI'], 'privacy') !== false) ? 'class="btn active"' : 'class="btn"'?> title="Privacy" >Privacy</a>
        <!--<a href="javascript:();" class="btn">Networks</a>-->
        <a href="{{url('settings/notification')}}" <?php echo (strpos($_SERVER['REQUEST_URI'], 'notification') !== false) ? 'class="btn active"' : 'class="btn"'?> title="Notifications" >Notifications</a>
        <a href="{{url('settings/change-password')}}" <?php echo (strpos($_SERVER['REQUEST_URI'], 'change-password') !== false) ? 'class="btn active"' : 'class="btn"'?> title="Change Password">
            Change Password
        </a>
        <a href="{{url('settings/delete-account')}}" <?php echo (strpos($_SERVER['REQUEST_URI'], 'delete-account') !== false) ? 'class="btn active"' : 'class="btn"'?> title="Delete Account">
            Delete Account
        </a>
    </div>
</div>
