<?php namespace App\Http\Controllers\Auth;

use App\AlbumPhoto;
use App\Classes\Kinnect2;
use App\Repository\Eloquent\SettingRepository;
use App\Repository\Eloquent\UsersRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Brand;
use App\Consumer;
use Input;
use Intervention\Image\Facades\Image;
use Jenssegers\Agent\Agent;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades;
use App\Classes\UrlFilter;
use App\Services\StorageManager;

class AuthController extends Controller
{
    protected $usersRepository;
    /**
     * @var SettingRepository
     */
    protected $settingRepository;

    //protected $redirectTo = '/profile';
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;


    /**
     * AuthController constructor.
     * @param Guard             $auth
     * @param Registrar         $registrar
     * @param UsersRepository   $usersRepository
     * @param SettingRepository $settingRepository
     */
    public function __construct(Guard $auth, Registrar $registrar, UsersRepository $usersRepository, SettingRepository $settingRepository) {
        $this->auth            = $auth;
        $this->registrar       = $registrar;
        $this->usersRepository = $usersRepository;

        $this->middleware('guest',
            ['except' =>
                 ['getLogout', 'resendEmail', 'activateAccount'],
            ]);
        $this->settingRepository = $settingRepository;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request, Kinnect2 $kinnect2) {
        $userFind = User::where('email', $request->input('email'))
            ->first();
        if (isset($userFind)) {

            if (isset($_FILES['file']['tmp_name'])) {
                if ($userFind->photo_id > 0) {
                    $albumPhoto = $this->usersRepository->getDefaultAlbum($userFind->photo_id);
                    $album_id   = $albumPhoto->album_id;
                } else {
                    $album_id = $this->usersRepository->insertDefaultAlbum($userFind->id);
                }

                $sm       = new StorageManager();
                $photo_id = $sm->saveAlbumPhoto($userFind->id, 'user', $request->file('file'), 'album_photo', 'Profile Photo', $album_id);

                $userFind->photo_id = $photo_id;
                $userFind->save();

                if ($photo_id > 0) {
                    //making thumbs
                    $folder_path = public_path('storage/temporary/users/');
                    if (!file_exists($folder_path)) {
                        if (!mkdir($folder_path, 0777, TRUE)) {
                            $folder_path = '';
                        }
                    }
                    $parent_photo = AlbumPhoto::find($photo_id);
                    $file_name    = time() . rand(111111111, 9999999999);

                    // <editor-fold desc="PROFILE_THUMB_WIDTH">
                    $image1 = Image::make($request->file('file'))->encode('jpg');
                    $image1->resize(Config::get('constants.PROFILE_THUMB_WIDTH'), Config::get('constants.PROFILE_THUMB_HEIGHT'));

                    if ($image1->save($folder_path . '/' . $file_name . '.jpg')) {
                        $photo_id = $sm->saveAlbumPhoto($userFind->id, 'user', $image1, 'album_photo', 'Profile Photo', $album_id, $parent_photo->file_id, 'thumb_profile', $parent_photo->photo_id);
                    }
                    // </editor-fold>

                    //<editor-fold desc="PROFILE_ICON_HEIGHT">
                    $image1 = Image::make($request->file('file'))->encode('jpg');;
                    $image1->resize(Config::get('constants.PROFILE_ICON_WIDTH'), Config::get('constants.PROFILE_ICON_HEIGHT'));

                    if ($image1->save($folder_path . '/' . $file_name . '.jpg')) {
                        $photo_id = $sm->saveAlbumPhoto($userFind->id, 'user', $image1, 'album_photo', 'Profile Photo', $album_id, $parent_photo->file_id, 'thumb_icon', $parent_photo->photo_id);
                    }
                    //</editor-fold>

                    // <editor-fold desc="PROFILE_NORMAL_HEIGHT">
                    $image1 = Image::make($request->file('file'))->encode('jpg');;
                    $image1->resize(Config::get('constants.PROFILE_NORMAL_WIDTH'), Config::get('constants.PROFILE_NORMAL_HEIGHT'));

                    if ($image1->save($folder_path . '/' . $file_name . '.jpg')) {
                        $photo_id = $sm->saveAlbumPhoto($userFind->id, 'user', $image1, 'album_photo', 'Profile Photo', $album_id, $parent_photo->file_id, 'thumb_normal', $parent_photo->photo_id);
                    }
                    //</editor-fold>

                    // <editor-fold desc="WALL_IMAGE_HEIGHT">
                    $image1 = Image::make($request->file('file'))->encode('jpg');;
                    $image1->resize(Config::get('constants.WALL_IMAGE_WIDTH'), Config::get('constants.WALL_IMAGE_HEIGHT'));

                    if ($image1->save($folder_path . '/' . $file_name . '.jpg')) {
                        $photo_id = $sm->saveAlbumPhoto($userFind->id, 'user', $image1, 'album_photo', 'Profile Photo', $album_id, $parent_photo->file_id, 'thumb_wall', $parent_photo->photo_id);
                    }
                    //</editor-fold>

                }

                return $kinnect2->profilePhoto($photo_id, $userFind->id, $type = NULL, 'thumb_normal');
            }

            $this->sendEmail($userFind);

            return 'saved';
        }

        $validator = $this->registrar->validator($request->except('imageData'));

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        if(session('user_type') == 1){
            $displayname = session('first_name') . ' ' . session('last_name');
            $username    = session('first_name');
        }else{
            $displayname = session('brandname');
            $username    = session('brandname');
        }

        $activation_code = str_random(60) . $request->input('email');
        $user            = new User;

        $user->name            = session('first_name') . ' ' . session('last_name');
        $user->email           = $request->input('email');
        $user->first_name      = session('first_name');
        $user->last_name       = session('last_name');
        $user->password        = bcrypt($request->input('password'));
        $user->activation_code = $activation_code;
        $user->user_type       = session('user_type');
        $user->displayname     = $displayname;
        $user->username        = $username;
        $user->website         = session('website');
        $user->facebook        = session('facebook');
        $user->twitter         = session('twitter');
        $user->country         = session('country');
        $user->timezone        = session('timezone');

        $dt = Carbon::Now();
        $dt->addDays(29);

        $user->token_expiry_date = $dt;

        if ($user->save()) {
            $user_id = $user->id;

            if ($user->user_type == Config::get('constants.REGULAR_USER')) {
                $username                 = \Kinnect2::slugify(trim(session('first_name')) . '-' . trim(session('last_name')), ['table' => 'users', 'field' => 'username']);
                $user->username           = $username;
                $consumer                 = new Consumer();
                $consumer->gender         = session('gender');
                $consumer->birthdate      = (int)session('year') . '-' . (int)session('month') . '-' . (int)session('date');
                $consumer->about_me       = session('about_me');
                $consumer->personnel_info = session('personnel_info');

                $consumer->save();
                $consumer->user()->save($user);
            }//saving user()

            if ($user->user_type == Config::get('constants.BRAND_USER')) {
                $username          = \Kinnect2::slugify(trim(session('brandname')), ['table' => 'users', 'field' => 'username']);
                $user->username    = $username;
                $user->displayname = session('brandname');
                $brand             = new Brand();
                $brand->brand_name = session('brandname');
                //$brand->brand_history = session('brand_history');
                //$brand->description = session('description');
                $brand->save();
                $brand->user()->save($user);
            }//saving brand

            //Saving profile photo

            if ($user_id > 0) {
                if (isset($_FILES['file']['tmp_name'])) {
                    $tmp_file_path = $_FILES['file']['tmp_name'];
                } else {
                    $tmp_file_path = asset('/local/public/images/login-page/upload-img.png');
                }

                $album_id = $this->usersRepository->insertDefaultAlbum($user_id);
                if ($request->hasFile('file')) {
                    $sm       = new StorageManager();
                    $photo_id = $sm->saveAlbumPhoto($user_id, 'user', $request->file('file'), 'album_photo', 'Profile Photo', $album_id);

                    //Making Thumbs
                    if ($photo_id > 0) {
                        //making thumbs
                        $folder_path = public_path('storage/temporary/groups/');
                        if (!file_exists($folder_path)) {
                            if (!mkdir($folder_path, 0777, TRUE)) {
                                $folder_path = '';
                            }
                        }
                        $parent_photo = AlbumPhoto::find($photo_id);
                        $file_name    = time() . rand(111111111, 9999999999);

                        // <editor-fold desc="PROFILE_THUMB_WIDTH">
                        $image1 = Image::make($request->file('file'))->encode('jpg');;
                        $image1->resize(Config::get('constants.PROFILE_THUMB_WIDTH'), Config::get('constants.PROFILE_THUMB_HEIGHT'));

                        if ($image1->save($folder_path . '/' . $file_name . '.jpg')) {
                            $photo_id = $sm->saveAlbumPhoto($user_id, 'user', $image1, 'album_photo', 'Profile Photo', $album_id, $parent_photo->file_id, 'thumb_profile', $parent_photo->photo_id);
                        }
                        // </editor-fold>

                        //<editor-fold desc="PROFILE_ICON_HEIGHT">
                        $image1 = Image::make($request->file('file'))->encode('jpg');;
                        $image1->resize(Config::get('constants.PROFILE_ICON_WIDTH'), Config::get('constants.PROFILE_ICON_HEIGHT'));

                        if ($image1->save($folder_path . '/' . $file_name . '.jpg')) {
                            $photo_id = $sm->saveAlbumPhoto($user_id, 'user', $image1, 'album_photo', 'Profile Photo', $album_id, $parent_photo->file_id, 'thumb_icon', $parent_photo->photo_id);
                        }
                        //</editor-fold>

                        // <editor-fold desc="PROFILE_NORMAL_HEIGHT">
                        $image1 = Image::make($request->file('file'))->encode('jpg');;
                        $image1->resize(Config::get('constants.PROFILE_NORMAL_WIDTH'), Config::get('constants.PROFILE_NORMAL_HEIGHT'));

                        if ($image1->save($folder_path . '/' . $file_name . '.jpg')) {
                            $photo_id = $sm->saveAlbumPhoto($user_id, 'user', $image1, 'album_photo', 'Profile Photo', $album_id, $parent_photo->file_id, 'thumb_normal', $parent_photo->photo_id);
                        }
                        //</editor-fold>

                        // <editor-fold desc="WALL_IMAGE_HEIGHT">
                        $image1 = Image::make($request->file('file'))->encode('jpg');;
                        $image1->resize(Config::get('constants.WALL_IMAGE_WIDTH'), Config::get('constants.WALL_IMAGE_HEIGHT'));

                        if ($image1->save($folder_path . '/' . $file_name . '.jpg')) {
                            $photo_id = $sm->saveAlbumPhoto($user_id, 'user', $image1, 'album_photo', 'Profile Photo', $album_id, $parent_photo->file_id, 'thumb_wall', $parent_photo->photo_id);
                        }
                        //</editor-fold>
                    }
                    //End of Making thumbs

                    $user->photo_id = $photo_id;
                    $user->save();
                }
                $this->settingRepository->saveAllSetting($user->id);
            }
            $request->session()->flush();
            $this->sendEmail($user);
            $agent = new Agent();
            if ($agent->isMobile() || $agent->isTablet()) {
                return 'done';
            }

            return $kinnect2->profilePhoto(@$photo_id, $user_id, $type = NULL, 'thumb_normal');
            //return 'We have sent out activation code to your email:<strong> '. $request->input('email').' </strong> check you inbox to activate your account, check your spam/junk folder too.';
        } else {

            \Session::flash('message', \Lang::get('notCreated'));

            return redirect()->back()->withInput();

        }
    }

