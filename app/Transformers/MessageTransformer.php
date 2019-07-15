<?php


namespace App\Transformers;


use App\Models\Message;

class MessageTransformer
{
    public function transform(Message $message)
    {
        return [
            'id'               => $message->id,
            'from_user_id'     => $message->from_user_id,
            'from_user_name'   => $message->fromUser->name,
            'from_user_avatar' => env('APP_URL') . $message->fromUser->avatar,
            'to_user_id'       => $message->to_user_id,
            'to_user_name'     => $message->toUser->name,
            'to_user_avatar'   => env('APP_URL') . $message->toUser->avatar,
            'body'             => $message->body,
            'has_read'         => $message->has_read,
            'dialog_id'        => $message->dialog_id,
            'read_at'          => $message->read_at,
            'created_at'       => (string)$message->created_at,
            'updated_at'       => (string)$message->updated_at,
        ];
    }
}