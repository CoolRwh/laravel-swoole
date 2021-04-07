<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthRequest;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    public function login(AuthRequest $request)
    {

        try{

        }catch (\Exception $exception)
        {
             new \Exception('1','1');
        }

    }


}
