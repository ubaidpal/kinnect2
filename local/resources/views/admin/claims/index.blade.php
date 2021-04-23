{{--

    * Created by   :  Muhammad Yasir
    * Project Name : kinnect2
    * Product Name : PhpStorm
    * Date         : 04-Mar-2016 6:45 PM
    * File Name    : INDEX

--}}
@extends('admin.layout.store-admin')
@section('content')
        <!-- Post Div-->
@include('admin.layout.arbitrator-leftnav')
@include('admin.alert.alert')
<div class="ad_main_wrapper">
    <div class="task_inner_wrapper">
        <div class="task-tabs">
            <a href="{{route('super-admin.claims-unassigned')}}"
               class="@if($type == 'unassigned') active @endif">Unassigned</a>
            <a href="{{route('super-admin.claims-assigned')}}"
               class="@if($type == 'assigned') active @endif">Assigned</a>
            <a href="{{route('super-admin.claims-resolved')}}"
               class="@if($type == 'resolved') active @endif">Resolved</a>
        </div>
        <div class="awr-search">
            <form method="get" action="{{route('claim.search')}}" id="serachForm">
                {!! Form::hidden('type', $type) !!}
                <div class="fltR">
                    <div class="awr-ttle">
                        <input type="text" placeholder="Type Order ID" class="search" name="order_id"
                               value="@if(isset($order_id)){{$order_id}}@endif">
                    </div>
                    <div class="awr-btn">
                        <button class="searchFormBtn" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
        @if(count($claims) > 0)
            <div class="assigned-task-wrapper">

                @foreach($claims as $claim)
                    <div class="task-wrapper">
                        <div class="icon">
                            <div>icon</div>
                        </div>
                        <div class="detail">
                            <h3>{{$claim->title}}</h3>

                            <div class="order-info">
                                @if(isset($claim->dispute->order))
                                    Order ID: {{$claim->dispute->order->order_number}} &nbsp;-&nbsp;
                                @endif
                                Claimed by {{@$claim->dispute->user->displayname}} &nbsp;-&nbsp;
                                Dated: {{\Carbon\Carbon::parse($claim->created_at)->format('d F Y')}}
                            </div>
                            <p>{{$claim->detail}}</p>

                            <div class="view-dispute">
                                <a class="view-detail"
                                   href="{{url($admin_url.'claim-detail/'.@$claim->dispute->reference_id)}}">
                                    View Detail
                                </a>
                                @if(!\Request::is('admin/super-admin/claims-resolved'))
                                    @role('super.admin')
                                    <div class="assignto">
                                        {!! Form::select('arbitrator', ['placeholder' => 'Select Arbitrator']+$arbitrator,$claim->arbitrator_id,['class'=>'assign-claim', 'data-id' => $claim->uuid]) !!}
                                    </div>
                                    @endrole
                                    @role('dispute.manager')
                                    <div class="assignto">
                                        {!! Form::select('arbitrator', ['placeholder' => 'Select Arbitrator']+$arbitrator,$claim->arbitrator_id,['class'=>'assign-claim', 'data-id' => $claim->uuid]) !!}
                                    </div>
                                    @endrole
                                @endif
                                @if(\Request::is('admin/super-admin/claims-unassigned'))

                                    @if($claim->arbitrator_id != $user_id && $claim->status != 1)
                                        @role('arbitrator')
                                        <a class="view-detail" href="{{url($admin_url.'claim/assign/'.$claim->uuid)}}">
                                            Assign to me
                                        </a>
                                        @endrole
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                @if(isset($serach) && $search == 'search')
                    {!! $claims->render() !!}
                @endif
            </div>
        @else
            No Claim found
        @endif
    </div>
</div>
@endsection
@section('footer-scripts')
    {!! HTML::script('local/public/assets/admin/script.js') !!}
@endsection
