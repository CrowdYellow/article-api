<?php

namespace App\Http\Controllers\Api;

use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CaptchaController extends Controller
{
    public function getCaptcha(CaptchaBuilder $captchaBuilder)
    {
        $key       = 'captcha-' . Str::random(15);
        $captcha   = $captchaBuilder->build();
        $expiredAt = now()->addMinutes(2);
        Cache::put($key, ['code' => $captcha->getPhrase()], $expiredAt);
        $data = [
            'captcha_key'   => $key,
            'captcha_image' => $captcha->inline()
        ];
        return $this->data(config('code.success'), 'success', $data);
    }
}
