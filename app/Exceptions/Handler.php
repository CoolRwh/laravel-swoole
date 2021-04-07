<?php

namespace App\Exceptions;

use App\Enums\Common\CodeEnum;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     *
     * @param Exception $exception
     * @return mixed|void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {



        // 是否记录异常日志 默认记录
        $message    = $exception->getMessage();
        $code       = $exception->getCode();
        $httpCode   = Response::HTTP_INTERNAL_SERVER_ERROR;
        $resData = [];
        $resData['data']            = [];
        $resData['mate']['message'] = $message;
        $resData['mate']['code']    = $code;

        if (method_exists($exception, 'getStatusCode')) {
            $httpCode = $exception->getStatusCode();
        }
        $resData['mate']['httpCode']    = $httpCode;

        // 验证异常 422
        if ($exception instanceof ValidationException) {
            $resData['mate']['message'] = $exception->validator;
            return response()->json($resData,$httpCode);
        }

        if (empty($message)){
            $resData['mate']['message'] = Response::$statusTexts[$httpCode];
        }
        return response()->json($resData);
//        return parent::render($request, $exception);
    }
}