    public function sendEmail(User $user) {

        $data = array(
            'name' => $user->name,
            'code' => $user->activation_code,
            'email' => $user->email,
            'name' => $user->first_name.' '.$user->last_name
        );

        \Mail::queue('emails.activateAccount', $data, function ($message) use ($user) {
            $message->subject(\Lang::get('auth.pleaseActivate'));
            $message->to($user->email);
            $message->from("kinnect2@no-reply.com");
        });
    }

    public function resendEmail() {
        $user = \Auth::user();
        if ($user->resent >= 5) {
            return view('auth.tooManyEmails')
                ->with('email', $user->email);
        } else {
            if ($user->deleted == 1) {
                $activation_code       = str_random(60) . $user->email;
                $user->activation_code = $activation_code;
                $user->deleted         = 0;
            }

            $user->resent = $user->resent + 1;
            $dt           = Carbon::Now();
            $dt->addDays(30);

            $user->token_expiry_date = $dt;

            $user->save();
            $this->sendEmail($user);

            return view('auth.activateAccount')
                ->with('email', $user->email);
        }
    }

    public function activateAccount($code, User $user) {

        if ($user->accountIsActive($code)) {
            if (\Auth::user()->user_type == 1) {
                \Session::flash('message', \Lang::get('auth.successActivated'));

                return redirect('after_activation_follow_brands');
            } else {
                \Session::flash('message', \Lang::get('auth.successActivated'));

                return redirect('/');
            }

        }

        \Session::flash('message', \Lang::get('auth.unsuccessful'));

        return redirect('/');

    }

