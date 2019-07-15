<?php


namespace App\Transformers;


class NotificationTransformer
{
    public function transform($notifications)
    {
        return [
            'type'       => $this->whichType($notifications->type),
            'data'       => $notifications->data,
            'read_at'    => $notifications->read_at,
            'created_at' => $notifications->created_at,
        ];
    }

    public function whichType($type)
    {
        switch ($type) {
            case 'App\\Notifications\\ArticleComment':
                return "文章评论";
                break;
            case 'App\\Notifications\\NewMessages':
                return "收到新消息";
                break;
            case 'App\\Notifications\\UserFollow':
                return "用户关注";
                break;
        }
    }
}