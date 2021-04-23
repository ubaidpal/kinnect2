<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller\View;

class pagesController extends Controller
{

    public function signUp()
    {
       return view('pages.signUp');
    }
	public function index()
    {
       return view('pages.index');
    }
	public function activityLog()
    {
       return view('pages.activityLog');
    }
	
	public function userProfile()
    {
       return view('pages.userProfile');
    }
	public function album()
    {
       return view('pages.album');
    }
	public function albumPhotos()
    {
       return view('pages.albumPhotos');
    }	
	public function battles()
    {
       return view('pages.battles');
    }	
	public function battleBrowse()
    {
       return view('pages.battleBrowse');
    }
	public function battleDetail()
    {
       return view('pages.battleDetail');
    }
	public function brands()
    {
       return view('pages.brands');
    }
	public function createAlbum()
    {
       return view('pages.createAlbum');
    }
	public function myPolls()
    {
       return view('pages.myPolls');
    }	
	public function createPoll()
    {
       return view('pages.createPoll');
    }
	public function createDiscussionTopic()
    {
       return view('pages.createDiscussionTopic');
    }	
	public function createEvent()
    {
       return view('pages.createEvent');
    }
	public function createMusic()
    {
       return view('pages.createMusic');
    }
	public function friendRequests()
    {
       return view('pages.friendRequests');
    }
	public function wallPhotos()
    {
       return view('pages.wallPhotos');
    }
	public function communityAd()
    {
       return view('pages.communityAd');
    }
	public function discussions()
    {
       return view('pages.discussions');
    }
	public function kinnectors()
    {
       return view('pages.kinnectors');
    }
	public function createBattle()
    {
       return view('pages.createBattle');
    }
	public function storeAdmin()
    {
       return view('pages.storeAdmin');
    }
	public function storeProductDetail()
    {
       return view('pages.storeProductDetail');
    }
	public function storeProductRange()
    {
       return view('pages.storeProductRange');
    }
	public function userProfileInfo()
    {
       return view('pages.userProfileInfo');
    }
	public function storeFeatured()
    {
       return view('pages.storeFeatured');
    }
	public function createGroup()
    {
       return view('pages.createGroup');
    }
	public function storeManagePro()    {
		
       return view('pages.storeManagePro');
    }
	public function storeProCategory()    {
		
       return view('pages.storeProCategory');
    }
	public function storeProSubCategory()    {
		
       return view('pages.storeProSubCategory');
    }
	public function storeAddProduct()    {
		
       return view('pages.storeAddProduct');
    }
	public function editProfile()    {
		
       return view('pages.userProfile');
    }
	public function shippingAddress() {
		
       return view('pages.shippingAddress');
    }
	public function shoppingCart() {
		
       return view('pages.shoppingCart');
    }
	public function pollsDetail() {
		
       return view('pages.pollsDetail');
    }
	public function addBankAccount() {
		
       return view('pages.addBankAccount');
    }
	public function storeOrderSuccessful() {
		
       return view('pages.storeOrderSuccessful');
    }
	public function storeTotalEarnings() {
		
       return view('pages.storeTotalEarnings');
    }
	public function storeManagerPanelOrders() {
		
       return view('pages.storeManagerPanelOrders');
    }
	
	public function reviewOrder() {
		
       return view('pages.reviewOrder');
    }
	public function groupDetail() {
		
       return view('pages.groupDetail');
    }
	public function privacySetting() {
		
       return view('pages.privacySetting');
    }
	public function generalSetting() {
		
       return view('pages.generalSetting');
    }
	public function networkSetting() {
		
       return view('pages.networkSetting');
    }
	public function notificationSetting() {
		
       return view('pages.notificationSetting');
    }
	public function changePassword() {
		
       return view('pages.changePassword');
    }
	public function deleteAccount() {
		
       return view('pages.deleteAccount');
    }
	public function event() {
		
       return view('pages.event');
    }
	public function eventDetail() {
		
       return view('pages.eventDetail');
    }
	public function eventInformation() {
		
       return view('pages.eventInformation');
    }
	public function myCampaigns() {
		
       return view('pages.myCampaigns');
    }
	public function reports() {
		
       return view('pages.reports');
    }

    public function terms() {

        return view('policy.terms')->with('title' , 'Terms and Conditions');
    }
    public function condition() {

        return view('policy.condition')->with('title' , 'Privacy Policy');
    }
	public function help_center() {

        return view('pages.help_center');
    }
	public function error_404() {

        return view('pages.error_404');
    }
	public function maintenance() {

        return view('pages.maintenance');
    }
	public function error_505() {

        return view('pages.error_505');
    }
	
	public function storeDisputeDetail() {

        return view('pages.storeDisputeDetail');
    }
	public function storePaymentMethod() {

        return view('pages.storePaymentMethod');
    }
	public function storeArbitrator() {

        return view('pages.storeArbitrator');
    }
	public function storeUserManagement() {

        return view('pages.storeUserManagement');
    }
	public function storeAddUser() {

        return view('pages.storeAddUser');
    }
	public function storeDisputePopup() {

        return view('pages.storeDisputePopup');
    }
	public function storeUnassigned() {

        return view('pages.storeUnassigned');
    }
	public function storeOrderDetail() {

        return view('pages.storeOrderDetail');
    }
	public function storeShippingMethod() {

        return view('pages.storeShippingMethod');
    }
	public function storeDisputeCase() {

        return view('pages.storeDisputeCase');
    }
	public function storeAdminStoreManagement() {

        return view('pages.storeAdminStoreManagement');
    }
	public function storeWithdrawlRequest() {

        return view('pages.storeWithdrawlRequest');
    }
	public function storeRequestWithdrawls() {

        return view('pages.storeRequestWithdrawls');
    }
	public function storeStatement() {

        return view('pages.storeStatement');
    }
	public function storeBankDetailPopup() {

        return view('pages.storeBankDetailPopup');
    }
	public function shippingAddressMultiple() {

        return view('pages.shippingAddressMultiple');
    }
	public function storeEnterInfo	() {

        return view('pages.storeEnterInfo');
    }
	public function storeWithdrawalPopup() {

        return view('pages.storeWithdrawalPopup');
    }
	public function storeCreatePopup() {

        return view('pages.storeCreatePopup');
    }


	

    public function faq() {

        return view('policy.faq')->with('title' , 'Faq');
    }
}
?>
