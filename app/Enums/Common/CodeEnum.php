<?php


namespace App\Enums\Common;

/**
 * Class CodeEnum 业务代码汇总
 * @package App\Enums\Common
 */
class CodeEnum
{
    // 0-99 通用错误吗
    const CODE_SUCCESS                  = 200;
    const CODE_FAILED                   = 1;
    const CODE_PARAM_ERROR              = 2;
    const CODE_FILE_UPLOAD_FAILED       = 3;
    const CODE_DATABASE_ERROR           = 4;
    const CODE_SIGN_INVALID             = 5;
    const CODE_TOKEN_INVALID            = 6;
    const CODE_CURL_REQUEST_FAILED      = 7;
    const CODE_CONFIG_NOT_EXIST         = 8;
    const CODE_SEND_ERROR               = 9;
    const CODE_AUTHENTICATE_ERROR       = 10;
    const CODE_VALIDATION_ERROR         = 11;
    const CODE_EXCEPTION                = 12;
    const CODE_API_EXCEPTION            = 13;
    // 数据操作失败
    const CODE_CREATE_FAILED            = 14;
    const CODE_UPDATE_FAILED            = 15;
    const CODE_DELETE_FAILED            = 16;
    const CODE_GET_FAILED               = 17;
    const CODE_NO_OPERATE               = 18;
    const CODE_MIN_PAY                  = 19;


    const CODE_PARAM_ERR               = 422;


    public static $errorMessage = [
        self::CODE_SUCCESS             => 'SUCCESS',
        self::CODE_FAILED              => '系统错误，请稍后重试',
        self::CODE_PARAM_ERROR         => '参数不合法',
        self::CODE_FILE_UPLOAD_FAILED  => '上传错误',
        self::CODE_DATABASE_ERROR      => '数据库错误',
        self::CODE_SIGN_INVALID        => '签名不合法',
        self::CODE_TOKEN_INVALID       => 'token不合法',
        self::CODE_CURL_REQUEST_FAILED => 'curl请求失败',
        self::CODE_CONFIG_NOT_EXIST    => '配置不存在',
        self::CODE_SEND_ERROR          => '通知发送失败',
        self::CODE_AUTHENTICATE_ERROR  => '您没有操作权限',
        self::CODE_VALIDATION_ERROR    => '验证失败',
        self::CODE_EXCEPTION           => '程序异常,请稍后重试',
        self::CODE_API_EXCEPTION       => 'api请求异常，请稍后重试',
        self::CODE_CREATE_FAILED       => '创建失败',
        self::CODE_UPDATE_FAILED       => '更新失败',
        self::CODE_DELETE_FAILED       => '删除失败',
        self::CODE_GET_FAILED          => '未获取到数据',
        self::CODE_NO_OPERATE          => '无需操作',
        self::CODE_MIN_PAY             => '小于最低下单金额',
        self::CODE_PARAM_ERR           => '参数错误！'

    ];
}
