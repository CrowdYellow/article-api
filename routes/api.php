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
    // 分类列表
    $api->get('/categories', 'CategoryController@index');
    // 文章列表
    $api->get('/articles', 'ArticlesController@index');
    // 文章详情页
    $api->get('/articles/{id}', 'ArticlesController@show');

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
        // 消息列表
        $api->get('user/messages', 'MessagesController@index');
        // 对话列表
        $api->get('user/messages/{id}', 'MessagesController@chatWithUser');
        // 发送消息
        $api->post('user/messages/{id}', 'MessagesController@store');
    });
});

$api->version('v2', function($api) {
    $api->get('version', function() {
        return response('this is version v2');
    });
});
