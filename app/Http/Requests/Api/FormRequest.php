<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest as BaseFormRequest;
use Illuminate\Contracts\Validation\Validator;

class FormRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function failedValidation(Validator $validator)
    {
        $data = [
            'status'  => config('code.validate_err'),
            'message' => '参数验证失败',
            'data'    => $validator->getMessageBag()->all(),
        ];
        exit(json_encode($data, true));
    }
}