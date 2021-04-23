@extends('layouts.masterDynamic')
@section('content')
    @include('includes.ads-left-nav')
	<!--Create Album-->
	<div class="community-ad">
 	<div class="content-gray-title mb10">
        <h4>Advertising</h4>
        <a class="btn fltR" href="javascript:();">Create an Ad</a>
    </div>
  <div class="community-ad-item">
   <a class="community-item-img" href="javascript:();">
    <img alt="image" src="{!! asset('local/public/assets/images/community-img-1.jpg') !!}">
   </a>
   <div class="community-ad-txt">
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut, quo quibusdam vel sed. Modi odit ipsum possimus ea pariatur eos non qui atque dolore rerum fugit, aut velit autem vitae sint eligendi incidunt ut libero quod consequuntur officiis recusandae exercitationem inventore delectus. Dicta quod error aspernatur quidem fuga nemo minus.</p>
   </div>
  </div>


  <div class="community-ad-item">
   <a class="community-item-img" href="javascript:();">
    <img alt="image" src="{!! asset('local/public/assets/images/community-img-2.jpg') !!}">
   </a>
   <div class="community-ad-txt">
    <p>Lorem ipsum dolor sit amet.</p>
   </div>
  </div>


  <div class="community-ad-item">
   <a class="community-item-img" href="javascript:();">
    <img alt="image" src="{!! asset('local/public/assets/images/community-img-1.jpg') !!}">
   </a>
   <div class="community-ad-txt">
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut, quo quibusdam vel sed. Modi odit ipsum possimus ea pariatur eos non qui atque dolore rerum fugit.</p>
   </div>
  </div>


  <div class="community-ad-item">
   <a class="community-item-img" href="javascript:();">
    <img alt="image" src="{!! asset('local/public/assets/images/community-img-2.jpg') !!}">
   </a>
   <div class="community-ad-txt">
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut, quo quibusdam vel sed.</p>
   </div>
  </div>


  <div class="community-ad-item">
   <a class="community-item-img" href="javascript:();">
    <img alt="image" src="{!! asset('local/public/assets/images/community-img-1.jpg') !!}">
   </a>
   <div class="community-ad-txt">
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut, quo quibusdam vel sed. Modi odit ipsum possimus ea pariatur eos non qui atque dolore rerum fugit, aut velit autem vitae sint eligendi incidunt ut libero quod consequuntur officiis recusandae exercitationem inventore delectus. Dicta quod error aspernatur quidem fuga nemo minus.</p>
   </div>
  </div>

 </div>
@endsection