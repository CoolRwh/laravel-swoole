<?php

namespace App\Http\Controllers\Common;

use App\Enums\Common\CodeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ApiHelper extends Controller
{

    /**
     * Http POST
     *
     * @param string $url 请求url.
     * @param array|string|null $params 参数.
     * @param array $headers 请求头.
     *
     * @return boolean|mixed
     */
    public static function httpPost($url, $params = null, array $headers = [])
    {
        $ch = curl_init();
        curl_setopt_array($ch, self::$curlOptions);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true); //HTTP POST
        if (is_string($params) || is_array($params)) {
            is_array($params) && $params = http_build_query($params);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        $headers && curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if (self::CURL_DEBUG) {
            $f = fopen(storage_path('logs/curl-verbose-'.date('Y-m-d').'.log'), 'w+');
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            curl_setopt($ch, CURLOPT_STDERR, $f);
        }
        $ret = curl_exec($ch);
        if ($errNo = curl_errno($ch)) {
            Log::error('[ApiHelper][httpPost]httpPost failed', [$url, $params, $headers, $errNo, curl_error($ch)]);
            $ret = false;
        }
        curl_close($ch);
        return $ret;
    }



    /**
     *
     * @param $data
     * @param string $message
     * @param int $code
     * @param int $httpCode
     * @return \Illuminate\Http\JsonResponse
     */
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
