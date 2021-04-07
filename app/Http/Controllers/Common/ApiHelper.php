<?php

namespace App\Http\Controllers\Common;

use App\Enums\Common\CodeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ApiHelper extends Controller
{
    //

    public static function responseSuccess(
        $data,
        $message    = '',
        $code       = CodeEnum::CODE_SUCCESS,
        $httpCode   = Response::HTTP_OK
    )
    {
        $responseData = [];
        $responseData['data']               = [];
        $responseData['meta']               = [];
        $responseData['meta']['code']       = $code;
        $responseData['meta']['httpCode']   = $httpCode;
        if (is_object($data)) {
            $data = collect($data)->toArray();
        }

        if (empty($message) && array_key_exists($code, CodeEnum::$errorMessage)) {
            $message = CodeEnum::$errorMessage[$code];
        }

        $responseData['meta']['message']   = $message;

        /**
         * 判断是否有分页
         */
        if (isset($data['current_page']) && isset($data['total'])) {
            $list = $data['data'];
            unset($data['data']);
            $responseData['data'] = $list;
            $pagination = $data;
            if (!empty($pagination)) {
                $responseData['meta']['pagination'] = $pagination;
            }
        }else{
            $responseData['data'] = $data['data'];
            if (!empty($data['meta'])){
                $responseData['meta'] += $data['meta'];
            }
        }
        return response()->json($responseData);
    }


}
