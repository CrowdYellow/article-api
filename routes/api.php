<?php

use Illuminate\Http\Request;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function($api) {
    // 获取验证码
    $api->get('/captcha', 'CaptchaController@getCaptcha');
    // 用户注册
    $api->post('/register', 'RegisterController@register');
    // 用户登录
    $api->post('/login', 'LoginController@login');
});

$api->version('v2', function($api) {
    $api->get('version', function() {
        return response('this is version v2');
    });
});
