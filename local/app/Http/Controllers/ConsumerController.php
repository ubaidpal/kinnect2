<?php

namespace App\Http\Controllers;

use App\Consumer;
use App\Repository\Eloquent\UsersRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ConsumerController extends Controller
{

    protected $data;
    private $user_id;
    protected $is_api;

    public function index()
    {
        //
    }

    public function __construct(Request $middleware) {
        $this->user_id = $middleware['middleware']['user_id'];
        @$this->data->user = $middleware['middleware']['user'];
        $this->is_api = $middleware['middleware']['is_api'];
    }

}