    public function userAlreadyRegistered(Request $request)
    {
        $data['email'] = $request->email;
        return view("auth.activateAccount", $data);
    }

    public function stepOne(Request $request) {

        $validator = Validator::make(
            [
                'password' => $request->password,
            ],
            [
                'password' => 'required|min:6',
            ]
        );

        if (!$request->back) {

            $alreadyExists = User::where("email", $request->email)->first();

            if(isset($alreadyExists->id)){
                if($alreadyExists->activation_code > '' and $alreadyExists->verified == 0){
                    $validator = Validator::make(
                        [
                            'email'                 => $request->email,
                            //                    'first_name' => $request->first_name,
                            'user_type'             => $request->user_type,
                            'password'              => $request->password,
                            'password_confirmation' => $request->password_confirmation,
                        ],
                        [
                            'password'              => 'required|min:7|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{7,}$/|confirmed',
                            'password_confirmation' => 'required|min:7',
                            'user_type'             => 'required',
                        ],
                        [
                            'required' => ':attribute field is required',
                        ]
                    );
                }else{
                    $validator = Validator::make(
                        [
                            'email'                 => $request->email,
                            //                    'first_name' => $request->first_name,
                            'user_type'             => $request->user_type,
                            'password'              => $request->password,
                            'password_confirmation' => $request->password_confirmation,
                        ],
                        [
                            'password'              => 'required|min:7|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{7,}$/|confirmed',
                            'password_confirmation' => 'required|min:7',
                            'user_type'             => 'required',
                            'email'                 => 'required|email|unique:users',
                        ],
                        [
                            'required' => ':attribute field is required',
                        ]
                    );
                }
            }

            $validator = Validator::make(
                [
                    'email'                 => $request->email,
                    //                    'first_name' => $request->first_name,
                    'user_type'             => $request->user_type,
                    'password'              => $request->password,
                    'password_confirmation' => $request->password_confirmation,
                ],
                [
                    'password'              => 'required|min:7|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{7,}$/|confirmed',
                    'password_confirmation' => 'required|min:7',
                    'user_type'             => 'required',
                    'email'                 => 'required|email|unique:users',
                ],
                [
                    'required' => trans('validation.custom.required'),
                ]
            );

            Session(
                [
                    'email'                 => $request->email,
                    'password'              => $request->password,
                    'password_confirmation' => $request->password_confirmation,
                    'user_type'             => $request->user_type,
                    'timezone'              => $request->timezone,
                ]
            );
        }

        if ($validator->fails()) {
            $data['signup'] = 'signup';

            $agent          = new Agent();

            if ($agent->isMobile() || $agent->isTablet()) {
                $data['email']                 = $validator->errors()->first('email');
                $data['user_type']             = $validator->errors()->first('user_type');
                $data['password_confirmation'] = $validator->errors()->first('password_confirmation');
                $data['password']              = $validator->errors()->first('password');

                return $data;

            }

            return view('auth.partials.step_one', $data)->withErrors($validator->errors(), 'signup');
        } else {
           // $data['countries'] = ['' => 'Select Country *'];
            $data['countries'] = $this->allCountries();//array_merge($data['countries'], $this->allCountries());

            if ($request->user_type == \Config::get('constants.REGULAR_USER')) {
                $data['dates'] = ['0' => 'Day *'];
                for ($i = 1; $i < 32; $i++) {
                    $data['dates'] = array_merge($data['dates'], array($i => $i));
                }

                $data['months'] = [
                    ''   => 'Month *',
                    '1'  => 'January',
                    '2'  => 'February',
                    '3'  => 'March',
                    '4'  => 'April',
                    '5'  => 'May',
                    '6'  => 'June',
                    '7'  => 'July',
                    '8'  => 'August',
                    '9'  => 'September',
                    '10' => 'October',
                    '11' => 'November',
                    '12' => 'December',
                ];
                $years          = ['' => 'Year *'];
                for ($i = date('Y'); $i > 1900; $i--) {
                    $years[$i] = $i;
                }

                $agent = new Agent();
                if ($agent->isMobile() || $agent->isTablet()) {
                    if ($request->user_type == 1) {
                        $view = 'signup-consumer';
                    } else {
                        $view = 'signup-brand';
                    }

                    return view('auth.partials.' . $view, $data)->with('years', $years);
                }

                return view('auth.partials.step_two', $data)->with('years', $years);
            } else {
                $data['industries'] = $this->allIndustries();

                $agent              = new Agent();
               if ($agent->isMobile() || $agent->isTablet()) {


                    return view('auth.partials.signup-brand', $data);
                }

                return view('auth.partials.step_two_brand', $data);
            }
        }

    }

