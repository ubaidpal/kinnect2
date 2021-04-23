<?php

namespace App\Http\Controllers\Admin;

use App\Events\SendEmail;
use App\Repository\Eloquent\Admin\SettingsRepository;
use App\User;
use Bican\Roles\Models\Role;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use kinnect2Store\Store\StoreAlbumPhotos;
use kinnect2Store\Store\StoreClaim;
use kinnect2Store\Store\StoreDispute;
use kinnect2Store\Store\StoreOrder;
use kinnect2Store\Store\StoreProduct;
use kinnect2Store\Store\StoreStorageFiles;
use kinnect2Store\Store\StoreTransaction;
use Redirect;

class UsersController extends Controller
{
    protected $user_id = NULL;
    protected $user;

    public function __construct() {
        if(isset(\Auth::user()->id)) {
            $this->user_id = \Auth::user()->id;
            $this->user    = \Auth::user();
        }

    }

    public function index() {
        $data['totalSaleSum']        = StoreTransaction::where('type', \Config::get('constants_brandstore.STATEMENT_TYPES.SALE'))
                                                       ->sum('amount');
        $data['totalWithdrawSum']    = StoreTransaction::where('type', \Config::get('constants_brandstore.STATEMENT_TYPES.WITHDRAW'))
                                                       ->sum('amount');
        $data['totalWorldPaySum']    = $data['totalSaleSum'] + $data['totalWithdrawSum'];
        $data['totalBrandsCount']    = User::where('user_type', '=', \Config::get('constants.BRAND_USER'))->count();
        $data['totalConsumersCount'] = User::where('user_type', '=', \Config::get('constants.REGULAR_USER'))->count();
        $data['totalProductsCount']  = StoreProduct::count();

        // <editor-fold desc="Claims">
        $data['openClaimsCount'] = StoreClaim::
        where('status', '!=', \Config::get('admin_constants.CLAIM_STATUS.NOT_ASSIGNED'))
                                             ->where('status', '!=', \Config::get('admin_constants.CLAIM_STATUS.ASSIGNED'))
                                             ->count();

        $data['resolvedClaimsCount'] = StoreClaim::
        where('status', '!=', \Config::get('admin_constants.CLAIM_STATUS.RESOLVED'))->count();
        // </editor-fold>

        // <editor-fold desc="Disputes">
        $data['openDisputeCount'] = StoreDispute::
        where('status', '!=', \Config::get('constants_brandstore.DISPUTE_STATUS.RESOLVED'))
                                                ->where('status', '!=', \Config::get('constants_brandstore.DISPUTE_STATUS.DISPUTE_CANCELLED_BUYER'))
                                                ->count();

        $data['acceptedDisputeCount'] = StoreDispute::
        where('status', \Config::get('constants_brandstore.DISPUTE_STATUS.DISPUTE_ACCEPTED'))->count();

        $data['rejectedDisputeCount'] = StoreDispute::
        where('status', \Config::get('constants_brandstore.DISPUTE_STATUS.DISPUTE_CANCELLED_SELLER'))->count();

        $data['rejectedDisputeCount'] = StoreDispute::
        where('status', \Config::get('constants_brandstore.DISPUTE_STATUS.DISPUTE_CANCELLED_SELLER'))->count();
        // </editor-fold>

        // <editor-fold desc="Top Ten Brands">

        $topTenBrands = StoreOrder::select('seller_id')->get();

        $topTenBrandsInfoIds = [];
        foreach ($topTenBrands as $topTenBrandsOrders) {
            if(isset($topTenBrandsInfo[$topTenBrandsOrders->seller_id])) {
                $topTenBrandsInfoIds[$topTenBrandsOrders->seller_id] = $topTenBrandsInfoIds[$topTenBrandsOrders->seller_id] + 1;
            } else {
                $topTenBrandsInfoIds[$topTenBrandsOrders->seller_id] = 1;
            }
        }

        $data['topTenBrandsInfo'] = [];
        $topTenBrandsCount        = 0;

        foreach ($topTenBrandsInfoIds as $key => $orderCount) {
            $topTenBrandsCount++;

            if($topTenBrandsCount > 10) {
                break;
            }

            $userInfo = getUserEmailAndUsername($key);

            if(isset($userInfo->email) AND isset($userInfo->username)) {
                array_push($data['topTenBrandsInfo'], ucfirst($userInfo->username) . "+_+" . $userInfo->email);
            }
        }
        $data['topTenBrands'] = $data['topTenBrandsInfo'];
        // </editor-fold>

        return view("admin.user.dashboard", $data)->with('title', 'Kinnect2 Store: Home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $default       = ['0' => 'Select Country'];
        $countriesList = DB::table('countries')->lists('name', 'id');
        $countriesList = $default + $countriesList;

        $user = \Auth::user();
        if($user->is('super.admin')) {
            $rollList = DB::table('roles')->orderBy('name', 'ASC')->get();
        } elseif($user->is('dispute.manager')) {
            $rollList = Role::where('slug', 'arbitrator')->get();
        } elseif($user->is('accounts.manager')) {
            $rollList = Role::where('slug', 'accountant')->get();
        }

        return view("admin.user.create", $rollList)->with('countries', $countriesList)->with('rolls', $rollList);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $this->validate($request, ['roll' => 'required', 'countries' => 'required', 'first_name' => 'required', 'last_name' => 'required', 'email' => 'required|email', 'password' => 'required|min:7', 'retype_password' => 'required|min:7|same:password',

        ]);

        list($role, $roleName) = explode('-', $request->roll);

        $roleID = \Config::get('constants.' . strtoupper($roleName));

        $user = User::where('email', '=', $request->email)->first();
        if($request->password != $request->retype_password) {
            return Redirect::back()->withErrors('New password and conformed password does not match');
        }
        if($user === NULL) {

            $activation_code = str_random(60) . "_email_" . $request->email;

            $user                  = new User();
            $user->name            = $request->first_name;
            $user->first_name      = $request->first_name;
            $user->last_name       = $request->last_name;
            $user->email           = $request->email;
            $user->country         = $request->countries;
            $user->password        = bcrypt($request->password);
            $user->user_type       = $roleID;
            $user->username        = $request->first_name . ' ' . $request->last_name;
            $user->displayname     = $request->first_name . ' ' . $request->last_name;
            $user->activation_code = $activation_code;
            $dt                    = Carbon::Now();
            $dt->addDays(29);
            $user->token_expiry_date = $dt;

            $user->save();
            $user = User::find($user->id);
            $user->attachRole($role);

            // $this->sendEmail($user);

            //<editor-fold desc="sending email to special user to activate account">
            if(isset($user->email)) {
                $emailData = array('subject' => 'Activation email.', 'message' => 'Click to activate your account', 'from' => \Config::get('admin_constants.SUPER_ADMIN_100_EMAIL'), 'name' => 'Kinnect2 Super Admin', 'template' => 'adminActivateAccount', 'to' => $user->email, 'type' => $user->user_type, 'email' => $user->email, 'activation_code' => $user->activation_code,);

                \Event::fire(new SendEmail($emailData));
            }
            // </editor-fold>

            return redirect()->route('admin.users')->with('info', 'Email send to user..');
        } else {
            return Redirect::back()->withErrors('This email already exist try another one..');
        }
    }

    public function activateAdminAccount($activation_code) {
        if(isset($activation_code)) {
            $original_activation_code = $activation_code;
            $activation_code          = explode("_email_", $activation_code);

            if(isset($activation_code[0])) {
                $code = $activation_code[0];
            }

            if(isset($activation_code[1])) {
                $email = $activation_code[1];
            }

            $user = User::where('activation_code', $original_activation_code)->where('email', $email)->first();

            if(isset($user->id)) {
                $user->active          = 1;
                $user->activation_code = '';
                $user->save();

                \Session::flash('message', \Lang::get('auth.successActivated'));
                \Auth::login($user);

                return redirect('/');
            }

            \Session::flash('message', \Lang::get('auth.unsuccessful'));

            return redirect('/');
        }

        \Session::flash('message', \Lang::get('auth.unsuccessful'));

        return redirect('/');

    }


    //    public function sendEmail(User $user) {
    //
    //        $data = array(
    //            'name' => $user->name,
    //            'code' => $user->activation_code,
    //        );
    //
    //        \Mail::queue('emails.activateAccount', $data, function ($message) use ($user) {
    //            $message->subject(\Lang::get('auth.pleaseActivate'));
    //            $message->to($user->email);
    //        });
    //    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show() {

        $users['users'] = User::where('user_type', '!=', '1')->where('user_type', '!=', '2')->orderBy('id', 'DESC')
                              ->where('id', '<>', $this->user_id)->orderBy('id', 'DESC')->paginate(25);

        return view("admin.user.storeUserManagement", $users);

    }

