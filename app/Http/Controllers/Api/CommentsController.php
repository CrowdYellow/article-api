<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CommentRequest;
use App\Models\Article;
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
        $comments = Comment::where('article_id', $id)->orderBy('created_at', 'asc')->paginate(20);
        $data     = [];
        foreach ($comments as $comment) {
            $data[] = $this->commentTransformer->transform($comment);
        }
        return $this->data(config('code.success'), 'success', $data);
    }

    public function store(CommentRequest $request)
    {
        // 当前用户
        $user = $this->user();

        $article = Article::find($request->article_id);

        if (empty($article)) {
            return $this->data(config('code.refuse_err'), '文章不存在！');
        }

        if (!$article->is_allowed_comment) {
            return $this->data(config('code.refuse_err'), '该文章不能评论！');
        }

        $comment             = new Comment();
        $comment->text       = $request->text;
        $comment->user_id    = $user->id;
        $comment->article_id = $request->article_id;
        $comment->save();
        return $this->data(config('code.success'), '创建成功！');
    }

    public function destroy($id)
    {
        // 当前用户
        $user = $this->user();

        $comment = Comment::find($id);

        if (empty($comment)) {
            return $this->data(config('code.null'), '评论不存在');
        }

        if ($user->isAuthorOf($comment)) {
            $comment->delete();
            return $this->data(config('code.success'), '删除成功');
        }

        return $this->data(config('code.refuse_err'), '你无权删除');
    }
}
