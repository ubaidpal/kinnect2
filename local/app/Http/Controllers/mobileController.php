<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller\View;

class mobileController extends Controller
{
	
	public function index()
    {
       return view('Pages.index');
    }
	public function signup()
    {
       return view('Pages.signup');
    }
	public function signin()
    {
       return view('Pages.signin');
    }
	public function signupConsumer()
    {
       return view('Pages.signupConsumer');
    }
	public function signupBrand()
    {
       return view('Pages.signupBrand');
    }
	public function changePassword()
    {
       return view('Pages.changePassword');
    }
	public function dashboard()
    {
       return view('Pages.dashboard');
    }
	public function dashboardPostview()
    {
       return view('Pages.dashboardPostview');
    }
	public function miscellaneous()
    {
       return view('Pages.miscellaneous');
    }
	public function leftNav()
    {
       return view('Pages.leftNav');
    }
	public function friends()
    {
       return view('Pages.friends');
    }
	public function brands()
    {
       return view('Pages.brands');
    }
	public function createPoll()
    {
       return view('Pages.createPoll');
    }
	public function pollAll()
    {
       return view('Pages.pollAll');
    }
	public function pollMy()
    {
       return view('Pages.pollMy');
    }
	public function pollResults()
    {
       return view('Pages.pollResults');
    }
	public function pollVote()
    {
       return view('Pages.pollVote');
    }
	public function battleView()
    {
       return view('Pages.battleView');
    }
	public function messages()
    {
       return view('Pages.messages');
    }
	public function notAvailable()
    {
       return view('Pages.notAvailable');
    }
	public function lostPassword()
    {
       return view('Pages.lostPassword');
    }
	public function resetPassword()
    {
       return view('Pages.resetPassword');
    }
	public function faq()
    {
       return view('Pages.faq');
    }
	
	
}
?>