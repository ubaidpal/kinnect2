@extends('layouts.login')

@section('content')
    <div class="container">
    <div class="main">
        <ul id="cbp-bislideshow" class="cbp-bislideshow">
            <li><img src="{!! asset('local/public/assets/images/login-page/bg-slider/slide-1.jpg') !!}" alt="image01"/></li>
            <li><img src="{!! asset('local/public/assets/images/login-page/bg-slider/slide-2.jpg') !!}" alt="image01"/></li>
            <li><img src="{!! asset('local/public/assets/images/login-page/bg-slider/slide-3.jpg') !!}" alt="image01"/></li>
            <li><img src="{!! asset('local/public/assets/images/login-page/bg-slider/slide-4.jpg') !!}" alt="image01"/></li>
            <li><img src="{!! asset('local/public/assets/images/login-page/bg-slider/slide-5.jpg') !!}" alt="image01"/></li>
            <li><img src="{!! asset('local/public/assets/images/login-page/bg-slider/slide-6.jpg') !!}" alt="image01"/></li>
            <li><img src="{!! asset('local/public/assets/images/login-page/bg-slider/slide-7.jpg') !!}" alt="image01"/></li>
            <li><img src="{!! asset('local/public/assets/images/login-page/bg-slider/slide-8.jpg') !!}" alt="image01"/></li>
        </ul>
    </div>
</div>
<!--  BG Slider  -->


<!--  Login Page Content  -->
<div class="login-page-wrapper">
    <!--  Welcome Kinnect2  -->
    <div class="welcome">
        Welcome to <span>Kinnect 2</span>
        <p><span>Opinions Matter.</span> Bringing Brands</p>
        <p>& Consumers Together.</p>
    </div>
    <!--  Welcome Kinnect2 - Ends -->


    <!--  Login Container  -->
    <div class="login-container fltR">

        <!--  Login Form  -->
        <table class="login-form" width="100%">
            <tr>
                <td><input type="text" placeholder="Email"></td>
                <td><input type="Password" placeholder="Password"></td>
                <td><a class="btn" href="javascript:();">Log In</a></td>
            </tr>
            <tr>
                <td>
                    <input type="checkbox" id="remember-me">
                    <label for="remember-me">Remember me</label>
                </td>
                <td colspan="2"> 
                    <a class="forgot-pass" href="javascript:();">Forgot Your Password?</a>
                </td>
            </tr>
        </table>
        <!--  Login Form - Ends -->

        <!--  SignUp Container  -->
        <div class="signup-container">
                <div class="signup-header">
                    <span>New to Kinnect2?</span>
                    <h2>SIGN UP</h2>
                    <span>it’s free and always will be.</span>
                </div>

                <!--  SignUp Form  -->
                <div class="signup-form">
                    <!--  Page - 1  -->
                    <div><input type="text" placeholder="Email *"></div>
                    <span>You’ll use your email address to login.</span>
                    <div><input type="password" placeholder="Password *"></div>
                    <div><input type="password" placeholder="Re-Enter Password *"></div>
                    <div><input class="profile-address mt10" type="text" placeholder="Profile Address *"></div>
                    <span>www.kinnect2.com/example123</span>
                    <select>
                      <option value="volvo">Profile Type</option>
                      <option value="first">First</option>
                      <option value="second">Second</option>
                      <option value="third">Third</option>
                    </select>
                    <div class="signup-policy">
                        By clicking Sign Up, you agree to our terms and that you have read our <a href="javascript:();">Data Policy</a>, including our <a href="javascript:();">Cookie Use</a>.
                    </div>
                    <div class="signup-pager cf">
                        <div class="circle-pager circle-pager-active"></div>
                        <div class="circle-pager"></div>
                        <div class="circle-pager"></div>
                    </div>
                    <a class="btn fltN" href="javascript:();">Next</a>

                    <div class="create-page">
                        <a href="javascript:();">Create a Page</a> for a Celebrity, Brand or Business
                    </div>


                    <!--  Page - 2  -->
                    <!--<div class="signup-personal-info">
                        <div class="signup-label">Personal Information:</div>
                        <div class="fltL mr10"><input type="text" placeholder="First Name"></div>
                        <div><input type="text" placeholder="Last Name" ></div>
                        <select>
                            <option value="gender">Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>

                        <div class="signup-dob">
                            <div class="signup-label">Date of Birth</div>
                            <select>
                                <option value="date">Date</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                            <select>
                                <option value="month">Month</option>
                                <option value="january">January</option>
                                <option value="february">february</option>
                                <option value="march">March</option>
                            </select>
                            <select>
                                <option value="gender">Year</option>
                                <option value="2015">2015</option>
                                <option value="2014">2014</option>
                            </select>
                        </div>

                        <div class="signup-contant-info">
                            <div class="signup-label">Contact Information:</div>
                            <div><input type="text" placeholder="website" ></div>
                            <div><input type="text" placeholder="Twitter" ></div>
                            <div><input type="text" placeholder="Facebook" ></div>
                        </div>

                        <div class="signup-pager cf">
                            <div class="circle-pager"></div>
                            <div class="circle-pager circle-pager-active"></div>
                            <div class="circle-pager"></div>
                        </div>
                        <div>
                            <a class="btn btn-inactive fltL" href="javascript:();">Back</a>
                            <a class="btn fltL" href="javascript:();">Next</a>
                        </div>
                    </div>-->

                    
                    <!--  Page - 3  -->
                    <!--<div class="signup-upload-img">
                        <div class="signup-label">Add Your Photo</div>
                        <img src="../webroot/images/login-page/upload-img.png" alt="image">
                        <div class="signup-label mt20">Choose New Photo</div>
                        <div class="chose-file">
                            <a class="btn btn-inactive fltL" href="javascript:();">Choose File</a>
                            <span>No file choosen</span>
                        </div>
                        <div class="signup-pager cf">
                            <div class="circle-pager"></div>
                            <div class="circle-pager"></div>
                            <div class="circle-pager circle-pager-active"></div>
                        </div>
                        <div>
                            <a class="btn fltL" href="javascript:();">Save Photo</a>
                            <a class="btn btn-inactive fltL" href="javascript:void(0);">Skip or Back</a>
                        </div>
                    </div>-->

                </div>
                <!--  SignUp Form - Ends  -->






        </div>
        <!--  SignUp Container - Ends -->
    </div>
    <!--  Login Container - Ends -->

</div>
<!--  Login Page Content  -->

@endsection
