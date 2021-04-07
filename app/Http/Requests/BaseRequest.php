<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Dotenv\Exception\ValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class BaseRequest extends FormRequest
{
    /**
     * 错误状态码和消息
     *
     * @var array
     */
    protected $messages = [
        // field name => [ errcode, errmsg ],
    ];

    /**
     * 根据控制器方法，配置不同的验证规则
     *
     * @var array
     */
    protected $rules = [
        //controller's method name => rules
    ];
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    public function messages()
    {
        $method   = $this->route()->getActionMethod();
        $messages = [];

        if (isset($this->messages[$method])) {
            $messages = $this->messages[$method];
        } elseif (!empty($this->messages['__default'])) {
            $messages = $this->messages['__default'];
        }
        return $messages;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = $this->route()->getActionMethod();
        $rules  = [];

        // 是否有对应方法的验证规则
        if (isset($this->rules[$method])) {
            $rules = $this->rules[$method];
        } elseif (!empty($this->rules['__default'])) {
            $rules = $this->rules['__default'];
        }
        return $rules;
    }

    /**
     * 验证失败后
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator)
    {
        $errMessages = $validator->errors()->getMessages();
        foreach ($errMessages as $key => $errMsg) {
            // 调整格式
            if (isset($this->messages[$key])) {
                $msg = $this->messages[$key];
                if (!is_array($msg) || count($msg) < 2) {
                    continue;
                }
                throw new ValidationException($msg[1], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        // 只提示第一个
        $errMsg = array_shift($errMessages);
        throw new ValidationException($errMsg[0], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
