<?php

namespace App\Http\Requests\Api;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'         => 'required|between:4,25|string|unique:users,name',
            'password'     => 'required|string|confirmed|min:6',
            'phone'        => [
                'required',
                'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$/',
                'unique:users'
            ],
            'captcha_key'  => 'required|string',
            'captcha_code' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required'         => '用户名不能为空。',
            'name.between'          => '用户名必须介于 6 - 25 个字符之间。',
            'name.string'           => '用户名必须是字符串。',
            'name.unique'           => '用户名已被占用，请重新填写',
            'phone.required'        => '手机号不能为空。',
            'phone.regex'           => '手机号格式不对。',
            'phone.unique'          => '手机号已注册。',
            'password.required'     => '密码不能为空。',
            'password.confirmed'    => '两次输入密码不一致。',
            'password.min'          => '密码最低6位。',
            'password.string'       => '密码必须是字符串。',
            'captcha_key.required'  => '验证码的key不能为空。',
            'captcha_key.string'    => '验证码的key必须是字符串。',
            'captcha_code.required' => '验证码的code不能为空。',
            'captcha_code.string'   => '验证码的code必须是字符串。',
        ];
    }
}
