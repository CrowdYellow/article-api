<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    use Helpers;

    public function data($code, string $msg, $data = null)
    {
        $info = [
            'status'  => $code,
            'message' => $msg,
            'data'    => $data,
        ];

        return $this->response->array($info);
    }
}