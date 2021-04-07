<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get(
    '/user',
    function (Request $request) {
        return $request->user();
    }
);

Route::get('cs', function () {

        $list =  \App\Models\Users::query()->paginate(5);
//        $list = fractal($list, new \App\Transformers\Api\UsersTransformer());
        return \App\Http\Controllers\Common\ApiHelper::responseSuccess($list);
    }
);
