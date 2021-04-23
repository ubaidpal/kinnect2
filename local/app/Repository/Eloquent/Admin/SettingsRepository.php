<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : kinnect2
 * Product Name : PhpStorm
 * Date         : 19-Apr-16 2:56 PM
 * File Name    : SettingsRepository.php
 */

namespace App\Repository\Eloquent\Admin;

use Bican\Roles\Models\Permission;

class SettingsRepository
{

    public function getUsersPermissions($id) {
        $data = \DB::table('permission_user')
                  ->join('permissions', 'permissions.id', '=', 'permission_user.permission_id')
                  ->where('permission_user.user_id', $id)
                  ->select('permissions.name','permissions.id')
                  ->get();
        $perms = [];
        foreach ($data as $item) {
            $perms[$item->id] = $item->name;
        }
        return $perms;
    }

    public function getAllPermissions() {
        return Permission::all()->keyBy('id');
    }

    public function updateUserPermissaions($user, $permissions) {
        $user->detachAllPermissions();

        foreach ($permissions as $permission) {
            $user->attachPermission($permission);
        }
    }


}
