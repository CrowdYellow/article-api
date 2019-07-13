<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        return $this->attemptLogin($request->phone, $request->password);
    }

    /**
     * 校验登陆
     * @param $phone
     * @param $password
     * @return mixed
     */
    public function attemptLogin($phone, $password)
    {
        // 校验用户名和密码
        if (!$token = Auth::guard('api')->attempt(['phone' => $phone, 'password' => $password])) {
            return $this->data(config('code.validate_err'), '用户名或密码错误');
        }
        return $this->token($token);
    }

    /**
     * 返回token
     * @param $token
     * @return mixed
     */
    public function token($token)
    {
        $data = [
            'token' => 'Bearer '.$token,
            'expires_in' => Auth::guard('api')->factory()->getTTL()
        ];
        return $this->data(config('code.success'), '登陆成功', $data);
    }
}