    public function normal_users() {
        $users['users'] = User::where(function ($query) {
                        $query->where('user_type', '=', \Config::get('constants.BRAND_USER'))
                            ->orWhere('user_type', '=', \Config::get('constants.REGULAR_USER'));
                        })
                        ->orderBy('id', 'DESC')
                        ->paginate(25);
        
        return view("admin.user.normal-users", $users);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request) {

        $users['users'] = User::where('id', $request->id)->first();

        $default            = ['0' => 'Select Country'];
        $countriesList      = DB::table('countries')->lists('name', 'id');
        $users['countries'] = $default + $countriesList;

        $roll     = ['0' => 'Select user type'];
        $rollList = DB::table('roles')->lists('name', 'id');

        $users['rolls'] = $roll + $rollList;
        $myRole         = DB::table('role_user')->where('user_id', $request->id)->first();
        $settingRepo = new SettingsRepository();
        $users['permissions'] = $settingRepo->getUsersPermissions($request->id);
        $users['allPermissions'] = $settingRepo->getAllPermissions();

        return view("admin.user.edit", $users)->with('myRole', $myRole);

    }

    public function normal_users_edit(Request $request) {

        $users['users'] = User::where('id', $request->id)->first();

        return view("admin.user.normal_user_edit", $users);

    }
    /* public function superAdminEdit(Request $request)
     {
         $users['users'] = User::where('id', $request->id)->first();
         return view("admin.user.superAdminEdit", $users);
     }*/

