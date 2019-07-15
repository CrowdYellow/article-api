<?php


namespace App\Transformers;


use App\Models\Comment;

class CommentTransformer
{
    public function transform(Comment $comment)
    {
        return [
            'id'         => $comment->id,
            'text'       => $comment->text,
            'good_count' => $comment->good_count,
            'name'       => $comment->user->name,
            'avatar'     => env('APP_URL') . $comment->user->avatar,
            'created_at' => (string)$comment->created_at,
        ];
    }
}