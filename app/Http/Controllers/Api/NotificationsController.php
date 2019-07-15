<?php

namespace App\Http\Controllers\Api;

use App\Transformers\NotificationTransformer;

class NotificationsController extends Controller
{
    protected $notificationTransformer;

    public function __construct(NotificationTransformer $notificationTransformer)
    {
        $this->notificationTransformer = $notificationTransformer;
    }

    public function index()
    {
        // 当前用户
        $user = $this->user();

        // 获取登录用户的所有通知
        $notifications = $user->notifications()->paginate(20);

        $data = [];

        foreach ($notifications as $notification) {
            $data[] = $this->notificationTransformer->transform($notification);
        }

        // 标记为已读，未读数量清零
        $user->markAsRead();

        return $this->data(config('code.success'), 'success', $data);
    }
}