    public function stepTwo(Request $request) {

        $validator = Validator::make(
            [
                'first_name' => $request->first_name,
            ],
            [
                'w_first_name' => 'required', // knowingly doing it.
            ],
            [
                'required' => ':attribute field is required',
            ]
        );

        if (!$request->back) {
            if ($request->date == 0) $request->date = '';
            if ($request->country == 0) $request->country = '';

            $validator = Validator::make(
                [
                    'gender'     => $request->gender,
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name,
                    'date'       => $request->date,
                    'month'      => $request->month,
                    'year'       => $request->year,
                    'country'    => $request->country,
                ],
                [
                    'gender'     => 'required',
                    'first_name' => 'required|alpha',
                    'last_name'  => 'required|alpha',
                    'date'       => 'required',
                    'month'      => 'required',
                    'year'       => 'required',
                    'country'    => 'required',
                ],
                [
                    'required' => ':attribute field is required',
                    'alpha'    => ':attribute may only contain letters.',
                ]
            );

            Session(
                [
                    'gender'     => $request->gender,
                    'username'   => $request->first_name,
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name,
                    'date'       => $request->date,
                    'month'      => $request->month,
                    'year'       => $request->year,
                    'website'    => $request->website,
                    'twitter'    => $request->twitter,
                    'facebook'   => $request->facebook,
                    'country'    => $request->country,
                ]
            );
        }

        if ($validator->fails()) {
            $data['countries'] = ['' => 'Select Country *'];
            $data['countries'] = array_merge($data['countries'], $this->allCountries());

            $data['dates'] = ['' => 'Day'];
            for ($i = 1; $i < 32; $i++) {
                $data['dates'] = array_merge($data['dates'], array($i => $i));
            }

            $data['months'] = [''    => 'Month', '1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May'
                               , '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December',
            ];

            $years = ['' => 'Year'];
            for ($i = date('Y'); $i > 1900; $i--) {
                $years[$i] = $i;
            }

            return view('auth.partials.step_two', $data)
                ->with('years', $years)
                ->withErrors($validator->errors(), 'login');
        } else {
            $agent = new Agent();
            if ($agent->isMobile() || $agent->isTablet()) {
                return 'next';
            }

            return view('auth.partials.step_three');
        }

    }

