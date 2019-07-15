<?php


namespace App\Transformers;


use App\Models\User;

class UserTransformer
{
    public function transform(User $user)
    {
        return [
            'id'                 => $user->id,
            'name'               => $user->name,
            'phone'              => $user->phone,
            'avatar'             => env('APP_URL') . $user->avatar,
            'introduction'       => $user->introduction,
            'article_count'      => $user->article_count,
            'follow_count'       => $user->follow_count,
            'fans'               => $user->fans,
            'notification_count' => $user->notification_count,
            'created_at'         => (string)$user->created_at,
            'updated_at'         => (string)$user->updated_at,
        ];
    }
}