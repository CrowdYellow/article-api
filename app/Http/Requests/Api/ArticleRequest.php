<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title'       => 'required|min:2',
            'body'        => 'required|min:3',
            'category_id' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'title.min' => '标题必须至少两个字符',
            'body.min'  => '文章内容必须至少三个字符',
        ];
    }
}