    public function stepTwoBrand(Request $request) {
        $validator = Validator::make(
            [
                'brandname' => $request->brandname,
            ],
            [
                'abrandname' => 'required', // knowingly doing it.
            ],
            [
                'required' => ':attribute field is required',
            ]
        );

        if (!$request->back) {
            if ($request->date == 0) $request->date = '';
            if ($request->brand_industry == 0) $request->brand_industry = '';
            if ($request->country == 0) $request->country = '';

            $validator = Validator::make(
                [
                    'brand_industry' => $request->brand_industry,
                    'brandname'      => $request->brandname,
                    'first_name'     => $request->first_name,
                    'last_name'      => $request->last_name,
                    'country'        => $request->country,
                ],
                [
                    'brand_industry' => 'required',
                    'brandname'      => 'required',
                    'first_name'     => 'required|alpha',
                    'last_name'      => 'required|alpha',
                    'country'        => 'required',
                ]
            );

            Session(
                [
                    'brandname'      => $request->brandname,
                    'first_name'     => $request->first_name,
                    'username'       => $request->first_name,
                    'last_name'      => $request->last_name,
                    'brand_industry' => $request->brand_industry,
                    'description'    => $request->description,
                    'website'        => $request->website,
                    'twitter'        => $request->twitter,
                    'facebook'       => $request->facebook,
                    'country'        => $request->country,
                ]
            );
        }

        if ($validator->fails()) {
            $data['countries'] = ['' => 'Select Country *'];
            $data['countries'] = array_merge($data['countries'], $this->allCountries());

            $data['industries'] = $this->allIndustries();
            $agent              = new Agent();
            if ($agent->isMobile() || $agent->isTablet()) {
                return 'error';
            }

            return view('auth.partials.step_two_brand', $data)->withErrors($validator->errors(), 'login');
        } else {
            $agent = new Agent();
            if ($agent->isMobile() || $agent->isTablet()) {
                return 'next';
            }

            return view('auth.partials.step_three');
        }

    }//stepTwoBrand()

    public function allCountries() {
        return \Cache::get('countries',function (){
            return DB::table('countries')->lists('name', 'id');
        });
    }

    public function allIndustries() {
        $industriesSelect = ['0' => 'Select Industry *'];
        $industries       = DB::table('brand_industries')->lists('name', 'id');
        $industries       = array_merge($industriesSelect, $industries);

        return $industries;
    }
}