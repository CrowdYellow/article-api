<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Transformers\CommentTransformer;

class CommentsController extends Controller
{
    protected $commentTransformer;

    public function __construct(CommentTransformer $commentTransformer)
    {
        $this->commentTransformer = $commentTransformer;
    }

    public function getCommentsBy($id)
    {
        $comments = Comment::where('article_id', $id)->paginate(20);
        $data = [];
        foreach ($comments as $comment) {
            $data[] = $this->commentTransformer->transform($comment);
        }
        return $this->data(config('code.success'), 'success', $data);
    }
}
