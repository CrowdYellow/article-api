<?php

namespace App\Http\Requests\Api;

class CommentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'text'       => 'required',
            'article_id' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'text.required'       => '请输入评论',
            'article_id.required' => '请选择文章id',
            'article_id.numeric'  => '文章id必须是数字',
        ];
    }
}