    /* public function superAdminUpdate($user_id, Request $request)
     {

         $this->validate($request, [
             'password' => 'required|min:7',
             'retype_password' => 'required|min:7|same:password',

         ]);
         $user = User::where('id', $user_id)->first();
         if (!empty($request->get('password'))) {
             $user->password = bcrypt($request->password);
         }
         $user->save();

         return Redirect::back()->with('info' , 'Password change Successful..');
         //return redirect()->route('admin.users');

     }*/
    public function changePassword() {

        return view("admin.user.adminEdit")->with('title', 'Change Password');
    }

    public function adminUpdatePassword($user_id, Request $request) {

        $this->validate($request, ['old_password' => 'required', 'password' => 'required|min:7|different:old_password|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!@#$%^&*]).*$/', 'retype_password' => 'required|min:7|same:password',

        ]);
        $oldPassword      = $request->old_password;
        $id               = $this->user_id;
        $user             = User::find($id);
        $current_password = $user->password;
        if(\Hash::check($oldPassword, $current_password)) {
            $user = User::where('id', $user_id)->first();
            if(!empty($request->get('password'))) {
                $user->password = bcrypt($request->password);
            }
            $user->save();
        } else {
            return Redirect::back()->withErrors('Old password is incorrect.');
        }

        //return redirect()->route('admin.users');
        return Redirect::back()->with('info', 'Password change Successful..');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($user_id, Request $request) {
        $this->validate($request, ['countries' => 'required', 'first_name' => 'required', 'last_name' => 'required', 'email' => 'required|email', 'password' => 'required|min:7', 'retype_password' => 'required|min:7|same:password',

        ]);

        list($role, $roleName) = explode('-', $request->roll);

        $roleID = \Config::get('constants.' . strtoupper($roleName));

        $user = User::where('email', '=', $request->email)->where('id', '<>', $user_id)->first();

        if($request->password != $request->retype_password) {
            return Redirect::back()->withErrors('New password and conformed password does not match');
        }
        if($user === NULL) {

            $user             = User::where('id', $user_id)->first();
            $user->name       = $request->first_name;
            $user->first_name = $request->first_name;
            $user->last_name  = $request->last_name;
            $user->email      = $request->email;
            $user->country    = $request->countries;
            if(!empty($request->get('password'))) {
                $user->password = bcrypt($request->password);
            }
            $user->user_type   = $roleID;
            $user->username    = $request->first_name . ' ' . $request->last_name;
            $user->displayname = $request->first_name . ' ' . $request->last_name;
            $user->save();
            $user = User::find($user->id);
            $user->roles()->sync([$role]);
            $settingRepo = new SettingsRepository();
            $settingRepo->updateUserPermissaions($user, $request->permissions);
            return redirect()->route('admin.users');
        } else {
            return Redirect::back()->withErrors('This email already exist try another one..');
        }
    }

    public function update_normal_user($user_id, Request $request) {
        $this->validate($request, ['email' => 'required|email', 'password' => 'required|min:7', 'retype_password' => 'required|min:7|same:password',

        ]);

        $user = User::find($user_id);

        $user->email    = Input::get('email');
        $user->password = bcrypt($request->password);
        $user->save();
        return redirect()->route('normal.users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy() {
        $user_id = Input::get('user_id');

        $user = User::where('id', $user_id)->delete();

        return 1;
    }

    public function userActiveDisabled() {

        $user_id = Input::get('user_id');
        $user    = Input::get('user');
        if($user == 1) {

            $user = User::where('id', $user_id)->update(array('active' => 0,));
        } else {
            $user = User::where('id', $user_id)->update(array('active' => 1,));
        }

        return 1;
    }

    function getUserPhotoSrc($file_id = NULL, $photo_id = NULL, $owner_id = NULL, $image_size_type = NULL) {
        if(isset($owner_id)) {

            $photo = StoreAlbumPhotos::where('owner_id', $owner_id)->where('owner_type', 'product')->select('file_id')
                                     ->orderBy('album_id', 'DESC')->first();

            if(isset($photo->file_id)) {
                if($image_size_type != NULL) {
                    $file = StoreStorageFiles::where('type', $image_size_type)->where('parent_file_id', $photo->file_id)
                                             ->first();
                } else {
                    $file = StoreStorageFiles::where('file_id', $photo->file_id)->first();
                }

                if(isset($file->storage_path)) {
                    return \Config::get('constants_activity.PHOTO_URL') . $file->storage_path . '?type=' . urlencode($file->mime_type);
                }

                return '';
            }
        }
    }

    public function admin_login($id) {

        \Auth::logout();
        \Auth::loginUsingId($id);
        return redirect()->back();
    }

    public function search_user() {

        $user = User::where(function ($query) {
            $query->where('user_type', '=', \Config::get('constants.BRAND_USER'))
                  ->orWhere('user_type', '=', \Config::get('constants.REGULAR_USER'));
        })->orderBy('displayname', 'ASC');

        if(Input::has('username') && !empty(Input::get('username'))) {
            $name             = Input::get('username');
            $user             = $user->where('displayname', 'like', "%$name%");
            $data['username'] = $name;
        }

        if(Input::has('email') && !empty(Input::get('email'))) {
            $email         = Input::get('email');
            $user          = $user->where('email', 'like', "%$email%");
            $data['email'] = $email;
        }

        $data['users'] = $user->paginate(25);

        //echo '<tt><pre>'; print_r($data); //die;
        return view("admin.user.normal-users", $data);
    }

}
