<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Api\RegisterRequest;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        if (!$this->verifyCaptcha($request->captcha_key, $request->captcha_code)) {
            return $this->data(config('code.validate_err'), '验证码有误');
        }

        $user = $this->create($request);

        $user->avatar = env('APP_URL').$user->avatar;

        return $this->data(config('code.success'), '注册成功', $user);
    }

    /**
     * 创建用户
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return User::create([
            'name'     => $data['name'],
            'phone'    => $data['phone'],
            'password' => bcrypt($data['password']),
            'avatar'   => '/images/user/default.png',
        ]);
    }

    /**
     * 检验验证码
     * @param $key
     * @param $code
     * @return bool
     */
    public function verifyCaptcha($key, $code)
    {
        $captchaData = Cache::get($key);
        if (!$captchaData) {
            return false;
        }
        if (!hash_equals($captchaData['code'], $code)) {
            // 验证错误就清除缓存
            Cache::forget($key);
            return false;
        }
        // 清除图片验证码缓存
        Cache::forget($key);
        return true;
    }
}
