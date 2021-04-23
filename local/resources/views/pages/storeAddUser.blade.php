@extends('layouts.store-admin')
@section('content')
    <!-- Post Div-->
    @include('includes.arbitrator-leftnav')
 	<div class="ad_main_wrapper">
       <div class="task_inner_wrapper">
    	<div class="main_heading">
             <h1>Add User</h1>
        </div>
        <div class="assigned-task-wrapper">
        	<div class="add-form-block">
            	<div class="user-title">First Name * :</div>
                <div class="user-input"><input type="text"></div>
            </div>
            <div class="add-form-block">
            	<div class="user-title">Last Name * :</div>
                <div class="user-input"><input type="text"></div>
            </div>
            <div class="add-form-block">
            	<div class="user-title">Email * :</div>
                <div class="user-input"><input type="text"></div>
            </div>
            <div class="add-form-block">
            	<div class="user-title">Password * :</div>
                <div class="user-input"><input type="text"></div>
            </div>
            <div class="add-form-block">
            	<div class="user-title">Retype Password * :</div>
                <div class="user-input"><input type="text"></div>
            </div>
            <div class="add-form-block">
            	<div class="user-title">User Type * :</div>
                <div class="user-input"><select><option>Select user type</option></select></div>
            </div>
            <div class="add-form-block">
            	<div class="user-title">&nbsp;</div>
                <div class="user-input"><a href="#" class="orngBtn">Save</a><a href="#" class="grey_btn">Cancel</a></div>
            </div>
        </div>
    </div>
   	</div>
@endsection