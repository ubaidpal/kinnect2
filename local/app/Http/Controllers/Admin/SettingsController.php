<?php

namespace App\Http\Controllers\Admin;

use App\Repository\Eloquent\Admin\SettingsRepository;
use App\User;
use Bican\Roles\Models\Permission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Bican\Roles\Models\Role;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    protected $user_id = NULL;
    protected $user;
    /**
     * @var \App\Repository\Eloquent\Admin\SettingsRepository
     */
    private $settingsRepository;

    public function __construct(SettingsRepository $settingsRepository) {
        if(isset(\Auth::user()->id)) {
            $this->user_id = \Auth::user()->id;
            $this->user    = \Auth::user();
        }

        $this->settingsRepository = $settingsRepository;
    }


    public function index() {
        $data['users'] = User::where('user_type', '!=', '1')->where('user_type', '!=', '2')->orderBy('id', 'DESC')
                             ->where('id', '<>', $this->user_id)->orderBy('id', 'DESC')->paginate(25);

        $data['permissions'] = Permission::lists('name', 'id');
        return view('admin.settings.permissions', $data);
    }

    public function store(Request $request) {
        $users = $request->get('users');
        if(!empty($users)){
            foreach ($users as $user) {
                $user = User::find($user);
                $user->attachPermission($request->get('permission'));
            }
        }

        return redirect()->back();

    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
}
