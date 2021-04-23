@extends('layouts.store-admin')
@section('content')
    <!-- Post Div-->
    @include('includes.arbitrator-leftnav')
 	<div class="ad_main_wrapper">
       	<div class="task_inner_wrapper">
       		<!-- Store Dispute Popup -->
            <div class="sd-popup">
                <div class="sdp-header">Revise Feedback</div>
                <div class="sd-orderId">Order ID: 65405850239847</div>
                <form class="form-container wA">
                    <label>*Select a reason why you need to revise feedback</label>
                    <div class="field-item">
                        <select>
                            <option value="">some option</option>
                        </select>
                    </div>
                    <label>How would you revise your rating for this product?</label>
                    <div class="sd-stars"></div>
                    <label>Comment</label>
                    <textarea></textarea>
                </form>
            </div>
    	</div>
   	</div>
@endsection