<?php

namespace App\Http\Requests\Api;

class MessageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'body' => "required"
        ];
    }

    public function messages()
    {
        return [
            'body.required' => '请输入内容'
        ];
    }
}
