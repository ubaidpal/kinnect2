<!-- Right Ad Panel -->
<div class="create_ad_panel">
	<div class="menu-btn">
        <a href="{{url('ads/create/package')}}" <?php echo (strpos($_SERVER['REQUEST_URI'], 'ads/create-ad/') !== false OR strpos($_SERVER['REQUEST_URI'], 'ads/create/package') !== false) ? 'class="assigned active"' : 'class="assigned"'?> title="Tasks">Tasks</a>
        <a href="{{url('ads/my-campaigns')}}" <?php echo (strpos($_SERVER['REQUEST_URI'], 'ads/my-campaigns') !== false OR strpos($_SERVER['REQUEST_URI'], 'ads/manage/') !== false) ? 'class="closed active"' : 'class="closed"'?> title="Closed">Closed</a>
        <a href="{{url('ads/my-campaigns')}}" <?php echo (strpos($_SERVER['REQUEST_URI'], 'ads/my-campaigns') !== false OR strpos($_SERVER['REQUEST_URI'], 'ads/manage/') !== false) ? 'class="userM active"' : 'class="userM"'?> title="User Management">User Management</a>
        <a href="{{url('ads/my-campaigns')}}" <?php echo (strpos($_SERVER['REQUEST_URI'], 'ads/my-campaigns') !== false OR strpos($_SERVER['REQUEST_URI'], 'ads/manage/') !== false) ? 'class="userM active"' : 'class="userM"'?> title="Sales & Accounts">Sales & Accounts</a>
        <a href="{{url('ads/my-campaigns')}}" <?php echo (strpos($_SERVER['REQUEST_URI'], 'ads/my-campaigns') !== false OR strpos($_SERVER['REQUEST_URI'], 'ads/manage/') !== false) ? 'class="userM active"' : 'class="userM"'?> title="Withdrawl Requests">Withdrawl Requests</a>
        <a href="{{url('admin/transactions')}}" <?php echo (strpos($_SERVER['REQUEST_URI'], '/transactions') !== false OR strpos($_SERVER['REQUEST_URI'], '/transactions/') !== false) ? 'class="userM active"' : 'class="userM"'?> title="Transactions">Transactions</a>
    </div>
</div>