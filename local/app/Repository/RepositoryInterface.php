<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05-11-15
 * Time: 12:58 PM
 */

namespace App\Repository;

interface RepositoryInterface {
	public function all();
	public function find($id);
	public function saveOrUpdate($id = null);
}
