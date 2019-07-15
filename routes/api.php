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

    // 需要 token 验证的接口
    $api->group(['middleware' => 'api.auth'], function($api) {
        // 当前登录用户信息
        $api->get('user', 'UsersController@me');
        // 是否关注该用户
        $api->get('user/followed/{id}', 'UsersFollowsController@hasFollowedThis');
        // 关注用户
        $api->post('user/followed/{id}', 'UsersFollowsController@followedThis');
        // 用户的关注
        $api->get('user/followed', 'UsersFollowsController@followed');
        // 用户的粉丝
        $api->get('user/follower', 'UsersFollowsController@follower');
    });
});

$api->version('v2', function($api) {
    $api->get('version', function() {
        return response('this is version v2');
    });
});
