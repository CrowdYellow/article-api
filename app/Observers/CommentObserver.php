<?php

namespace App\Observers;

use App\Models\Comment;

class CommentObserver
{
    public function created(Comment $comment)
    {
        $comment->article->updateCommentCount();
    }

    public function creating(Comment $comment)
    {
        $comment->text = clean($comment->text, 'user_topic_body');
    }

    public function deleted(Comment $comment)
    {
        $comment->article->updateCommentCount();
    }
}
