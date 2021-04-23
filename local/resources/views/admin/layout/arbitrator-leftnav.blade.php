<!-- Right Ad Panel -->
<div class="create_ad_panel">
    <div class="menu-btn">

        @role('super.admin')
        <a href="{{route('admin.home')}}" class="dashboard @if(\Request::is('admin')) active @endif" title="Dashboard">
            Dashboard
        </a>
        @endrole

        @permission('create.users')
        <a href="{{route('admin.users')}}" class="userM @if(\Request::is('admin/users')) active @endif" title="User Management">
            User Management
        </a>
        @endpermission

        @permission('arbitrator')
            <a href="{{route('super-admin.claims-unassigned')}}"
               class="claim @if(\Request::is('admin/super-admin/claims-unassigned')) active @endif" title="Claims">
                Claims
            </a>
        @endpermission
        @permission('resolved.disputes')
            <a href="{{route('super-admin.claims-resolved')}}"
               class="claim @if(\Request::is('admin/super-admin/claims-resolved')) active @endif" title="Resolved Claims">
                Resolved Claims
            </a>
        @endpermission
        @permission('accounts')
            <a href="{{url('admin/withdrawalRequests')}}"
               class="withdrawals @if(\Request::is('admin/withdrawalRequests')) active @endif" title="Withdrawals">
                Withdrawals
            </a>
            <a href="{{url('admin/transactions')}}" class="transaction @if(\Request::is('admin/transactions')) active @endif" title="Transactions">
                Transactions
            </a>
        @endpermission
        @role('super.admin')
        <a href="{{route('admin.settings')}}" class="admin-sett @if(\Request::is('admin/settings')) active @endif" title="Settings">
            Settings
        </a>
        <a href="{{route('admin.flaggedPosts')}}" class="flag-post @if(\Request::is('admin/flagged-posts')) active @endif" title="Flagged posts">
            Flagged posts
        </a>
        <a href="{{url('/admin/store_transactions')}}" class="store-trans @if(\Request::is('admin/store_transactions')) active @endif" title="Store Transactions">Store Transactions</a>
        @endrole
        @if(isset(Auth::user()->id))
            <a href="{{url('admin/changePassword/'.Auth::user()->id)}}" id="{{Auth::user()->id}}" title="Change Password"
               class="change-pass @if(\Request::is('admin/changePassword/'.Auth::user()->id)) active @endif">
                Change Password
            </a>
        @endif
    </div>
</div>
