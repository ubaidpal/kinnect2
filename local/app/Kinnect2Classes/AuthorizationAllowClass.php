<?php
namespace App\Kinnect2Classes;

use App\Facades\AuthorizationAllowClassFacade;
use Config;
use App\AuthorizationAllow;

class AuthorizationAllowClass implements AuthorizationAllowClassInterface {


	public function Setting( $type, $type_id, $permission, $param ) {

		$resource                = new AuthorizationAllow();
		$resource->resource_type = $type;
		$resource->resource_id   = $type_id;
		$resource->action        = $param;
		$resource->permission    = ( Config::get( 'constants.' . $permission ) );

		$resource->save();
	}

	public function deleteResource( $id, $type ) {
		$resource = AuthorizationAllow::where( 'resource_id', $id )->where( 'resource_type', $type )->get();

		foreach ( $resource as $resources ) {
			$resources->delete();
		}
	}

	public function getSettingPermissionValue( $type, $type_id, $action ) {
		return AuthorizationAllow::where( 'resource_type', $type )
		                         ->where( 'resource_id', $type_id )
		                         ->where( 'action', $action )
		                         ->pluck( 'permission' );
	}


	public function changeSetting( $type, $type_id, $permission, $param ) {
		$resource = AuthorizationAllow::where( 'resource_type', $type )->where( 'resource_id', $type_id )->where( 'action', $param )->first();

		if ( $resource ) {
			$resource->permission = ( Config::get( 'constants.' . $permission ) );
			$resource->update();
		}else{
			$this->Setting( $type, $type_id, $permission, $param );
		}
	}
}

