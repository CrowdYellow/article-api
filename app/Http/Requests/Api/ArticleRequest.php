<?php

namespace App\Http\Requests\Api;

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
            'title.required'       => '标题必填',
            'title.min'            => '标题必须至少两个字符',
            'body.required'        => '文章内容必填',
            'category_id.required' => '分类必选',
            'body.min'             => '文章内容必须至少三个字符',
        ];
    }
}
