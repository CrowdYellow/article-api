<?php

namespace App\Http\Requests\Api;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone'    => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'phone.required'    => '手机号不能为空。',
            'phone.string'      => '手机号必须是字符串。',
            'password.required' => '密码不能为空。',
            'password.string'   => '密码必须是字符串。',
        ];
    }
}
