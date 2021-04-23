@extends('admin.layout.store-admin')
@section('content')
    @include('admin.layout.arbitrator-leftnav')
    <div class="ad_main_wrapper" id="ad_main_wrapper">
        <div class="task_inner_wrapper">
            <div class="main_heading">
                <h1>Store Transactions</h1>
            </div>
            <div class="bsmp-serachf mb20">
                {!! Form::open(['url' => 'admin/store_transactions','method' => 'get']) !!}
                <div class="form-field">
                    <label>From</label>

                    <div class="form-item">
                        <input type="text" placeholder="{{\Carbon\Carbon::now()->format('Y/m/d')}}" value="{{date('Y/m/d',strtotime($from))}}"
                               id="from" name="from" required>
                    </div>
                </div>
                <div class="form-field">
                    <label>To</label>

                    <div class="form-item">
                        <input type="text" placeholder="{{\Carbon\Carbon::now()->format('Y/m/d')}}" id="to" value="{{date('Y/m/d',strtotime($to))}}"
                               name="to" required>
                    </div>
                </div>
                <div class="form-field">
                    <label>Transaction Type</label>
                    <select name="transaction_type">
                        <option value="">All Transaction Types</option>
                        <option @if($transaction_type == 'credit') selected="selected" @endif value="credit">Credit</option>
                        <option @if($transaction_type == 'debit') selected="selected" @endif value="debit">Debit</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="orngBtn">Search</button>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="assigned-task-wrapper">
                <div class="user-table heading">
                    <div class="name">Name</div>
                    <div class="email">Date</div>
                    <div class="role">Type</div>
                    <div class="action">Amount</div>
                </div>

                @if($transactions->count())
                    <?php
                    $totalDebits = 0;
                    $totalCredit = 0;
                    ?>
                    @foreach($transactions as $row)
                        <div class="user-table">
                            <div class="name">{{@$row->user->first_name.' '.$row->user->last_name}}</div>
                            <div class="email">{{getTimeByTZ($row->created_at, 'M. d, Y')}}</div>
                            <div class="role">{{config('constants_brandstore.STATEMENT_TYPES_STRING.'.$row->type)}}</div>
                            @if($row->transaction_type == 'credit')
                            <?php
                            $totalCredit = $totalCredit + $row->amount;
                            ?>
                            <div class="action credit">${{format_currency($row->amount)}}</div>
                            @elseif($row->transaction_type == 'debit')
                            <?php
                             $totalDebits = $totalDebits + $row->amount;
                             ?>
                            <div class="action debit">${{format_currency($row->amount)}}</div>
                            @endif

                        </div>
                    @endforeach
                @else
                    No record found
                @endif
            </div>

        </div>
        {!!  $transactions->render() !!}
    </div>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#from").datepicker({
                dateFormat : "yy/mm/dd",
                showOn : 'both',
                buttonImage : '{{asset('local/public/assets/images/img-Start-Time.png')}}', //minDate : 0,
                onClose : function(selectedDate){
                    $("#to").datepicker("option", "minDate", selectedDate);
                }
            });

            $("#start_date_icon").click(function(evt){
                evt.preventDefault();
                $("#start_date").click();
            });

            $("#to").datepicker({
                dateFormat : "yy/mm/dd",
                showOn : 'both',
                buttonImage : '{{asset('local/public/assets/images/img-Start-Time.png')}}', //minDate : 0,
                onClose : function(selectedDate){
                    $("#from").datepicker("option", "maxDate", selectedDate);
                }
            });

            $("#end_date_icon").click(function(evt){
                evt.preventDefault();
                $("#end_date").click();
            });
        });
    </script>
@endsection