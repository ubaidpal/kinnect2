<?php
namespace App\Kinnect2Classes;

interface AuthorizationAllowClassInterface{
    public function Setting($type,$type_id,$permission,$param);

    public function deleteResource($id,$type);

    public function changeSetting($type,$type_id,$permission,$param);
}