<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Facades\Kinnect2;
use App\Http\Requests;
use App\Repository\Eloquent\BrandRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    protected $data;

    /**
     * @var Brand
     */
    /**
     * @var BrandRepository
     */
    private $brandRepository;

    /**
     * @param Brand $brand
     * @param Request $middleware
     */
    public function __construct(BrandRepository $brandRepository, Request $middleware)
    {

        $this->user_id = $middleware['middleware']['user_id'];
        @$this->data->user = $middleware['middleware']['user'];
        $this->is_api = $middleware['middleware']['is_api'];
        $this->brandRepository = $brandRepository;

    }

    public function index()
    {
        //
    }

    public function brands()
    {

        if($this->is_api){
            $brands = \Kinnect2::_brand_details(\Kinnect2::recomendedBrands());
            $data['results'] = $brands;
            $data['count'] = count($brands);
            return \Api::success($data);
        }
        
        return view('user.brand.brands')->with('page_Title' , 'Recommended Brands');;
    }

    public function myBrands()
    {
        if($this->is_api){
            $brands = \Kinnect2::_brand_details(\Kinnect2::myBrands());

            $data['results'] = $brands;
            $data['count'] = count($brands);
            return \Api::success($data);
        }
        return view('user.brand.myBrands')->with('page_Title' , 'My Brands');
    }

    public function profile($user_id)
    {
        $this->data->brand = $this->brandRepository->profile($user_id);
        $this->data->user = $this->data->brand->user;
        $this->data->kinnectors = $this->brandRepository->getBrandKinnectors($user_id);
        $this->data->brand = $this->brandRepository->profile($user_id);
        $data = (array)$this->data;
        return view('profile.brand.index', $data);
    }

    public function profileInfo(Brand $brand, $user_id)
    {
        $this->data->brand = $this->brandRepository->profile($user_id);
        $this->data->user = $this->data->brand->user;
        $this->data->kinnectors = $this->brandRepository->getBrandKinnectors($user_id);
        $this->data->brand = $this->brandRepository->profileInfo($user_id);
        $data = (array)$this->data;
        return view('profile.brand.info', $data);
    }

    public function profileSetting()
    {
        dd('View is not created');
    }

    public function brandsKinnectors(Request $request,$brand_id = null)
    {

        if($this->is_api){
            if(empty($request->brand_id) || !isset($request->brand_id)) {
                return \Api::invalid_param();
            }else{
                $brand_id = $request->brand_id;
            }
        }
        $this->data->brand = $this->brandRepository->profile($brand_id);
        $this->data->user = $this->data->brand->user;
        $this->data->kinnectors = $this->brandRepository->getBrandKinnectors($brand_id);
        if($this->is_api){
            $brands = \Kinnect2::_brand_details($this->data->kinnectors );
            $data['results'] = $brands;
            $data['count'] = count($brands);
            return \Api::success($data);
        }
        $this->data->title = $this->data->user->name . ' - ' . 'Profile';
        $data = (array)$this->data;
        return view('user.brand.kinnectors', $data);
    }

    public function follow(Request $request)
    {
        if(empty($request->brand_id) || !isset($request->brand_id)) {
            if ($this->is_api) {
                return \Api::invalid_param();
            }
        }
        if ($this->user_id) {
            if ($request->brand_id) {
                if (Brand::isFollowing($request->brand_id, $this->user_id) > 0) {
                    $this->brandRepository->updateFollowing($request->brand_id, $this->user_id);
                } else {
                    $this->brandRepository->follow($request->brand_id, $this->user_id);

                }

                if($this->is_api){
                    return \Api::success_with_message();
                }
            }
        }

        return 'success';
    }

    public function unFollow(Request $request)
    {
        if(empty($request->brand_id) || !isset($request->brand_id)) {
            if ($this->is_api) {
                return \Api::invalid_param();
            }
        }
        if ($this->user_id) {
            //remove follower by brand
            if($request->brand == 1 && $request->user_id){
                $this->brandRepository->unfollow( $this->user_id,$request->user_id);
            }
            if ($request->brand_id) {
                $this->brandRepository->unfollow($request->brand_id, $this->user_id);
            }
            if($this->is_api){
                return \Api::success_with_message();
            }
        }
        return 'success';
    }

    public function update(Request $request)
    {
        $this->brandRepository->update($this->user_id);
        if ($this->is_api) {
            return User::findOrNew($this->user_id);
        } else {
            return redirect()->back();
        }

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get_all_recommended()
    {
        $brands['brands'] = \Kinnect2::recomendedAllBrands();
        $brands['type'] = 'recommended-brands';

        return view('templates.partials.paginate.users', $brands);
    }

    public function get_all_my_brand()
    {
        $user_id = \Input::get('userId');
        $user = User::whereId($user_id)->orWhere('username', $user_id)->first();
        $brands['brands'] = \Kinnect2::myAllBrands();
        $brands['type'] = 'following';
        return view('templates.partials.paginate.users', $brands);
    }

    public function store_created(){

        $user = User::whereId($this->user_id)->with('brand_detail')->first();

        $brand =  Brand::find($user->brand_detail->id);

        $brand->store_created = 1;
        $brand->save();

        return redirect('store/'.$user->username.'/admin/categories');

    }

    public function user_brands() {
        if($this->is_api){
            if(!\Input::has('user_id')){
                return \Api::invalid_param();
            }
            $user_id = \Input::get('user_id');

            $brands = \Kinnect2::myAllBrands($user_id);
             $data = \Kinnect2::_brand_details($brands);
            return \Api::success_list($data);
        }
    }
}
