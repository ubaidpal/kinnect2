<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : kinnect2
 * Product Name : PhpStorm
 * Date         : 09-Mar-2016 8:21 PM
 * File Name    : SuperAdminRepository.php
 */

namespace App\Repository\Eloquent\Admin;

use App\ActivityAction;
use App\Report;
use App\User;

class SuperAdminRepository
{
    public function __construct() {

    }

    public function members_count() {
        return User::where('user_type', \Config::get('constants.REGULAR_USER'))->orWhere('user_type', \Config::get('constants.BRAND_USER'))->count();
    }

    public function type_count($type) {
        return User::where('user_type', $type)->count();
    }

    public function all_login() {
        return User::sum('login_counter');
    }

    public function today_login() {
        return User::where('lastlogin_date', '>=', \Carbon\Carbon::now()->format('Y-m-d'))
                   ->groupBy('date')
                   ->orderBy('date', 'DESC')
                   ->first(array(
                       \DB::raw('Date(lastlogin_date) as date'),
                       \DB::raw('COUNT(*) as "login"')
                   ))->login;
    }

    public function getFlaggedPosts() {
        return Report::whereRead(0)->with('post')->orderBy('report_id', 'DESC')->paginate(25);
    }

    public function updateReportStatus($id) {
        $report       = Report::find($id);
        $report->read = 1;
        $report->save();
        return $report->action_id;
    }

    public function updatePostStatus($action_id) {
        $post = ActivityAction::find($action_id);

        if($post) {
            $post->is_flagged = 1;
            $post->save();
        }

        return TRUE;
    }

}
